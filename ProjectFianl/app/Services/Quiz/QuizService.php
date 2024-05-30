<?php

namespace App\Services\Quiz;

use App\Models\Course;
use App\Models\Course_user_pivot;
use App\Models\Quiz;
use App\Models\Quiz_user_pivot;
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




}
