<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Video\AddVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Http\Responses\Response;
use App\Models\Course;
use App\Services\VideoService;
use Illuminate\Http\JsonResponse;
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


    public function create_video(AddVideoRequest $request, $course_id):JsonResponse
    {
        $video = [];
        try {
            if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')) {
                $videoPath = $request->file('url')->store('videos', 'public');
                $videoUrl = Storage::url($videoPath);

                $validatedData = $request->validated();
                $validatedData['url'] = $videoUrl;
                $validatedData['duration'] = $this->videoService->videoDuration;

                $video = $this->videoService->addVideos($validatedData, $course_id);
                return Response::Success($video['video'], $video['message']);
            } else {
                return response()->json(['message' => 'unauthorized']);
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

    public function show_video($course_id,$video_id):JsonResponse
    {
        $video = [];
        try {
            $video = $this->videoService->show_video($course_id , $video_id);
            return Response::Success($video['video'] , $video['message']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$th);
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
