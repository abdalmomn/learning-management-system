<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use App\Http\Responses\Response;
use App\Services\Operation\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private SearchService $searchService;

    public function __construct(SearchService $searchService){
        $this->searchService = $searchService;
    }

    //api for searching about course in subject
      public function search_course(Request $request,$subject_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->searchService->search_course($request,$subject_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for searching about video in course
    public function search_video(Request $request,$course_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->searchService->search_video($request,$course_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }
}
