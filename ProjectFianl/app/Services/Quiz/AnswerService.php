<?php

namespace App\Services\Quiz;

use App\Models\Answer;
use App\Models\Course_user_pivot;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Quiz_user_pivot;
use Illuminate\Support\Facades\Auth;

class AnswerService
{

    //function provides teachers to add answers to their questions
    public function create_answer($request,$question_id): array
    {
        $question = Question::query()->where('id',$question_id)->with('quiz')->first();
        if(!is_null($question)) {
            if(Auth::user()->hasRole('teacher')){
                $quiz_id = $question['quiz']->id;
                $quizId = Quiz_user_pivot::query()->where('type','teacher')->where('quiz_id',$quiz_id)->first();
                if (!is_null($quizId) && $quizId->user_id == Auth::id()) {
                    $answerNumber = Answer::query()->where('question_id',$question_id)->count();
                    if ($answerNumber < 4 ){

                        $answer = Answer::query()->create([
                            'question_id' => $question_id,
                            'choice' => $request['choice'],
                            'role' => $request['role'] ?? 0,
                        ]);

                        $message = __('strings.Adding answer successfully');
                        $code = 200;


                    } else {

                        $message = __('strings.This question has already four answer');
                        $code = 403;

                    }
                }else{

                    $message = __('strings.question does not belongs to you to add answer on it');
                    $code = 401;

                }


            } else {

                $message = __('strings.You do not have permission to add answer');
                $code = 401;

            }
        } else {
            $message = __('strings.This question not found');
            $code = 404;
        }

        return [
            'answer' => $answer ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to delete answers from their questions
    public function delete_answer($answer_id): array
    {
        $answer = Answer::query()->where('id',$answer_id)->with('question')->first();
        if(!is_null($answer)) {
            $question_id = $answer['question']->id;
            $question = Question::query()->where('id',$question_id)->with('quiz')->first();
            $quiz_id = $question['quiz']->id;
            if(Auth::user()->hasRole('teacher')){
                $userId = Quiz_user_pivot::query()->where('type','teacher')->where('quiz_id',$quiz_id)->first();
                if (!is_null($userId) && $userId->user_id == Auth::id()) {

                        $answer->delete();
                        $message = __('strings.Deleting answer successfully');
                        $code = 200;

                }else{

                    $answer =[];
                    $message = __('strings.question does not belongs to you to delete answers');
                    $code = 403;

                }


            } else {

                $answer =[];
                $message = __('strings.You do not have permission to delete answer');
                $code = 401;

            }
        } else {
            $answer =[];
            $message = __('strings.This answer not found');
            $code = 404;

        }

        return [
            'answer' => $answer,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function provides teachers to update answers from their questions
    public function update_answer($request,$answer_id): array
    {
        $answer = Answer::query()->where('id',$answer_id)->with('question')->first();
        if(!is_null($answer)) {
            $question_id = $answer['question']->id;
            $question = Question::query()->where('id',$question_id)->with('quiz')->first();
            $quiz_id = $question['quiz']->id;
            if(Auth::user()->hasRole('teacher')){
                $userId = Quiz_user_pivot::query()->where('type','teacher')->where('quiz_id',$quiz_id)->first();
                if (!is_null($userId) && $userId->user_id == Auth::id()) {

                    $answer->update([
                        'choice' => $request['choice'] ??$answer['choice'],
                        'role' => $request['role'] ?? $answer['role'],
                    ]);
                    $message = __('strings.Updating answer successfully');
                    $code = 200;

                }else{

                    $answer =[];
                    $message = __('strings.question does not belongs to you to update answers');
                    $code = 403;

                }


            } else {

                $answer =[];
                $message = __('strings.You do not have permission to update answer');
                $code = 401;

            }
        } else {
            $answer =[];
            $message = __('strings.This answer not found');
            $code = 404;

        }

        return [
            'answer' => $answer,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to get answers belongs to the specific question
    public function show_answer($question_id) : array
    {
        $question = Question::query()->where('id',$question_id)->first();
        if (!is_null($question)) {
            $answer = Answer::query()->where('question_id', $question_id)->get();
            if (!$answer->isEmpty()) {
                if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')) {

                    if (Auth::user()->hasRole('admin')) {

                        $message = __('strings.Getting all answers in this question successfully');
                        $code = 200;

                    }
                    if (Auth::user()->hasRole('teacher')) {
                        $quiz_id = $question->quiz_id;
                        $quiz = Quiz::query()->where('id',$quiz_id)->first();
                        $course_id = $quiz->course_id;
                        $found = Course_user_pivot::query()
                            ->where('paid',0)
                            ->where('course_id',$course_id)
                            ->where('user_id',Auth::id())
                            ->first();
                        if ($found) {
                            $message = __('strings.Getting all answers in this question successfully');
                            $code = 200;
                        } else {
                            $answer = [];
                            $message = __('strings.This question belongs to another teacher');
                            $code = 404;
                        }
                    }

                } else {
                    $answer = [];
                    $message = __('strings.You do not have any permission to show answers for this question');
                    $code = 401;

                }
            } else {
                $answer = [];
                $message = __('strings.Not found any answer in this question');
                $code = 404;
            }
        }else{
            $answer = [];
            $message = __('strings.This question not found');
            $code = 404;
        }

        return [
            'answer' => $answer,
            'message' => $message ?? [],
            'code' => $code ?? [],
        ];
    }


}
