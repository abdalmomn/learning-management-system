<?php

namespace App\Services;

use App\Mail\certificate;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Course_user_pivot;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Quiz_user_pivot;
use App\Models\Subject;
use App\Models\Transaction;
use App\Models\User;
use App\Models\User_video_pivot;
use App\Models\Video;
use FFMpeg\FFMpeg;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use function PHPUnit\Framework\isEmpty;

class DashboardService{
    public function show_all_students():array
    {
        $users = User::query()->where('type' , '=' , 'student')->count();
        if (Auth::user()->hasRole('admin')){
        if (!is_null($users)){
        $message = __('strings.getting all students successfully');
        return[
            'users' => $users,
            'message' => $message,
            'code' => 200,
        ];
        }else{
            $message = __('strings.there are no students');
            return [
            'users' => '',
            'message' => $message,
            ];
        }
        }else{
            $message = 'unauthorized';
            return [
              'users' => '',
              'message' => $message,
            ];
        }
    }
    public function show_all_teachers():array
    {
        $users = User::query()->where('type' , '=' , 'teacher')->count();
        if (Auth::user()->hasRole('admin')){
        if (!is_null($users)){
        $message = __('strings.getting all teachers successfully');
        return [
            'users' => $users,
            'message' => $message,
            'code' => 200,
        ];
        }else{
            $message = __('strings.there are no teachers');
            return [
                'users' => '',
                'message' => $message,
            ];
        }
        }else{
            $message = 'unauthorized';
            return [
                'users' => '',
                'message' => $message,
            ];
        }
    }

    public function show_all_courses():array
    {
        $courses = Course::all()->count();
        if (Auth::user()->hasRole('admin')) {
            if (!is_null($courses)) {
                $message = 'getting all courses';
            }else{
                $courses = [];
                $message = 'there are no courses at the moment';
            }
        }else{
            $courses = [];
            $message = 'unauthorized';
        }
        return [
            'courses' => $courses,
            'message' => $message,
        ];

    }
    public function show_courses_by_subject($subject_id):array
    {
        $courses = Course::query()
            ->join('subjects' , 'courses.subject_id' , '=' , 'subjects.id')
            ->where('subject_id' , $subject_id)
            ->select('courses.*')
            ->count();
        if (Auth::user()->hasRole('admin')){
            if (!is_null($courses)) {
            $message = __('strings.getting all courses successfully');
            return [
                'courses' => $courses,
                'message' => $message,
                'code' => 200,
            ];
        }else{
            $message = 'there are no courses for this subject';
            return [
                'courses' => '',
                'message' => $message,
            ];
        }
        }else{
            $message = 'unauthorized';
            return [
                'courses' => '',
                'message' => $message,
            ];
        }
    }

    public function show_all_videos()
    {
        $videos = Video::all()->count();
        if (Auth::user()->hasRole('admin')) {
            if (!is_null($videos)) {
                $message = 'getting all videos';
            }else{
                $videos = [];
                $message = 'there are no videos at the moment';
            }
        }else{
            $videos = [];
            $message = 'unauthorized';
        }
        return [
            'videos' => $videos,
            'message' => $message,
        ];
    }
    public function show_videos_by_course($course_id):array
    {
        $videos = Video::query()
            ->join('courses' , 'courses.id' , '=' , 'videos.course_id')
            ->where('course_id' , $course_id)
            ->select('videos.*')
            ->count();
        if (Auth::user()->hasRole('admin')){

            if (!is_null($videos)){
            $message = __('strings.getting all videos successfully');
            return [
                'videos' => $videos,
                'message' => $message,
            ];
        }else{
            $message = __('strings.there are no videos');
            return [
              'videos' => '',
              'message' => $message,
            ];
        }
        }else{
            $message = 'unauthorized';
            return [
                'videos' => '',
                'message' => $message,
            ];
        }
    }

    public function show_all_quizzes()
    {
        $quizzes = Quiz::all()->count();
        if (Auth::user()->hasRole('admin')) {
            if (!is_null($quizzes)) {
                $message = 'getting all quizzes';
            }else{
                $quizzes = [];
                $message = 'there are no quizzes at the moment';
            }
        }else{
            $quizzes = [];
            $message = 'unauthorized';
        }
        return [
            'quizzes' => $quizzes,
            'message' => $message,
        ];

    }
    public function show_quizzes_by_course() : array
    {
        $quizzes = Quiz::query()->get();
        if (!is_null($quizzes)) {
            if (Auth::user()->hasRole('admin')){

                if (Auth::user()->hasRole('admin')) {

                    $quizzes = Quiz::query()->get();
                    $message = __('strings.Getting all quizzes in data successfully');
                    $code = 200;

                } if (Auth::user()->hasRole('admin')) {
                    $quizzesUsers = Quiz_user_pivot::query()->where('type','admin')
                        ->where('user_id',Auth::id())
                        ->get();
                    if (!$quizzesUsers->isEmpty()){
                        $quizzes = [];

                        foreach ($quizzesUsers as $quizzesUser) {

                            $quizzes[]  = Quiz::query()->where('id',$quizzesUser->quiz_id)->get();

                        }
                        $quizzes = $quizzes;
                        $message = __('strings.Getting all your quizzes successfully');
                        $code = 200;


                    } else {
                        $quizzes =[];
                        $message = __('strings.You do not have any quiz');
                        $code = 404;
                    }
                }

            }else {
                $quizzes =[];
                $message = __('strings.You do not have any permission to show all quizzes');
                $code = 401;

            }
        }else{
            $quizzes = [];
            $message = __('strings.Not found any quiz');
            $code = 404;
        }

        return [
            'quizzes' => $quizzes,
            'message' => $message ?? [],
            'code' => $code ?? [],
        ];
    }

    public function show_profile($user_id):array
    {
        $user = User::query()
            ->where('id' , '=' , $user_id)
            ->first();
        if (Auth::user()->hasRole('admin')){
            if (!is_null($user)){
                $message = 'getting user information';
            return [
                'user' => $user,
                'message' => $message,
            ];
        }else{
            $message = 'information not found';
            return [
                'user' => '',
                'message' => $message,
            ];
        }
        }else{
            $message = 'unauthorized';
            return [
                'user' => '',
                'message' => $message,
            ];
        }
    }


