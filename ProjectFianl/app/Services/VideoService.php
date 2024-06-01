<?php
namespace  App\Services;

use App\Models\Course;
use App\Models\User_video_pivot;
use App\Models\Video;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;

class VideoService
{
    public $videoDuration;
    public function addVideos($request, $course_id): array
    {
        if (Auth::user()->hasRole('teacher')) {
            // Get duration for video (from a package)
            $track = new GetId3(request()->file('url'));
            $track = GetId3::fromUploadedFile(request()->file('url'));

            // Get playtime in seconds
            $playtime_seconds = $track->getPlaytimeSeconds();

            // Convert playtime to H:M:S format
            $this->videoDuration = secondsToHms($playtime_seconds);

            //create a new video
            $video = Video::create([
                'course_id' => $course_id,
                'title' => $request['title'],
                'url' => $request['url'],
                'duration' => $this->videoDuration,
            ]);
            //merge video with teacher
            $user_video = User_video_pivot::create([
                'user_id' => Auth::id(),
                'video_id' => $video->id,
            ]);
            // Update course total duration
            $course = Course::find($course_id);
            $courseTotalDurationSeconds = hmsToSeconds($course->hour);
            $courseTotalDurationSeconds += $playtime_seconds;
            $course->hour = secondsToHms($courseTotalDurationSeconds);
            $course->save();
        }else{
            $message = 'unauthorized';
            $code = 403;
        }
        $message = 'video added successfully';
        return ['video' => $video, 'user_video' => $user_video, 'message' => $message];
    }


    public function update_videos($request,$course_id,$video_id):array
    {
        $videos = Video::query()
            ->join('courses' , 'courses.id' , '=' , 'videos.course_id')
            ->select('courses.id as course_id' , 'videos.id as video_id')
            ->first();
        $courseId = $videos->course_id;
        $videoId = $videos->video_id;
        if ($course_id == $courseId && $video_id == $videoId){
        $track = new GetId3(request()->file('url'));
        $track = GetId3::fromUploadedFile(request()->file('url'));

        // Get playtime in seconds
        $playtime_seconds = $track->getPlaytimeSeconds();

        // Convert playtime to H:M:S format
        //this duration for the video I will send it in the request
        $this->videoDuration = secondsToHms($playtime_seconds);

        $video = Video::query()->find($video_id);
        $course = Course::query()->find($course_id);
        if($video && $course){
            $pivot = User_video_pivot::query()
                ->where('video_id' , $video_id)->first();
            if (Auth::user()->hasRole('teacher') && $pivot->user_id == Auth::id()){
                Video::query()->where('id' , $video_id)->update([
                    'title' => $request['title'] ??  $video['title'],
                    'description' => $request['description'] ??  $video['description'],
                    'url' => $request['url'] ??  $video['url'],
                    'duration' => $this->videoDuration ?? $video['duration'],
                ]);
                $course = Course::find($course_id);
                $courseTotalDurationSeconds = hmsToSeconds($course->hour);
                $oldDurationSeconds = hmsToSeconds($video['duration']);
                $newDurationSeconds = hmsToSeconds($this->videoDuration);

                if($newDurationSeconds != $oldDurationSeconds){
                    $courseTotalDurationSeconds -= $oldDurationSeconds;
                    $courseTotalDurationSeconds += $newDurationSeconds;
                }
                $course->hour = secondsToHms($courseTotalDurationSeconds);
                $course->save();

                $video = Video::query()->find($video_id);
                $message = 'video has been updated successfully!';
                $code = 200;
            }else{
                $message = 'unauthorized';
                $code = 403;
            }
        }else{
            $video = [];
            $message = 'video or course is not found';
            $code = 404;
        }
        }else{
            $video = [];
            $message = 'wrong video or course';
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

                $message = 'deleted successfully';
                $code = 200;
            }else{
                $video = [];
                $message = 'unauthorized';
                $code = 403;
            }
        }else{
            $video = [];
            $message = 'wrong video or course';
            $code = 403;
        }}else{
            $video = [];
            $message = 'video not found';
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
                $message = 'show all videos';
            }else{
                $video = [];
                $message = 'there are no videos at the moment';
            }
        }else{
            $video = [];
            $message = 'course not found';
            $code = 404;
        }
        return [
          'video' => $video,
          'message' => $message,
        ];
    }

    public function show_video($course_id,$video_id):array
    {
        $course = Course::find($course_id);
        if ($course){
            $video = Video::where('course_id' , $course_id)->find($video_id);
            if ($video){
                $video = Video::query()
                    ->where('id' , $video_id)
                    ->where('course_id' , $course_id)
                    ->first();
                $video = Video::where('course_id' , $course_id)->find($video_id);
                $video->view += 1;
                $video->save();

                $message = 'video information';
            }else{
                $video = [];
                $message = 'video not found';
            }
        }else{
            $video = [];
            $message = 'course not found';
        }
        return  [
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
            $message = 'liked successfully';
        }else{
            $video = [];
            $message = 'video not found';
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
            $message = 'liked successfully';
        }else{
            $video = [];
            $message = 'video not found';
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
            $message = 'liked successfully';
        }else{
            $video = [];
            $message = 'video not found';
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
            $message = 'liked successfully';
        }else{
            $video = [];
            $message = 'video not found';
        }
        return [
            'video' => $video,
            'message' => $message,
        ];
    }
}

