<?php
namespace  App\Services;

use App\Models\Course;
use App\Models\User_video_pivot;
use App\Models\Video;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;

class VideoService
{
    public function addVideos($request, $course_id): array
    {
        $video = Video::create([
            'course_id' => $course_id,
            'title' => $request['title'],
            'url' => $request['url'],
            'duration' => $request['duration'],
        ]);
        $user_video = User_video_pivot::create([
            'user_id' => Auth::id(),
            'video_id' => $video->id,
        ]);
        $message = 'video added successfully';
        return ['video' => $video, 'user_video' => $user_video, 'message' => $message];


    }

}