    public function update_profile($request,$user_id) : array
    {

        if ((Auth::user()->hasRole('admin'))) {
            $user = User::query()
                ->where('id' , '=' , $user_id)
                ->first();
            if ($user) {
                $user->update([
                    'full_name' => $request['full_name'] ?? $user['full_name'],
                    'phone' => $request['phone'] ?? $user['phone'],
                    'birthday' => $request['birthday'] ?? $user['birthday'],
                    'address' => $request['address'] ?? $user['address'],
                    'image' => $request['image'] ?? $user['image'],
                ]);
                $user = User::query()->find($user_id);
                $message = __('strings.Updated profile successfully');
                $code = 200;

            } else {
                $user = [];
                $message = 'Not found';
                $code = 403;
            }
        }else{
            $message = 'unauthorized';
            return [
                'user' => '',
                'message' => $message,
            ];
        }
        return [
            'user' => $user,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function delete_profile($user_id):array
    {
        if (Auth::user()->hasRole('admin')){
            $user = User::query()
                ->where('id', '=', $user_id)
                ->first();
            if (!is_null($user)){
                $user = $user->delete();
                $message = 'user deleted successfully';
            }else
            {
                $user = [];
                $message = 'user not found';
            }
        }else{
            $user = [];
            $message = 'unauthorized';
        }
        return [
            'user' => $user,
            'message' => $message,
        ];
    }


    public function show_course($course_id)
    {
        if (Auth::user()->hasRole('admin')) {
            $pivot = Course_user_pivot::query()
                ->join('users', 'users.id', '=', 'course_user_pivots.user_id')
                ->join('courses', 'courses.id', '=', 'course_user_pivots.course_id')
                ->where('course_id', $course_id)
                ->select('users.full_name as username', 'courses.id as course_id')
                ->first();

            $course = Course::query()
                ->where('id', $course_id)
                ->first();

            $video = Video::query()
                ->where('course_id', $course_id)->get();
            $video_count = Video::query()
                ->where('course_id', $course_id)
                ->count();
            if ($course && $pivot) {
                $teacher_name = $pivot->username;
                if (!$video->isEmpty()) {
                    $message = __('strings.getting all videos for this course');
                    $code = 200;
                } else {
                    $video = [];
                    $message = __('strings.there are no videos at the moment');
                    $code = 404;
                }
            } else {
                $teacher_name = 'none';
                $message = __('strings.course not found');
                $code = 404;
            }
            $video['teacher_name'] = $teacher_name;
            if ($video_count)
                $video['videos_count'] = $video_count;
            else
                $video['videos_count'] = 'none';
        }else{
            $video = [];
            $message = 'unauthorized';
            $code = 403;
        }
        return [
            'video' => $video,
            'message' => $message,
            'code' => $code,

        ];
    }

    public function create_course($request) : array
    {
        if (Auth::user()->hasRole('admin')){
            $course = Course::query()->create([
                'subject_id' => $request['subject_id'],
                'name' => $request['name'],
                'content' => $request['content'],
                'poster' => $request['poster'],
                'requirements' => $request['requirements'],
                'price' => $request['price'],
            ]);
            Course_user_pivot::query()->create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
            ]);
            $message = __('strings.Course created successfully');
            $code=200;
        }else{
            $course = [];
            $message = __('strings.unauthorized');
            $code=403;
        }
        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function update_course($request,$id) : array
    {

        $course=Course::query()->find($id);
        if (!is_null($course)) {
            $any = Course_user_pivot::query()->where('paid',0)->where('course_id',$id)->first();
            if (!is_null($any)) {
                if (Auth::user()->hasRole('admin')) {
                    Course::query()->find($id)->update([
                        'subject_id' => $request['subject_id'] ?? $course['subject_id'],
                        'name' => $request['name'] ?? $course['name'],
                        'content' => $request['content'] ?? $course['content'],
                        'poster' => $request['poster'] ?? $course['poster'],
                        'hour' => $request['hour'] ?? $course['hour'],
                        'requirements' => $request['requirements'] ?? $course['requirements'],
                        'price' => $request['price'] ?? $course['price'],
                    ]);
                    $course=Course::query()->find($id);
                    $message = 'Updated course successfully';
                    $code=200;
                } else {
                    $course = [];
                    $message = __('strings.unauthorized');
                    $code=403;
                }
            }else{
                $course = [];
                $message = __('strings.This course does not belongs to you to delete it or not found in data');
                $code = 403;
            }
        }
        else{
            $course = [];
            $message = __('strings.The course not found');
            $code=404;
        }

        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function delete_course($id) : array
    {
        $course=Course::query()->find($id);
        if (!is_null($course)) {
            $any = Course_user_pivot::query()->where('paid',0)->where('course_id',$id)->first();
            if (!is_null($any)) {
                if(Auth::user()->hasRole('admin')) {
                    $course = $course->delete();
                    $message = __('strings.Deleting course successfully');
                    $code = 200;
                } else {
                    $course = [];
                    $message = 'you dont have permission for deleting this course';
                    $code = 403;
                }
            }
            else{
                $course = [];
                $message = __('strings.This course does not belongs to you to delete it or not found in data');
                $code = 403;
            }
        }else{
            $course = [];
            $message = __('strings.The course not found');
            $code=404;
        }

        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

//video operation
    public $videoDuration;
    public function getVideoDuration($videoFile): string
    {
        // Move the file to a permanent storage location
        $videoPath = $videoFile->storeAs('videos', $videoFile->getClientOriginalName(), 'public');

        // Get the full path of the stored file
        $fullVideoPath = storage_path('app/public/videos/' . $videoFile->getClientOriginalName());

        // Ensure the file exists
        if (!file_exists($fullVideoPath)) {
            throw new \Exception("File not found at: " . $fullVideoPath);
        }

        // Run FFmpeg command to get the duration of the video
        $ffmpegCommand = "ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . escapeshellarg($fullVideoPath);
        $durationInSeconds = shell_exec($ffmpegCommand);

        // Ensure the command executed successfully and returned a valid duration
        if ($durationInSeconds === null || $durationInSeconds === false) {
            throw new \Exception("Unable to retrieve video duration.");
        }

        // Convert duration from seconds to H:M:S format
        return $this->secondsToHms(trim($durationInSeconds));
    }



    public function add_Video($request, $course_id): array
    {
        if (Auth::user()->hasRole('admin')) {
            // Create the video record
            $video = Video::create([
                'course_id' => $course_id,
                'title' => $request['title'],
                'url' => $request['url'],
                'duration' => $request['duration'],
            ]);

            $user_video = User_video_pivot::create([
                'user_id' => Auth::id(),
                'video_id' => $video->id,
                'course_id' => $course_id,
                'watched' => 0,
            ]);

            // Update course total duration
            $course = Course::find($course_id);
            $courseTotalDurationSeconds = $this->hmsToSeconds($course->hour);
            $courseTotalDurationSeconds += $this->hmsToSeconds($request['duration']);
            $course->hour = $this->secondsToHms($courseTotalDurationSeconds);
            $course->save();
        } else {
            $video = [];
            $user_video = [];
            $message = __('strings.unauthorized');
        }

        $message = __('strings.video added successfully');
        return [
            'video' => $video,
            'user_video' => $user_video,
            'message' => $message
        ];
    }

    // Helper function to convert seconds to H:M:S format
    private function secondsToHms($seconds): string
    {
        return gmdate('H:i:s', $seconds);
    }

    // Helper function to convert H:M:S to seconds
    private function hmsToSeconds($hms): int
    {
        sscanf($hms, "%d:%d:%d", $hours, $minutes, $seconds);
        return $hours * 3600 + $minutes * 60 + $seconds;
    }



    public function update_videos($request, $course_id, $video_id): array
    {

        // Retrieve the video and course based on the passed parameters
        $videos = Video::query()
            ->join('courses', 'courses.id', '=', 'videos.course_id')
            ->where('videos.id' ,'=' , $video_id)
            ->where('courses.id' , '=' , $course_id)
            ->select('courses.id as course_id', 'videos.id as video_id')
            ->first();

        $courseId = $videos->course_id;
        $videoId = $videos->video_id;
        if ($course_id == $courseId && $video_id == $videoId) {

            // Retrieve the video record
            $video = Video::query()->find($video_id);
            $course = Course::query()->find($course_id);
            if ($video && $course) {
                $pivot = User_video_pivot::query()
                    ->where('video_id', $video_id)
                    ->first();

                if (Auth::user()->hasRole('admin') && $pivot->user_id == Auth::id()) {

                    // Check if a new video file is uploaded
                    if (request()->hasFile('url')) {

                        // Store the uploaded video file
                        $videoFile = request()->file('url');
                        $videoPath = $videoFile->storeAs('videos', $videoFile->getClientOriginalName(), 'public');
                        $fullVideoPath = storage_path('app/public/videos/' . $videoFile->getClientOriginalName());

                        // Ensure the file exists
                        if (!file_exists($fullVideoPath)) {
                            throw new \Exception("File not found at: " . $fullVideoPath);
                        }

                        // Initialize FFmpeg and get the video duration
                        $ffmpeg = FFMpeg::create();
                        try {
                            $ffmpegVideo = $ffmpeg->open($fullVideoPath);
                            $durationInSeconds = $ffmpegVideo->getFormat()->get('duration');
                        } catch (\Exception $e) {
                            throw new \Exception("FFmpeg could not open the file: " . $fullVideoPath);
                        }

                        // Convert duration from seconds to H:M:S format
                        $this->videoDuration = $this->secondsToHms($durationInSeconds);
                    } else {
                        // Use existing duration if no new video is uploaded
                        $this->videoDuration = $video->duration;
                    }

                    // Update video information
                    Video::query()->where('id', $video_id)->update([
                        'title' => $request['title'] ?? $video['title'],
                        'description' => $request['description'] ?? $video['description'],
                        'url' => request()->hasFile('url') ? $videoPath : $video['url'],
                        'duration' => $this->videoDuration,
                    ]);

                    // Update course total duration
                    $courseTotalDurationSeconds = $this->hmsToSeconds($course->hour);
                    $oldDurationSeconds = $this->hmsToSeconds($video->duration);
                    $newDurationSeconds = $this->hmsToSeconds($this->videoDuration);

                    if ($newDurationSeconds != $oldDurationSeconds) {
                        $courseTotalDurationSeconds -= $oldDurationSeconds;
                        $courseTotalDurationSeconds += $newDurationSeconds;
                    }

                    $course->hour = $this->secondsToHms($courseTotalDurationSeconds);
                    $course->save();

                    $video = Video::query()->find($video_id);
                    $message = __('strings.video has been updated successfully!');
                    $code = 200;
                } else {
                    $video = [];
                    $message = __('strings.unauthorized');
                    $code = 403;
                }
            } else {
                $video = [];
                $message = __('strings.video or course is not found');
                $code = 404;
            }
        } else {
            $video = [];
            $message = __('strings.wrong video or course');
            $code = 403;
        }

        return [
            'video' => $video,
            'message' => $message,
            'code' => $code,
        ];
    }

//delete video and decrease time from course hours
    public function delete_video($course_id,$video_id):array
    {

        $video = Video::query()->where('id' , $video_id)->first();
        if ($video){

            $video_duration = $video->duration;
            $video_seconds = hmsToSeconds($video_duration);

            $videoId  = $video->id;
            $courseId = $video->course_id;
            if ($video_id == $videoId && $course_id == $courseId){
                $pivot = User_video_pivot::query()
                    ->where('user_id' , Auth::id())
                    ->first();
                if ($pivot && $pivot->user_id == Auth::id()
                    || Auth::user()->hasRole('admin')){

                    $video = Video::query()->where('id' , $video_id)->delete();

                    //delete duration from course
                    $course = Course::query()->find($course_id);
                    $courseTotalDurationSeconds = hmsToSeconds($course->hour);
                    $courseTotalDurationSeconds -= $video_seconds;
                    $course->hour = secondsToHms($courseTotalDurationSeconds);
                    $course->save();

                    $message = __('strings.deleted successfully');
                    $code = 200;
                }else{
                    $video = [];
                    $message = __('strings.unauthorized');
                    $code = 403;
                }
            }else{
                $video = [];
                $message = __('strings.wrong video or course');
                $code = 403;
            }}else{
            $video = [];
            $message = __('strings.video not found');
            $code = 404;
        }
        return [
            'video' => $video,
            'message' => $message,
            'code' => $code,
        ];
    }


    public function show_video($course_id, $video_id): array
    {
        if (!Auth::check()) {
            return [
                'status' => 0,
                'video' => [],
                'video_url' => '',
                'message' => 'User is not authenticated',
            ];
        }
        if (Auth::user()->hasRole('admin')) {

            $course = Course::find($course_id);
            if ($course) {
                $video = Video::where('course_id', $course_id)->find($video_id);
                if ($video) {
                    $video->view += 1;
                    $video->save();
                    // Add to watched videos
                    $pivot = User_video_pivot::query()
                        ->where('video_id', $video_id)
                        ->where('course_id', $course_id)
                        ->where('user_id', Auth::id())
                        ->first();
                    if (is_null($pivot)) {
                        User_video_pivot::query()->create([
                            'user_id' => Auth::id(),
                            'video_id' => $video_id,
                            'course_id' => $course_id,
                            'watched' => 1,
                        ]);
                    }

                    // Assuming the video has a 'path' or 'url' attribute
                    $videoUrl = asset('storage/videos/' . $video->url);
                    $message = __('strings.video information');
                } else {
                    $videoUrl = '';
                    $message = __('strings.video not found');
                }
            } else {
                $video = '';
                $message = __('strings.course not found');
            }
        }else {
                $video = [];
                $user_video = [];
                $message = __('strings.unauthorized');
            }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }


    public function add_like($video_id):array
    {
        if (Auth::user()->hasRole('admin')) {

            $video = Video::query()
                ->where('id', $video_id)
                ->first();
            if ($video) {
                $video->like += 1;
                $video->save();
                $message = __('strings.liked successfully');
            } else {
                $video = [];
                $message = __('strings.video not found');
            }
        }else {
            $video = [];
            $user_video = [];
            $message = __('strings.unauthorized');
        }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }
    public function remove_like($video_id):array
    {
        if (Auth::user()->hasRole('admin')) {

            $video = Video::query()
                ->where('id', $video_id)
                ->first();
            if ($video) {
                $video->like -= 1;
                $video->save();
                $message = __('strings.like remove successfully');
            } else {
                $video = [];
                $message = __('strings.video not found');
            }
        }else {
            $video = [];
            $user_video = [];
            $message = __('strings.unauthorized');
        }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }
    public function add_dislike($video_id):array
    {
        if (Auth::user()->hasRole('admin')) {

            $video = Video::query()
                ->where('id', $video_id)
                ->first();
            if ($video) {
                $video->dislike += 1;
                $video->save();
                $message = __('strings.disliked successfully');
            } else {
                $video = [];
                $message = __('strings.video not found');
            }
        }else {
            $video = [];
            $user_video = [];
            $message = __('strings.unauthorized');
        }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }
    public function remove_dislike($video_id):array
    {
        if (Auth::user()->hasRole('admin')) {

            $video = Video::query()
                ->where('id', $video_id)
                ->first();
            if ($video) {
                $video->dislike -= 1;
                $video->save();
                $message = __('strings.remove dislike successfully');
            } else {
                $video = [];
                $message = __('strings.video not found');
            }
        }else {
            $video = [];
            $user_video = [];
            $message = __('strings.unauthorized');
        }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }



//quizzes -----------------------------------------------------------




    public function create_quiz($course_id): array
    {
        $course = Course::query()->where('id', $course_id)->first();
        if (!is_null($course)) {
            if (Auth::user()->hasRole('admin')) {
                $courseId = Course_user_pivot::query()->where('paid',0)
                ->where('user_id',Auth::id())
                ->where('course_id', $course_id)
                ->first();
                if (!is_null($courseId)) {
                    $quizFound = Quiz::query()->where('course_id', $course_id)->first();
                    if (is_null($quizFound)) {

                        $quiz = Quiz::query()->create([
                            'course_id' => $course_id,
                        ]);

                        $quiz_id = $quiz->id;

                        Quiz_user_pivot::query()->create([
                            'user_id' => Auth::id(),
                            'quiz_id' => $quiz_id,
                            'type' => 'teacher',
                            'mark' => 0,
                        ]);

                        $message = __('strings.Adding quiz successfully');
                        $code = 200;

                    } else {

                        $message = __('strings.There is quiz in this course already');
                        $code = 403;

                    }
                }else{

                    $message = __('strings.not authorized');
                    $code = 401;

                }
            } else {

                $message = __('strings.You do not have permission to add quiz');
                $code = 401;

            }
        }else{

            $message = __('strings.Course not found');
            $code = 404;

        }

        return [
            'quiz' => $quiz ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to delete quiz from their courses
    public function delete_quiz($quiz_id): array
    {
        $quiz = Quiz::query()->where('id', $quiz_id)->first();
        if (!is_null($quiz)) {
            if (Auth::user()->hasRole('admin')) {
                $quizUser = Quiz_user_pivot::query()->where('type','teacher')->where('quiz_id',$quiz_id)->first();
                if(!is_null($quizUser) && $quizUser->user_id == Auth::id()) {

                    $quiz->delete();
                    $message = __('strings.Deleting quiz successfully');
                    $code = 200;

                } else {
                    $quiz =[];
                    $message = __('strings.This quiz does not belongs to you');
                    $code = 403;

                }
            }else{
                $quiz =[];
                $message = __('strings.You do not have permission to delete quiz');
                $code = 401;

            }
        } else {
            $quiz =[];
            $message = __('strings.Not found in data');
            $code = 404;
        }

        return [
            'quiz' => $quiz ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    public function show_quizzes_with_question_and_answer() : array
    {
        $quizzes = Quiz::query()->get();
        if (!is_null($quizzes)) {
            if (Auth::user()->hasRole('admin')) {

                $quizzes = Quiz::query()->with('questions.answers')->get();
                $message = __('strings.Getting all quizzes in data successfully');
                $code = 200;

            } if (Auth::user()->hasRole('admin')) {
                $quizzesUsers = Quiz_user_pivot::query()->where('type','admin')->where('user_id',Auth::id())->get();
                if (!$quizzesUsers->isEmpty()){
                    $quizzes = [];
                    foreach ($quizzesUsers as $quizzesUser) {

                        $quizzes[]  = Quiz::query()->where('id',$quizzesUser->quiz_id)->with('questions.answers')->get();

                    }


                    $quizzes = $quizzes;
                    $message = __('strings.Getting all your quizzes successfully');
                    $code = 200;


                } else {
                    $quizzes =[];
                    $message = __('strings.You do not have any quiz');
                    $code = 404;
                }
            }else {
                $quizzesUsers = Quiz_user_pivot::query()->where('type','admin')->where('user_id',Auth::id())->get();
                if (!$quizzesUsers->isEmpty()){
                    $quizzes = [];
                    foreach ($quizzesUsers as $quizzesUser) {

                        $quizzes[]  = Quiz::query()->where('id',$quizzesUser->quiz_id)->with('questions.answers')->get();

                    }


                    $quizzes = $quizzes;
                    $message = __('strings.Getting all your quizzes successfully');
                    $code = 200;


                } else {
                    $quizzes =[];
                    $message = __('strings.You do not have any quiz');
                    $code = 404;
                }
            }


        }else{
            $quizzes = [];
            $message = __('strings.Not found any quiz');
            $code = 404;
        }

        return [
            'quiz' => $quizzes,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to go to the quiz in the end of course
    public function go_to_quiz($course_id) : array
    {
        $quiz = Quiz::query()->where('course_id',$course_id)->first();
        if (!is_null($quiz)){
            if (Auth::user()->hasRole('admin')){
                $quiz = Quiz::query()->where('course_id',$course_id)->with('questions.answers')->first();
                $message = 'Getting quiz successfully';
                $code = 200;
            }else if (Auth::user()->hasRole('admin')){
                $found = Quiz_user_pivot::query()
                    ->where('type','teacher')
                    ->where('quiz_id',$quiz->id)
                    ->where('user_id',Auth::id())
                    ->first();
                if(!is_null($found)){
                    $quiz = Quiz::query()->where('course_id',$course_id)->with('questions.answers')->first();
                    $message = __('strings.Getting quiz successfully');
                    $code = 200;
                }else{
                    $quiz =[];
                    $message = __('strings.This course belongs to another teacher');
                    $code = 403;
                }

            }else{
                $found = Course_user_pivot::query()
                    ->where('paid',1)
                    ->where('course_id',$course_id)
                    ->where('user_id',Auth::id())
                    ->first();
                if ($found){
                    //get count of video in this course
                    $Video = Course::query()->withCount('videos')->find($course_id);
                    $countVideo = $Video['videos_count'];

                    //get number of student video watched
                    $videosWatchedCount =User_video_pivot::query()
                        ->where('course_id',$course_id)
                        ->where('user_id',Auth::id())
                        ->where('watched',1)
                        ->count();

                    if ($videosWatchedCount > (0.80 * $countVideo)){
                        $quiz = Quiz::query()->where('course_id',$course_id)->with('questions.answers')->first();
                        $message = __('strings.Getting quiz successfully');
                        $code = 200;
                    }else{
                        $quiz =[];
                        $message = __('strings.You need to watch at least 80% from this course');
                        $code = 403;
                    }

                }else{
                    $quiz =[];
                    $message = __('strings.You need to buy this course first');
                    $code = 403;
                }

            }

        }else{
            $quiz =[];
            $message = __('strings.There is no quiz for this course currently');
            $code = 404;
        }
        return [
            'quiz' => $quiz,
            'message' => $message,
            'code' => $code,
        ];

    }

    //function to submit the answers from quiz and add mark
    public function submit_quiz($request, $quiz_id): array
    {
        // العثور على الكويز بواسطة معرف الكويز
        $quiz = Quiz::query()->where('id',$quiz_id)->first();
        if (!is_null($quiz)) {
            // التحقق مما إذا كان المستخدم له دور "طالب"
            if (Auth::user()->hasRole('admin')) {
                $found = Quiz_user_pivot::query()
                    ->where('type', 'admin')
                    ->where('user_id', Auth::id())
                    ->where('quiz_id', $quiz_id)
                    ->first();
                if (!$found) {
                    // الحصول على عدد الفيديوهات في هذا الكورس
                    $course = Course::query()->withCount('videos')->find($quiz->course_id);
                    $countVideo = $course->videos_count;

                    // الحصول على جميع الأسئلة وإجاباتها في هذا الاختبار
                    $questions = Question::query()->where('quiz_id', $quiz_id)->with('answers')->get();
                    $trueAnswerId = [];

                    // بناء مصفوفة تحتوي على ID الإجابات الصحيحة
                    foreach ($questions as $question) {
                        foreach ($question->answers as $answer) {
                            if ($answer->role == 1) { // role == 1 يعني الإجابة الصحيحة
                                $trueAnswerId[] = $answer->id;
                                break;
                            }
                        }
                    }

                    // مصفوفة الإجابات الخاصة بالطالب
                    $answersStudent = $request['answers'];
                    $mark = 0;

                    // المقارنة بين الإجابات الصحيحة وإجابات الطالب
                    foreach ($trueAnswerId as $index => $trueAnswer) {
                        if (isset($answersStudent[$index]) && $answersStudent[$index] == $trueAnswer) {
                            //   $mark += (1 / $countVideo);
                            $mark += 1;
                        }
                    }

                    // تسجيل نتيجة الطالب في جدول الكسر
                    Quiz_user_pivot::query()->create([
                        'user_id' => Auth::id(),
                        'quiz_id' => $quiz_id,
                        'type' => 'student',
                        'mark' => $mark
                    ]);

                    $userId = Auth::id();
                    $user = User::query()->where('id' , $userId)->first();
                    $userEmail = $user->email ;

                    $course = Course::query()->where('id' , $quiz->course_id)->first();
                    $discount_value = ($course->price) * 0.10 ;

                    // معالجة حالات خاصة بناءً على النتيجة
                    if ($mark == 10) {

                        User::query()->where('id',$userId)->update([
                            'wallet' => $user->wallet + $discount_value,
                        ]);

                        Transaction::create([
                            'user_id' => $userId,
                            'course_id' =>$course->id,
                            'type' => 'discount',
                            'amount' => $discount_value,
                        ]);

                        $data = [];
                        $data['message'] = __('strings.Excellent. You got a perfect mark on this test. You got coupons worth ten percent of the price of this course. These coupons have been added to your balance.');
                        $data['mark'] = $mark;
                        $data['name'] = $user->full_name;
                        $data['courseName'] = $course->name;
                        Mail::to($userEmail)->send(new certificate($data));
                    } else if ($mark == 9 || $mark == 8 ||$mark == 7) {
                        $data = [];
                        $data['message'] = __('strings.Very Good. You got a good mark on this test.');
                        $data['mark'] = $mark;
                        $data['name'] = $user->full_name;
                        $data['courseName'] = $course->name;
                        Mail::to($userEmail)->send(new certificate($data));
                    }else{
                        $data = [];
                        $data['message'] = __('strings.Sorry, you failed in this test');
                        $data['mark'] = $mark;
                        $data['name'] = $user->full_name;
                        $data['courseName'] = $course->name;
                        Mail::to($userEmail)->send(new certificate($data));
                    }

                    $data = [];
                    $data['mark'] = $mark . ' From 10';
                    $data['answers'] = $answersStudent;
                    $message = __('strings.Your mark');
                    $code = 200;
                } else {
                    $data = [];
                    $message = __('strings.You have already solved this quiz');
                    $code = 403;
                }
            } else {
                $data = [];
                $message = __('strings.This test is not for you');
                $code = 403;
            }
        } else {
            $data = [];
            $message = __('strings.This quiz was not found');
            $code = 404;
        }

        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

//answers -------------------------------------------------------------



    public function create_answer($request,$question_id): array
    {
        $question = Question::query()->where('id',$question_id)->with('quiz')->first();
        if(!is_null($question)) {
            if(Auth::user()->hasRole('admin')){
                $quiz_id = $question['quiz']->id;
                $quizId = Quiz_user_pivot::query()->where('type','admin')->where('quiz_id',$quiz_id)->first();
                if (!is_null($quizId) && $quizId->user_id == Auth::id()) {
                    $answerNumber = Answer::query()->where('question_id',$question_id)->count();
                    if ($answerNumber < 4 ){

                        $answer = Answer::query()->create([
                            'question_id' => $question_id,
                            'choice' => $request['choice'],
                            'role' => $request['role'] ?? 0,
                        ]);

                        $message = __('strings.Adding answer successfully');
                        $code = 200;


                    } else {

                        $message = __('strings.This question has already four answer');
                        $code = 403;

                    }
                }else{

                    $message = __('strings.question does not belongs to you to add answer on it');
                    $code = 401;

                }


            } else {

                $message = __('strings.You do not have permission to add answer');
                $code = 401;

            }
        } else {
            $message = __('strings.This question not found');
            $code = 404;
        }

        return [
            'answer' => $answer ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to delete answers from their questions
    public function delete_answer($answer_id): array
    {
        $answer = Answer::query()->where('id',$answer_id)->with('question')->first();
        if(!is_null($answer)) {
            $question_id = $answer['question']->id;
            $question = Question::query()->where('id',$question_id)->with('quiz')->first();
            $quiz_id = $question['quiz']->id;
            if(Auth::user()->hasRole('admin')){
                $userId = Quiz_user_pivot::query()->where('type','admin')->where('quiz_id',$quiz_id)->first();
                if (!is_null($userId) && $userId->user_id == Auth::id()) {

                    $answer->delete();
                    $message = __('strings.Deleting answer successfully');
                    $code = 200;

                }else{

                    $answer =[];
                    $message = __('strings.question does not belongs to you to delete answers');
                    $code = 403;

                }


            } else {

                $answer =[];
                $message = __('strings.You do not have permission to delete answer');
                $code = 401;

            }
        } else {
            $answer =[];
            $message = __('strings.This answer not found');
            $code = 404;

        }

        return [
            'answer' => $answer,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to update answers from their questions
    public function update_answer($request,$answer_id): array
    {
        $answer = Answer::query()->where('id',$answer_id)->with('question')->first();
        if(!is_null($answer)) {
            $question_id = $answer['question']->id;
            $question = Question::query()->where('id',$question_id)->with('quiz')->first();
            $quiz_id = $question['quiz']->id;
            if(Auth::user()->hasRole('admin')){
                $userId = Quiz_user_pivot::query()->where('type','admin')->where('quiz_id',$quiz_id)->first();
                if (!is_null($userId) && $userId->user_id == Auth::id()) {

                    $answer->update([
                        'choice' => $request['choice'] ??$answer['choice'],
                        'role' => $request['role'] ?? $answer['role'],
                    ]);
                    $message = __('strings.Updating answer successfully');
                    $code = 200;

                }else{

                    $answer =[];
                    $message = __('strings.question does not belongs to you to update answers');
                    $code = 403;

                }


            } else {

                $answer =[];
                $message = __('strings.You do not have permission to update answer');
                $code = 401;

            }
        } else {
            $answer =[];
            $message = __('strings.This answer not found');
            $code = 404;

        }

        return [
            'answer' => $answer,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to get answers belongs to the specific question
    public function show_answer($question_id) : array
    {
        $question = Question::query()->where('id',$question_id)->first();
        if (!is_null($question)) {
            $answer = Answer::query()->where('question_id', $question_id)->get();
            if (!$answer->isEmpty()) {
                if (Auth::user()->hasRole('admin')) {

                    if (Auth::user()->hasRole('admin')) {

                        $message = __('strings.Getting all answers in this question successfully');
                        $code = 200;

                    }
                    if (Auth::user()->hasRole('admin')) {
                        $quiz_id = $question->quiz_id;
                        $quiz = Quiz::query()->where('id',$quiz_id)->first();
                        $course_id = $quiz->course_id;
                        $found = Course_user_pivot::query()
                            ->where('paid',0)
                            ->where('course_id',$course_id)
                            ->where('user_id',Auth::id())
                            ->first();
                        if ($found) {
                            $message = __('strings.Getting all answers in this question successfully');
                            $code = 200;
                        } else {
                            $answer = [];
                            $message = __('strings.This question belongs to another teacher');
                            $code = 404;
                        }
                    }

                } else {
                    $answer = [];
                    $message = __('strings.You do not have any permission to show answers for this question');
                    $code = 401;

                }
            } else {
                $answer = [];
                $message = __('strings.Not found any answer in this question');
                $code = 404;
            }
        }else{
            $answer = [];
            $message = __('strings.This question not found');
            $code = 404;
        }

        return [
            'answer' => $answer,
            'message' => $message ?? [],
            'code' => $code ?? [],
        ];
    }


    //question -----------------------------------------------------------------


    public function create_question($request,$quiz_id): array
    {
        $quiz = Quiz::query()->where('id',$quiz_id)->first();
        if(!is_null($quiz)) {
            if(Auth::user()->hasRole('admin')){
                $quizId = Quiz_user_pivot::query()->where('type','admin')->where('quiz_id',$quiz_id)->first();
                if (!is_null($quizId) && $quizId->user_id == Auth::id()) {
                    $questionNumber = Question::query()->where('quiz_id', $quiz_id)->count();
                    if ($questionNumber < 10) {

                        $question = Question::query()->create([
                            'quiz_id' => $quiz_id,
                            'question' => $request['question'],
                        ]);

                        $message = __('strings.Adding question successfully');
                        $code = 200;


                    } else {

                        $message = __('strings.This quiz has already ten question you can not add more than ten');
                        $code = 403;

                    }
                }else{

                    $message = __('strings.Quiz does not belongs to you to add question on it');
                    $code = 401;

                }


            } else {

                $message = __('strings.You do not have permission to add question');
                $code = 401;

            }
        } else {
            $message = __('strings.This quiz not found');
            $code = 404;
        }

        return [
            'question' => $question ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to delete question from their quizzes
    public function delete_question($question_id): array
    {
        $question = Question::query()->where('id',$question_id)->with('quiz')->first();
        if(!is_null($question)) {
            $quiz_id = $question['quiz']->id;
            if (Auth::user()->hasRole('admin')) {
                $user = Quiz_user_pivot::query()->where('type','admin')->where('quiz_id',$quiz_id)->first();
                if(!is_null($user) && $user->user_id == Auth::id()) {

                    $question->delete();
                    $message = __('strings.Delete question successfully');
                    $code = 200;

                }else{
                    $question = [];
                    $message = __('strings.This question does not belongs to you');
                    $code = 403;

                }
            } else {
                $question = [];
                $message = __('strings.You do not have permission to add question');
                $code = 401;

            }

        } else {

            $question = [];
            $message = __('strings.This question not found');
            $code = 404;

        }

        return [
            'question' => $question ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to update question from their quizzes
    public function update_question($request,$question_id): array
    {
        $question = Question::query()->where('id',$question_id)->with('quiz')->first();
        if(!is_null($question)) {
            $quiz_id = $question['quiz']->id;
            $user = Quiz_user_pivot::query()->where('type','admin')->where('quiz_id',$quiz_id)->first();
            if (Auth::user()->hasRole('admin')) {
                if(!is_null($user)  && $user->user_id == Auth::id()) {
                    $question->update([
                        'question' => $request['question'] ?? $question->question,
                    ]);
                    $message = __('strings.Update question successfully');
                    $code = 200;

                }else{
                    $question = [];
                    $message = __('strings.This question belongs to another teacher');
                    $code = 403;

                }
            } else {
                $question = [];
                $message = __('strings.You do not have permission to update question');
                $code = 401;

            }

        } else {

            $question = [];
            $message = __('strings.This question not found');
            $code = 404;

        }

        return [
            'question' => $question,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to get question belongs to the specific quiz
    public function show_question($quiz_id) : array
    {
        $quiz = Quiz::query()->where('id',$quiz_id)->first();
        if (!is_null($quiz)) {
            $question = Question::query()->where('quiz_id', $quiz_id)->get();
            if ($question->isEmpty()) {
                if ( Auth::user()->hasRole('admin')) {

                    if (Auth::user()->hasRole('admin')) {

                        $message = __('strings.Getting all question in this quiz successfully');
                        $code = 200;

                    }
                    if (Auth::user()->hasRole('teacher')) {
                        $questionUsers = Quiz_user_pivot::query()->where('type', 'teacher')->where('user_id', Auth::id())->where('quiz_id', $quiz_id)->first();
                        if ($questionUsers) {

                            $message = __('strings.Getting all your quizzes successfully');
                            $code = 200;


                        } else {
                            $question = [];
                            $message = __('strings.You do not have any quiz');
                            $code = 404;
                        }
                    }

                } else {
                    $question = [];
                    $message = __('strings.You do not have any permission to show question for this quiz');
                    $code = 401;

                }
            } else {
                $question = [];
                $message = __('strings.Not found any question in this quiz');
                $code = 404;
            }
        }else{
            $question = [];
            $message = __('strings.This quiz not found');
            $code = 404;
        }

        return [
            'question' => $question,
            'message' => $message ?? [],
            'code' => $code ?? [],
        ];
    }

    //// comments and replies ---------------------------------------------------------


    public function get_comments($video_id){
        if (Auth::user()->hasRole('admin')) {
            $video = Video::query()->where('id', $video_id)->first();
            $comments = Comment::query()->where('video_id', $video_id)->with('user')->get();

            if (!is_null($video)) {
                if (!$comments->isEmpty()) {
                    $message = __('strings.Getting all comments successfully');
                    $code = 200;

                } else {

                    $comments = [];
                    $message = __('strings.There is no comments for this video');
                    $code = 404;

                }
            } else {
                $comments = [];
                $message = __('strings.There is no video in this id');
                $code = 404;
            }
        }else{
            $comments = [];
            $message = 'unauthorized';
            $code = 403;
        }
        return [
            'data' => $comments,
            'message' => $message,
            'code' => $code,
        ];
    }

    //getting all replies for special comment
    public function get_replies($comment_id)
    {
        if (Auth::user()->hasRole('admin')) {

        $comments = Comment::query()->where('id', $comment_id)
            ->with('ALL_replies.ALL_replies.ALL_replies.ALL_replies.ALL_replies')->get();
        $message = __('strings.Getting all comments successfully');
        $code = 200;

        }else{
            $comments = [];
            $message = 'unauthorized';
            $code = 403;
        }
        return [
            'data' => $comments,
            'message' => $message,
            'code' => $code,
        ];

    }

    //function to add comment for special video
    public function add_comment($request , $video_id){
        if (Auth::user()->hasRole('admin')) {

            $comments = Comment::query()->create([
            'user_id' => Auth::id(),
            'video_id' => $video_id,
            'comment' => $request['comment'],
        ]);
        $message = __('strings.Create comment successfully');
        $code = 200;
        }else{
            $comments = [];
            $message = 'unauthorized';
            $code = 403;
        }
        return [
            'data' => $comments,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to add reply for special comment
    public function add_reply($request , $comment_id){
        if (Auth::user()->hasRole('admin')) {

            $video_id = Comment::query()->where('id' , $comment_id)->select('video_id')->first();
        $reply = Comment::query()->create([
            'user_id' => Auth::id(),
            'video_id' => $video_id->video_id,
            'parent_id' => $comment_id,
            'comment' => $request['comment'],
        ]);
        $message = __('strings.Create reply successfully');
        $code = 200;
        }else{
            $comments = [];
            $message = 'unauthorized';
            $code = 403;
        }
        return [
            'data' => $reply,
            'message' => $message,
            'code' => $code,
        ];
    }

    //حذف التعليق بواسطة الادمن او الاستاذ تبع صاحب الكورس او الطالب اذا كان لألو
    public function delete_comment($comment_id){
        $video_id = Comment::query()->where('id' , $comment_id)->select('video_id')->first();
        $course_id = Video::query()->where('id' , $video_id)->select('course_id')->first();

        $tr = Course_user_pivot::query()
            ->where('paid' , 0 )
            ->where('user_id' , Auth::id())
            ->where('course_id' , $course_id)->first();

        $comment= Comment::query()->where('id',$comment_id)->first();

        if (Auth::user()->hasRole('admin')){
            $comment->delete();
            $message = 'Deleting comment successfully';
            $code = 200;
        }else if (Auth::user()->hasRole('teacher') &&  $tr){
            $comment->delete();
            $message = __('strings.Deleting comment successfully');
            $code = 200;
        }else if ( $comment->user_id == Auth::id()){

            $comment->delete();
            $message = __('strings.Deleting comment successfully');
            $code = 200;
        }else{

            $message = __('strings.You do not have permission to delete it');
            $code = 403;

        }
        return [
            'data' => [],
            'message' => $message,
            'code' => $code,
        ];
    }




    public function get_reports()
    {
        if (Auth::user()->hasRole('admin')) {
            $data = \App\Models\Notification::query()->where('data->reason', '!=', null)->get();
            $message = 'Getting all reports successfully';
            $code = 200;
        } else {
            $data = [];
            $message = __('strings.You do not have permission');
            $code = 403;
        }
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }











    public function search_course($request,$subject_id)
    {
        if (Auth::user()->hasRole('admin')){
        $subject = Subject::query()->find($subject_id);
        if (!is_null($subject)) {
            $query = $request['course_name'];
            $course = Course::query()
                ->where('subject_id', $subject_id)
                ->where('name', 'like', '%' . $query . '%')
                ->first();

            $message = __('strings.Getting course successfully') ;
            $code = 200;
        }else{
            $course = [];
            $message = __('strings.subject not found');
            $code = 404;

        }
        }else{
            $course = [];
            $message = 'unauthorized';
            $code = 403;
        }

        return [
            'data' => $course,
            'message' => $message,
            'code' => $code
        ];
    }

    //function to search about special video
    public function search_video($request,$course_id)
    {
        if (Auth::user()->hasRole('admin')){

            $course = Course::query()->find($course_id);
        if (!is_null($course)) {
            $query = $request->input('video_id');
            $video = Video::query()
                ->where('course_id', $course_id)
                ->where('id', 'like', $query . '%')
                ->first();

            $message = __('strings.Getting video successfully');
            $code = 200;

        }else{
            $video = [];
            $message = __('strings.course not found');
            $code = 404;
        }
        }else{
            $video = [];
            $message = 'unauthorized';
            $code = 403;
        }
        return [
            'data' => $video,
            'message' => $message,
            'code' => $code
        ];
    }

    public function all_transactions():array
    {
        $trans = Transaction::all();
        if (Auth::user()->hasRole('admin')){
        $message = 'getting all transactions';
        }else{
            $course = [];
            $message = 'unauthorized';
            $code = 403;
        }
            return [
            'trans' =>  $trans,
            'message' => $message,
        ];
    }
}
