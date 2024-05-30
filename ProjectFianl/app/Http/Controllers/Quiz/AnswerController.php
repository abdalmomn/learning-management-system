<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\CreateAnswerRequest;
use App\Http\Requests\Quiz\CreateQuestionRequest;
use App\Http\Requests\Quiz\UpdateAnswerRequest;
use App\Http\Requests\Quiz\UpdateQuestionRequest;
use App\Http\Responses\Response;
use App\Services\Quiz\AnswerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    private AnswerService $answerService;
    public function __construct(AnswerService $answerService){
        $this->answerService = $answerService;
    }

    //api to add answer to question by teacher for his questions only
    public function create_answer(CreateAnswerRequest $request,$question_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->answerService->create_answer($request->validated(),$question_id);
            return Response::Success($data['answer'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to delete answer from question by teacher for his questions only
    public function delete_answer($answer_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->answerService->delete_answer($answer_id);
            return Response::Success($data['answer'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to update answer from question by teacher for his questions only
    public function update_answer(UpdateAnswerRequest $request,$answer_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->answerService->update_answer($request->validated(),$answer_id);
            return Response::Success($data['answer'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }
}
