<?php
namespace  App\Services;

use App\Models\Course;
use App\Models\User_video_pivot;
use App\Models\Video;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;
use FFMpeg\Coordinate\TimeCode;

class VideoService
{
    public $videoDuration;
    public function getVideoDuration($videoFile): string
    {
        // Move the file to a permanent storage location
        $videoPath = $videoFile->storeAs('videos', $videoFile->getClientOriginalName(), 'public');


        $fullVideoPath = Storage::disk('public')->path($videoPath);

        // Get the full path of the stored file

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



    public function addVideos($request, $course_id): array
    {
        if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')) {
            // Create the video record
            $video = Video::create([
                'course_id' => $course_id,
                'title' => $request['title'],
                'url' => $request['url'],
                'duration' => $request['duration'],
            ]);
            // Merge video with teacher
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

                if (Auth::user()->hasRole('teacher') && $pivot->user_id == Auth::id()) {

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
            if ($pivot
                && Auth::user()->hasRole('teacher')
                && $pivot->user_id == Auth::id()
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

    //show all videos for a specific course
    public function show_all_videos($course_id):array
    {
        $course = Course::find($course_id);
        if ($course){
            $video = Video::query()
                ->where('course_id' , $course_id)->get();
            if ($video){
                $message = __('strings.show all videos');
            }else{
                $video = [];
                $message = __('strings.there are no videos at the moment');
            }
        }else{
            $video = [];
            $message = __('strings.course not found');
            $code = 404;
        }
        return [
          'video' => $video,
          'message' => $message,
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
        return [
            'video' => $video,
            'message' => $message,
        ];
    }


    public function add_like($video_id):array
    {
        $video = Video::query()
            ->where('id' , $video_id)
            ->first();
        if ($video) {
            $video->like += 1;
            $video->save();
            $message = __('strings.liked successfully');
        }else{
            $video = [];
            $message = __('strings.video not found');
        }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }
    public function remove_like($video_id):array
    {
        $video = Video::query()
            ->where('id' , $video_id)
            ->first();
        if ($video) {
            $video->like -= 1;
            $video->save();
            $message = __('strings.like remove successfully');
        }else{
            $video = [];
            $message = __('strings.video not found');
        }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }
    public function add_dislike($video_id):array
    {
        $video = Video::query()
            ->where('id' , $video_id)
            ->first();
        if ($video) {
            $video->dislike += 1;
            $video->save();
            $message = __('strings.disliked successfully');
        }else{
            $video = [];
            $message = __('strings.video not found');
        }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }
    public function remove_dislike($video_id):array
    {
        $video = Video::query()
            ->where('id' , $video_id)
            ->first();
        if ($video) {
            $video->dislike -= 1;
            $video->save();
            $message = __('strings.remove dislike successfully');
        }else{
            $video = [];
            $message = __('strings.video not found');
        }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }
}

