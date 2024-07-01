<?php

namespace App\Http\Controllers\Operation;

use App\actions\CommentsAndRepliesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\commentAndReplies\AddCommentRequest;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;

class CommentsAndRepliesController extends Controller
{
    private CommentsAndRepliesAction $commentsAndRepliesAction;

    public function __construct(CommentsAndRepliesAction $commentsAndRepliesAction){
        $this->commentsAndRepliesAction = $commentsAndRepliesAction;
    }

    //api for getting all comments from special video
    public function get_comments($video_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->commentsAndRepliesAction->get_comments($video_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to get all replies for special comment
    public function get_replies($comment_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->commentsAndRepliesAction->get_replies($comment_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to add comment for special video
    public function add_comment(AddCommentRequest $request , $video_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->commentsAndRepliesAction->add_comment($request , $video_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to add reply for special comment
    public function add_reply(AddCommentRequest $request , $comment_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->commentsAndRepliesAction->add_reply($request , $comment_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to add reply for special comment
    public function delete_comment($comment_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->commentsAndRepliesAction->delete_comment($comment_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }
}
