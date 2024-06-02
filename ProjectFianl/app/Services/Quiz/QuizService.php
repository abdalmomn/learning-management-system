<?php

namespace App\Services\Quiz;

use App\Models\Course;
use App\Models\Course_user_pivot;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Quiz_user_pivot;
use App\Models\User_video_pivot;
use Illuminate\Support\Facades\Auth;

class QuizService
{


    //function provides teachers to add quiz to their courses
    public function create_quiz($course_id): array
    {
        $course = Course::query()->where('id', $course_id)->first();
        if (!is_null($course)) {
            if (Auth::user()->hasRole('teacher')) {
                $courseId = Course_user_pivot::query()->where('paid',0)->where('user_id',Auth::id())->where('course_id', $course_id)->first();
                if (!is_null($courseId)) {
                    $quizFound = Quiz::query()->where('course_id', $course_id)->first();
                    if (is_null($quizFound)) {

                                $quiz = Quiz::query()->create([
                                    'course_id' => $course_id,
                                ]);

                                $quiz_id = $quiz->id;

                                Quiz_user_pivot::query()->create([
                                    'user_id' => Auth::id(),
                                    'quiz_id' => $quiz_id,
                                    'type' => 'teacher',
                                    'mark' => 0,
                                ]);

                                $message = 'Adding quiz successfully';
                                $code = 200;

                    } else {

                        $message = 'There is quiz in this course already';
                        $code = 403;

                    }
                }else{

                    $message = 'Course does not belongs to you to add quiz on it';
                    $code = 401;

                }
            } else {

                $message = 'You do not have permission to add quiz';
                $code = 401;

            }
        }else{

            $message = 'Course not found';
            $code = 404;

        }

        return [
            'quiz' => $quiz ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to delete quiz from their courses
    public function delete_quiz($quiz_id): array
    {
        $quiz = Quiz::query()->where('id', $quiz_id)->first();
        if (!is_null($quiz)) {
            if (Auth::user()->hasRole('teacher')) {
                $quizUser = Quiz_user_pivot::query()->where('type','teacher')->where('quiz_id',$quiz_id)->first();
                if(!is_null($quizUser) && $quizUser->user_id == Auth::id()) {

                        $quiz->delete();
                        $message = 'Deleting quiz successfully';
                        $code = 200;

                } else {
                    $quiz =[];
                    $message = 'This quiz does not belongs to you';
                    $code = 403;

                }
            }else{
                $quiz =[];
                $message = 'You do not have permission to delete quiz';
                 $code = 401;

                }
        } else {
            $quiz =[];
            $message = 'Not found in data';
            $code = 404;
        }

        return [
            'quiz' => $quiz ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to get all quizzes in data to the admin or to a student from his courses
    public function show_quizzes() : array
    {
        $quizzes = Quiz::query()->get();
        if (!is_null($quizzes)) {
            if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')){

                if (Auth::user()->hasRole('admin')) {

                    $quizzes = Quiz::query()->get();
                    $message = 'Getting all quizzes in data successfully';
                    $code = 200;

                } if (Auth::user()->hasRole('teacher')) {
                    $quizzesUsers = Quiz_user_pivot::query()->where('type','teacher')->where('user_id',Auth::id())->get();
                    if (!$quizzesUsers->isEmpty()){

                        $quizzes = $quizzesUsers;
                        $message = 'Getting all your quizzes successfully';
                        $code = 200;


                    } else {
                        $quizzes =[];
                        $message = 'You do not have any quiz';
                        $code = 404;
                    }
                }

        }else {
            $quizzes =[];
                $message = 'You do not have any permission to show all quizzes';
            $code = 401;

        }
        }else{
            $quizzes = [];
            $message = 'Not found any quiz';
            $code = 404;
        }

        return [
            'quiz' => $quizzes,
            'message' => $message ?? [],
            'code' => $code ?? [],
        ];
    }

<<<<<<< HEAD
=======
    //function to get all quizzes in data with questions and answers by admin or teacher ro student
    public function show_quizzes_with_question_and_answer() : array
    {
        $quizzes = Quiz::query()->get();
        if (!is_null($quizzes)) {
                if (Auth::user()->hasRole('admin')) {

                    $quizzes = Quiz::query()->with('questions.answers')->get();
                    $message = 'Getting all quizzes in data successfully';
                    $code = 200;

                } if (Auth::user()->hasRole('teacher')) {
                    $quizzesUsers = Quiz_user_pivot::query()->where('type','teacher')->where('user_id',Auth::id())->get();
                    if (!$quizzesUsers->isEmpty()){
                        $quizzes = [];
                        foreach ($quizzesUsers as $quizzesUser) {

                            $quizzes[]  = Quiz::query()->where('id',$quizzesUser->quiz_id)->with('questions.answers')->get();

                        }


                        $quizzes = $quizzes;
                        $message = 'Getting all your quizzes successfully';
                        $code = 200;


                    } else {
                        $quizzes =[];
                        $message = 'You do not have any quiz';
                        $code = 404;
                    }
                }else {
                $quizzesUsers = Quiz_user_pivot::query()->where('type','student')->where('user_id',Auth::id())->get();
                if (!$quizzesUsers->isEmpty()){
                    $quizzes = [];
                    foreach ($quizzesUsers as $quizzesUser) {

                        $quizzes[]  = Quiz::query()->where('id',$quizzesUser->quiz_id)->with('questions.answers')->get();

                    }


                    $quizzes = $quizzes;
                    $message = 'Getting all your quizzes successfully';
                    $code = 200;


                } else {
                    $quizzes =[];
                    $message = 'You do not have any quiz';
                    $code = 404;
                }
            }


        }else{
            $quizzes = [];
            $message = 'Not found any quiz';
            $code = 404;
        }

        return [
            'quiz' => $quizzes,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to go to the quiz in the end of course
    public function go_to_quiz($course_id) : array
    {
     $quiz = Quiz::query()->where('course_id',$course_id)->first();
     if (!is_null($quiz)){
         if (Auth::user()->hasRole('admin')){
             $quiz = Quiz::query()->where('course_id',$course_id)->with('questions.answers')->first();
             $message = 'Getting quiz successfully';
             $code = 200;
         }else if (Auth::user()->hasRole('teacher')){
             $found = Quiz_user_pivot::query()
                 ->where('type','teacher')
                 ->where('quiz_id',$quiz->id)
                 ->where('user_id',Auth::id())
                 ->first();
             if(!is_null($found)){
                 $quiz = Quiz::query()->where('course_id',$course_id)->with('questions.answers')->first();
                 $message = 'Getting quiz successfully';
                 $code = 200;
             }else{
                 $quiz =[];
                 $message = 'This course belongs to another teacher';
                 $code = 403;
             }

         }else{
             $found = Course_user_pivot::query()
                 ->where('paid',1)
                 ->where('course_id',$course_id)
                 ->where('user_id',Auth::id())
                 ->first();
             if ($found){
                 //get count of video in this course
                 $Video = Course::query()->withCount('videos')->find($course_id);
                 $countVideo = $Video['videos_count'];

                 //get number of student video watched
                 $videosWatchedCount =User_video_pivot::query()
                     ->where('course_id',$course_id)
                     ->where('user_id',Auth::id())
                     ->where('watched',1)
                     ->count();

                 if ($videosWatchedCount > (0.80 * $countVideo)){
                     $quiz = Quiz::query()->where('course_id',$course_id)->with('questions.answers')->first();
                     $message = 'Getting quiz successfully';
                     $code = 200;
                 }else{
                     $quiz =[];
                     $message = 'You need to watch at least 80% from this course';
                     $code = 403;
                 }

             }else{
                 $quiz =[];
                 $message = 'You need to buy this course first';
                 $code = 403;
             }

         }

     }else{
         $quiz =[];
         $message = 'There is no quiz for this course currently';
         $code = 404;
     }
        return [
            'quiz' => $quiz,
            'message' => $message,
            'code' => $code,
        ];

     }

     //function to submit the answers from quiz and add mark
    public function submit_quiz($request,$quiz_id) : array
    {
        $quiz = Quiz::query()->where('id',$quiz_id)->first();
        if (!is_null($quiz)){
        if (Auth::user()->hasRole('student')){
            $found = Quiz_user_pivot::query()
                ->where('type','student')
                ->where('user_id',Auth::id())
                ->where('quiz_id',$quiz_id)->first();
            if (!$found){
                //get count of video in this course
                $Video = Course::query()->withCount('videos')->find($quiz->course_id);
                $countVideo = $Video['videos_count'];

                //get id for all quiz answers and insert them to $answersTrue array
                $questions = Question::query()->where('quiz_id',$quiz_id)->with('answers')->get();
                $answersTrue =[];
                for ($j = 0 ; $j >9 ; $j++){
                    for ($i = 0 ;$i > 4;$i++){
                        if($questions[$j]->answers[$i]->role == 1){
                            $answersTrue = $questions[$j]->answers[$i]->id;
                            $i = 4;
                        }
                    }}

                //get array from student contain id the answers
                $answersStudent = [];
                $answers = $request['answers'];
                $mark = 0 ;
                //compare between two arrays
                for ($i = 0 ; $i > 9 ; $i++){
                    if ($answers[$i] == $answersTrue[$i]){
                        $mark = $mark + (1/$countVideo);
                    }
                }

                Quiz_user_pivot::query()->create([
                    'user_id' => Auth::id(),
                    'quiz_id' => $quiz_id,
                    'type' => 'student',
                    'mark' => $mark
                ]);
             //   if ($mark)

                $answer = $mark;
                $message = 'Your mark';
                $code = 200;

            }else{
                $answer =[];
                $message = 'Your already solve this quiz';
                $code = 403;
            }
        }else{
            $answer =[];
            $message = 'This test not for you';
            $code = 403;
        }
        }else{
            $answer =[];
            $message = 'This quiz Not found ';
            $code = 403;
        }

            return [
                'answer' => $answer,
                'message' => $message,
                'code' => $code,
            ];
    }

>>>>>>> origin/main



}
