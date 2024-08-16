<?php

namespace App\Services\Quiz;

use App\Mail\certificate;
use App\Models\Course;
use App\Models\Course_user_pivot;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Quiz_user_pivot;
use App\Models\Transaction;
use App\Models\User;
use App\Models\User_video_pivot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

                                $message = __('strings.Adding quiz successfully');
                                $code = 200;

                    } else {

                        $message = __('strings.There is quiz in this course already');
                        $code = 403;

                    }
                }else{

                    $message = __('strings.Course does not belongs to you to add quiz on it');
                    $code = 401;

                }
            } else {

                $message = __('strings.You do not have permission to add quiz');
                $code = 401;

            }
        }else{

            $message = __('strings.Course not found');
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
                        $message = __('strings.Deleting quiz successfully');
                        $code = 200;

                } else {
                    $quiz =[];
                    $message = __('strings.This quiz does not belongs to you');
                    $code = 403;

                }
            }else{
                $quiz =[];
                $message = __('strings.You do not have permission to delete quiz');
                 $code = 401;

                }
        } else {
            $quiz =[];
            $message = __('strings.Not found in data');
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
                    $message = __('strings.Getting all quizzes in data successfully');
                    $code = 200;

                } if (Auth::user()->hasRole('teacher')) {
                    $quizzesUsers = Quiz_user_pivot::query()->where('type','teacher')->where('user_id',Auth::id())->get();
                    if (!$quizzesUsers->isEmpty()){
                        $quizzes = [];

                        foreach ($quizzesUsers as $quizzesUser) {

                            $quizzes[]  = Quiz::query()->where('id',$quizzesUser->quiz_id)->get();

                        }
                        $quizzes = $quizzes;
                        $message = __('strings.Getting all your quizzes successfully');
                        $code = 200;


                    } else {
                        $quizzes =[];
                        $message = __('strings.You do not have any quiz');
                        $code = 404;
                    }
                }

        }else {
            $quizzes =[];
                $message = __('strings.You do not have any permission to show all quizzes');
            $code = 401;

        }
        }else{
            $quizzes = [];
            $message = __('strings.Not found any quiz');
            $code = 404;
        }

        return [
            'quiz' => $quizzes,
            'message' => $message ?? [],
            'code' => $code ?? [],
        ];
    }

    //function to get all quizzes in data with questions and answers by admin or teacher ro student
    public function show_quizzes_with_question_and_answer() : array
    {
        $quizzes = Quiz::query()->get();
        if (!is_null($quizzes)) {
                if (Auth::user()->hasRole('admin')) {

                    $quizzes = Quiz::query()->with('questions.answers')->get();
                    $message = __('strings.Getting all quizzes in data successfully');
                    $code = 200;

                } if (Auth::user()->hasRole('teacher')) {
                    $quizzesUsers = Quiz_user_pivot::query()->where('type','teacher')->where('user_id',Auth::id())->get();
                    if (!$quizzesUsers->isEmpty()){
                        $quizzes = [];
                        foreach ($quizzesUsers as $quizzesUser) {

                            $quizzes[]  = Quiz::query()->where('id',$quizzesUser->quiz_id)->with('questions.answers')->get();

                        }


                        $quizzes = $quizzes;
                        $message = __('strings.Getting all your quizzes successfully');
                        $code = 200;


                    } else {
                        $quizzes =[];
                        $message = __('strings.You do not have any quiz');
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
                    $message = __('strings.Getting all your quizzes successfully');
                    $code = 200;


                } else {
                    $quizzes =[];
                    $message = __('strings.You do not have any quiz');
                    $code = 404;
                }
            }


        }else{
            $quizzes = [];
            $message = __('strings.Not found any quiz');
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
                 $message = __('strings.Getting quiz successfully');
                 $code = 200;
             }else{
                 $quiz =[];
                 $message = __('strings.This course belongs to another teacher');
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
                     $message = __('strings.Getting quiz successfully');
                     $code = 200;
                 }else{
                     $quiz =[];
                     $message = __('strings.You need to watch at least 80% from this course');
                     $code = 403;
                 }

             }else{
                 $quiz =[];
                 $message = __('strings.You need to buy this course first');
                 $code = 403;
             }

         }

     }else{
         $quiz =[];
         $message = __('strings.There is no quiz for this course currently');
         $code = 404;
     }
        return [
            'quiz' => $quiz,
            'message' => $message,
            'code' => $code,
        ];

     }

     //function to submit the answers from quiz and add mark
    public function submit_quiz($request, $quiz_id): array
    {
        // العثور على الكويز بواسطة معرف الكويز
        $quiz = Quiz::query()->where('id',$quiz_id)->first();
        if (!is_null($quiz)) {
            // التحقق مما إذا كان المستخدم له دور "طالب"
            if (Auth::user()->hasRole('student')) {
                $found = Quiz_user_pivot::query()
                    ->where('type', 'student')
                    ->where('user_id', Auth::id())
                    ->where('quiz_id', $quiz_id)
                    ->first();
                if (!$found) {
                    // الحصول على عدد الفيديوهات في هذا الكورس
                    $course = Course::query()->withCount('videos')->find($quiz->course_id);
                    $countVideo = $course->videos_count;

                    // الحصول على جميع الأسئلة وإجاباتها في هذا الاختبار
                    $questions = Question::query()->where('quiz_id', $quiz_id)->with('answers')->get();
                    $trueAnswerId = [];

                    // بناء مصفوفة تحتوي على ID الإجابات الصحيحة
                    foreach ($questions as $question) {
                        foreach ($question->answers as $answer) {
                            if ($answer->role == 1) { // role == 1 يعني الإجابة الصحيحة
                                $trueAnswerId[] = $answer->id;
                                break;
                            }
                        }
                    }

                    // مصفوفة الإجابات الخاصة بالطالب
                    $answersStudent = $request['answers'];
                    $mark = 0;

                    // المقارنة بين الإجابات الصحيحة وإجابات الطالب
                    foreach ($trueAnswerId as $index => $trueAnswer) {
                        if (isset($answersStudent[$index]) && $answersStudent[$index] == $trueAnswer) {
                         //   $mark += (1 / $countVideo);
                            $mark += 1;
                        }
                    }

                    // تسجيل نتيجة الطالب في جدول الكسر
                    Quiz_user_pivot::query()->create([
                        'user_id' => Auth::id(),
                        'quiz_id' => $quiz_id,
                        'type' => 'student',
                        'mark' => $mark
                    ]);

                    $userId = Auth::id();
                    $user = User::query()->where('id' , $userId)->first();
                    $userEmail = $user->email ;

                    $course = Course::query()->where('id' , $quiz->course_id)->first();
                    $discount_value = ($course->price) * 0.10 ;

                    // معالجة حالات خاصة بناءً على النتيجة
                    if ($mark == 10) {

                        User::query()->where('id',$userId)->update([
                            'wallet' => $user->wallet + $discount_value,
                        ]);

                        Transaction::create([
                            'user_id' => $userId,
                            'course_id' =>$course->id,
                            'type' => 'discount',
                            'amount' => $discount_value,
                        ]);

                        $data = [];
                        $data['message'] = __('strings.Excellent. You got a perfect mark on this test. You got coupons worth ten percent of the price of this course. These coupons have been added to your balance.');
                        $data['mark'] = $mark;
                        $data['name'] = $user->full_name;
                        $data['courseName'] = $course->name;
                        Mail::to($userEmail)->send(new certificate($data));
                    } else if ($mark == 9 || $mark == 8 ||$mark == 7) {
                        $data = [];
                        $data['message'] = __('strings.Very Good. You got a good mark on this test.');
                        $data['mark'] = $mark;
                        $data['name'] = $user->full_name;
                        $data['courseName'] = $course->name;
                        Mail::to($userEmail)->send(new certificate($data));
                    }else{
                        $data = [];
                        $data['message'] = __('strings.Sorry, you failed in this test');
                        $data['mark'] = $mark;
                        $data['name'] = $user->full_name;
                        $data['courseName'] = $course->name;
                        Mail::to($userEmail)->send(new certificate($data));
                    }

                    $data = [];
                    $data['mark'] = $mark . ' From 10';
                    $data['answers'] = $answersStudent;
                    $message = __('strings.Your mark');
                    $code = 200;
                } else {
                    $data = [];
                    $message = __('strings.You have already solved this quiz');
                    $code = 403;
                }
            } else {
                $data = [];
                $message = __('strings.This test is not for you');
                $code = 403;
            }
        } else {
            $data = [];
            $message = __('strings.This quiz was not found');
            $code = 404;
        }

        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }





}
