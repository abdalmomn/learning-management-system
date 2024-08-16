<?php
namespace App\actions;

use App\Models\Comment;
use App\Models\Course_user_pivot;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;

class CommentsAndRepliesAction
{
    //getting all comments for special video
    public function get_comments($video_id){
        $video = Video::query()->where('id' , $video_id)->first();
        $comments = Comment::query()->where('video_id' , $video_id)->with('user')->get();

     if (!is_null($video)) {
            if (!$comments->isEmpty()) {
                $message = __('strings.Getting all comments successfully');
                $code = 200;

            } else {

                $comments = [];
                $message = __('strings.There is no comments for this video');
                $code = 404;

            }
        }else{
            $comments = [];
            $message = __('strings.There is no video in this id');
            $code = 404;
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

        $comments = Comment::query()->where('id', $comment_id)
            ->with('ALL_replies.ALL_replies.ALL_replies.ALL_replies.ALL_replies')->get();
        $message = __('strings.Getting all comments successfully');
        $code = 200;


        return [
            'data' => $comments,
            'message' => $message,
            'code' => $code,
        ];

    }

    //function to add comment for special video
    public function add_comment($request , $video_id){
        $comments = Comment::query()->create([
            'user_id' => Auth::id(),
            'video_id' => $video_id,
            'comment' => $request['comment'],
        ]);
        $message = __('strings.Create comment successfully');
        $code = 200;

        return [
            'data' => $comments,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to add reply for special comment
    public function add_reply($request , $comment_id){
        $video_id = Comment::query()->where('id' , $comment_id)->select('video_id')->first();
        $reply = Comment::query()->create([
            'user_id' => Auth::id(),
            'video_id' => $video_id->video_id,
            'parent_id' => $comment_id,
            'comment' => $request['comment'],
        ]);
        $message = __('strings.Create reply successfully');
        $code = 200;

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


}

