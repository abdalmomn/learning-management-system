<?php

namespace App\Services\Operation;

use App\Models\Course;
use App\Models\User;
use App\Notifications\SendReportAboutStudentMail;
use App\Notifications\SendReportAboutTeacherMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class StudentAndTeacherOperationService
{

    //function to get The most baying courses
    public function best_seller($subject_id)
    {
        $courses = Course::query()
            ->where('subject_id' , $subject_id)
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        $message = __('strings.getting The most baying courses');
        $code = 200;
        return [
            'courses' => $courses,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to send a report about a student
    public function send_report_student($request , $student_id)
    {
        $admin = User::query()->where('id' , 1)->first();
        $data = [];
        //المستخدم يلي عم يشتكي
        $data['user_id'] = Auth::id();
        //الطالب يلي اشتكو عليه
        $data['student_id'] = $student_id;
        //سبب الشكوى
        $data['reason'] = $request->reason;

        Notification::send($admin, new SendReportAboutStudentMail($data));

        $message = __('strings.Sending report successfully');
        $code = 200;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to send a report about a teacher
    public function send_report_teacher($request , $teacher_id)
    {

        $admin = User::query()->where('id' , 1)->first();
        $data = [];
        //المستخدم يلي عم يشتكي
        $data['user_id'] = Auth::id();
        //الطالب يلي اشتكو عليه
        $data['teacher_id'] = $teacher_id;
        //سبب الشكوى
        $data['reason'] = $request->reason;

        Notification::send($admin, new SendReportAboutTeacherMail($data));

        $message = __('strings.Sending report successfully');
        $code = 200;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }


    //function to get all reports
    public function get_reports(){
        if (Auth::id() == 1){
            $data = \App\Models\Notification::query()->where('data->reason', '!=', null)->get();
            $message = 'Getting all reports successfully';
            $code = 403;
        }else{
            $data = [];
            $message = __('strings.You do not have permission');
            $code = 403;
        }

        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }




}

