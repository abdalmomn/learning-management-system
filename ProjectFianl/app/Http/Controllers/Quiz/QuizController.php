<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\SubmitAnswersRequest;
use App\Http\Responses\Response;
use App\Models\Question;
use App\Models\User_video_pivot;
use App\Services\Quiz\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

    //api to get all quizzes in data with questions and answers by admin or teacher ro student
    public function show_quizzes_with_question_and_answer() : jsonResponse
    {
        $data = [];
        try {
            $data = $this->quizService->show_quizzes_with_question_and_answer();
            return Response::Success($data['quiz'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to go to the quiz in the end of course
    public function go_to_quiz($course_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->quizService->go_to_quiz($course_id);
            return Response::Success($data['quiz'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to submit choices and getting mark
    public function submit_quiz(SubmitAnswersRequest $request,$quiz_id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->quizService->submit_quiz($request->validated(),$quiz_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    public function sh(){

        $questions = Question::query()->where('quiz_id',9)->with('answers')->get();
        $answersTrue =[];
        for ($j = 0 ; $j >9 ; $j++){
        for ($i=0;$i>4;$i++){
          if($questions[$j]->answers[$i]->role == 1){
              $answersTrue = $questions[$j]->answers[$i]->id;
             }
        }}
        return $answersTrue;
    }


}
