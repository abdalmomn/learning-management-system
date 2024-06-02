<?php

namespace App\Services\Quiz;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Quiz_user_pivot;
use Illuminate\Support\Facades\Auth;

class QuestionService
{

    //function provides teachers to add question to their quizzes
    public function create_question($request,$quiz_id): array
    {
        $quiz = Quiz::query()->where('id',$quiz_id)->first();
        if(!is_null($quiz)) {
            if(Auth::user()->hasRole('teacher')){
                $quizId = Quiz_user_pivot::query()->where('type','teacher')->where('quiz_id',$quiz_id)->first();
                if (!is_null($quizId) && $quizId->user_id == Auth::id()) {
                    $questionNumber = Question::query()->where('quiz_id', $quiz_id)->count();
                    if ($questionNumber < 10) {

                            $question = Question::query()->create([
                                'quiz_id' => $quiz_id,
                                'question' => $request['question'],
                            ]);

                            $message = 'Adding question successfully';
                            $code = 200;


                    } else {

                        $message = 'This quiz has already ten question you can not add more than ten';
                        $code = 403;

                    }
                }else{

                    $message = 'Quiz does not belongs to you to add question on it';
                    $code = 401;

                }


            } else {

                $message = 'You do not have permission to add question';
                $code = 401;

            }
        } else {
            $message = 'This quiz not found';
            $code = 404;
        }

        return [
            'question' => $question ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to delete question from their quizzes
    public function delete_question($question_id): array
    {
        $question = Question::query()->where('id',$question_id)->with('quiz')->first();
        if(!is_null($question)) {
            $quiz_id = $question['quiz']->id;
            if (Auth::user()->hasRole('teacher')) {
                $user = Quiz_user_pivot::query()->where('type','teacher')->where('quiz_id',$quiz_id)->first();
                if(!is_null($user) && $user->user_id == Auth::id()) {

                        $question->delete();
                        $message = 'Delete question successfully';
                        $code = 200;

                }else{
                    $question = [];
                    $message = 'This question does not belongs to you';
                    $code = 403;

                }
                } else {
                    $question = [];
                    $message = 'You do not have permission to add question';
                    $code = 401;

                }

        } else {

            $question = [];
            $message = 'This question not found';
            $code = 404;

        }

        return [
            'question' => $question ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to update question from their quizzes
    public function update_question($request,$question_id): array
    {
        $question = Question::query()->where('id',$question_id)->with('quiz')->first();
        if(!is_null($question)) {
            $quiz_id = $question['quiz']->id;
            $user = Quiz_user_pivot::query()->where('type','teacher')->where('quiz_id',$quiz_id)->first();
                if (Auth::user()->hasRole('teacher')) {
                    if(!is_null($user)  && $user->user_id == Auth::id()) {
                             $question->update([
                            'question' => $request['question'] ?? $question->question,
                        ]);
                        $message = 'Update question successfully';
                        $code = 200;

                    }else{
                        $question = [];
                        $message = 'This question belongs to another teacher';
                        $code = 403;

                    }
            } else {
                $question = [];
                $message = 'You do not have permission to update question';
                $code = 401;

            }

        } else {

            $question = [];
            $message = 'This question not found';
            $code = 404;

        }

        return [
            'question' => $question,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to get question belongs to the specific quiz
//    public function show_question($quiz_id) : array
//    {
//        $question = Question::query()->where('quiz_id',$quiz_id)->get();
//        if (!is_null($question)) {
//            if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')){
//
//                if (Auth::user()->hasRole('admin')) {
//
//                    $question = Question::query()->where('quiz_id',$quiz_id)->get();
//                    $message = 'Getting all question in this quiz successfully';
//                    $code = 200;
//
//                } if (Auth::user()->hasRole('teacher')) {
//                    $question = Question::query()->where('quiz_id',$quiz_id)->get();
//                    $questionUsers = Quiz_user_pivot::query()->where('type','teacher')->where('quiz+id',$quiz_id)->first();
//                    if (!$questionUsers->isEmpty()){
//
//                        $quizzes = $quizzesUsers;
//                        $message = 'Getting all your quizzes successfully';
//                        $code = 200;
//
//
//                    } else {
//                        $quizzes =[];
//                        $message = 'You do not have any quiz';
//                        $code = 404;
//                    }
//                }
//
//            }else {
//                $quizzes =[];
//                $message = 'You do not have any permission to show all quizzes';
//                $code = 401;
//
//            }
//        }else{
//            $question = [];
//            $message = 'Not found any question in this quiz';
//            $code = 404;
//        }
//
//        return [
//            'question' => $question,
//            'message' => $message ?? [],
//            'code' => $code ?? [],
//        ];
//    }


  }
