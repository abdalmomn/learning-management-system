<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Video\AddVideoRequest;
use App\Http\Responses\Response;
use App\Models\Course;
use App\Services\VideoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;

class VideoController extends Controller
{
    public $videoService;
    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }


    public function create_video(AddVideoRequest $request, $course_id)
    {
        $video = [];
        try {
            // Get duration for video (from a package)
            $track = new GetId3(request()->file('url'));
            $track = GetId3::fromUploadedFile(request()->file('url'));

            // Get playtime in seconds
            $playtime_seconds = $track->getPlaytimeSeconds();

            // Convert playtime to H:M:S format
            $videoDuration = secondsToHms($playtime_seconds);

            if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')) {
                $videoPath = $request->file('url')->store('videos', 'public');
                $videoUrl = Storage::url($videoPath);

                $validatedData = $request->validated();
                $validatedData['url'] = $videoUrl;
                $validatedData['duration'] = $videoDuration;

                $video = $this->videoService->addVideos($validatedData, $course_id);

                // Update course total duration
                $course = Course::find($course_id);
                $courseTotalDurationSeconds = hmsToSeconds($course->hour);
                $courseTotalDurationSeconds += $playtime_seconds;
                $course->hour = secondsToHms($courseTotalDurationSeconds);
                $course->save();

                return Response::Success($video['video'], $video['message']);
            } else {
                return ['message' => 'unauthorized'];
            }
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }
}
