<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Responses\Response;
use App\Services\Quiz\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    private QuizService $quizService;
    public function __construct(QuizService $quizService){
        $this->quizService = $quizService;
    }


    //api to add quiz by  teacher for his courses only
    public function create_quiz($course_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->quizService->create_quiz($course_id);
            return Response::Success($data['quiz'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to delete quiz from course by teacher from his courses only
    public function delete_quiz($quiz_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->quizService->delete_quiz($quiz_id);
            return Response::Success($data['quiz'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to get all quizzes in data to the admin or to a student from his courses
    public function show_quizzes() : jsonResponse
    {
        $data = [];
        try {
            $data = $this->quizService->show_quizzes();
            return Response::Success($data['quiz'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }


}
