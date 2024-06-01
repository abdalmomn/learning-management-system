<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\CreateAnswerRequest;
use App\Http\Requests\Quiz\CreateQuestionRequest;
use App\Http\Requests\Quiz\UpdateAnswerRequest;
use App\Http\Requests\Quiz\UpdateQuestionRequest;
use App\Http\Responses\Response;
use App\Models\Course_user_pivot;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\Quiz\AnswerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    //api to get answers for special question
    public function show_answer($question_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->answerService->show_answer($question_id);
            return Response::Success($data['answer'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    public function sh(){
        $question = Question::query()->where('id',4)->first();
        $quiz_id = $question->quiz_id;
        $quiz = Quiz::query()->where('id',$quiz_id)->first();
        $course_id = $quiz->course_id;
        $found = Course_user_pivot::query()->where('paid',0)
            ->where('course_id',$course_id)
            ->where('user_id',20)
            ->first();
        return $found;

    }
}
