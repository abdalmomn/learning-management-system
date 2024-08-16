<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Video\AddVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Http\Responses\Response;
use App\Models\Course;
use App\Models\User_video_pivot;
use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;
use Psy\Util\Json;
use Throwable;

class VideoController extends Controller
{
    public $videoService;
    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }


    public function create_video(AddVideoRequest $request, $course_id): JsonResponse
    {
        $video = [];
        try {
            if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')) {
                // Store the video in 'public/videos'
                $videoPath = $request->file('url')->store('videos', 'public');
                $videoUrl = Storage::url($videoPath);

                // Validate and store video data
                $validatedData = $request->validated();
                $validatedData['url'] = $videoUrl;

                // Fetch video duration using the service
                $validatedData['duration'] = $this->videoService->getVideoDuration($request->file('url'));

                // Add video to the database
                $video = $this->videoService->addVideos($validatedData, $course_id);

                return Response::Success($video['video'], $video['message']);
            } else {
                return response()->json(['message' => 'unauthorized'], 403);
            }
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }


    public function update_video(UpdateVideoRequest $request ,$course_id, $video_id):JsonResponse
    {
        $video = [];
        try {
            if (Auth::user()->hasRole('teacher')){
                $videoPath = $request->file('url')->store('videos', 'public');
                $videoUrl = Storage::url($videoPath);

                $validatedData = $request->validated();
                $validatedData['url'] = $videoPath;
                $validatedData['duration'] = $this->videoService->videoDuration;

                $video = $this->videoService->update_videos($validatedData,$course_id,$video_id);
                return Response::Success($video['video'],$video['message']);
            }else{
                return response()->json(['message' => 'unauthorized']);
            }
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$message);
        }
    }

    public function delete_video($course_id,$video_id):JsonResponse
    {
        $video = [];
        try {
            $video = $this->videoService->delete_video($course_id,$video_id);
            return Response::Success($video['video'] , $video['message']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$message);
        }
    }

    public function show_all_videos($course_id):JsonResponse
    {
        $video = [];
        try {
            $video = $this->videoService->show_all_videos($course_id);
            return Response::Success($video['video'] , $video['message']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$message);
        }
    }


    public function show_video($course_id, $video_id)
    {
        $video = [];
        try {
            $video = $this->videoService->show_video($course_id, $video_id);
            return Response::Success($video['video'] , $video['message']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$message);
        }
    }



    public function add_like($video_id):JsonResponse
    {
        $video = [];
        try {
            $video = $this->videoService->add_like($video_id);
            return Response::Success($video['video'] , $video['message']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$message);
        }
    }
    public function remove_like($video_id):JsonResponse
    {
        $video = [];
        try {
            $video = $this->videoService->remove_like($video_id);
            return Response::Success($video['video'] , $video['message']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$message);
        }
    }
    public function add_dislike($video_id):JsonResponse
    {
        $video = [];
        try {
            $video = $this->videoService->add_dislike($video_id);
            return Response::Success($video['video'] , $video['message']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$message);
        }
    }
    public function remove_dislike($video_id):JsonResponse
    {
        $video = [];
        try {
            $video = $this->videoService->remove_dislike($video_id);
            return Response::Success($video['video'] , $video['message']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$message);
        }
    }
}
