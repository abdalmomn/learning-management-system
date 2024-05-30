<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\CreateQuestionRequest;
use App\Http\Requests\Quiz\UpdateQuestionRequest;
use App\Http\Responses\Response;
use App\Models\Quiz;
use App\Models\Quiz_user_pivot;
use App\Services\Quiz\QuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    private QuestionService $questionService;

    public function __construct(QuestionService $questionService){
        $this->questionService = $questionService;
    }

    //api to add question to quiz by teacher for his quizzes only
    public function create_question(CreateQuestionRequest $request,$quiz_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->questionService->create_question($request->validated(),$quiz_id);
            return Response::Success($data['question'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to delete question from  quiz by teacher from his questions only
    public function delete_question($question_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->questionService->delete_question($question_id);
            return Response::Success($data['question'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to update question from quiz by teacher from his questions only
    public function update_question(UpdateQuestionRequest $request,$question_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->questionService->update_question($request->validated(),$question_id);
            return Response::Success($data['question'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

//    //api to get question for special quiz
//    public function show_question() : jsonResponse
//    {
//        $data = [];
//        try {
//            $data = $this->quizService->show_quizzes();
//            return Response::Success($data['quiz'],$data['message'],$data['code']);
//
//        }catch (\Throwable $th){
//            $message=$th->getMessage();
//            return Response::Error($data,$message);
//
//        }
//    }






}
