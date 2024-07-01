<?php
namespace App\Http\Controllers\Operation;


use App\Http\Controllers\Controller;
use App\Http\Requests\UserOperation\ReasonRequest;
use App\Http\Responses\Response;
use App\Services\Operation\StudentAndTeacherOperationService;
use Illuminate\Http\JsonResponse;

class StudentAndTeacherOperationController extends Controller{

    private StudentAndTeacherOperationService $studentAndTeacherOperationService;
    public function __construct(StudentAndTeacherOperationService $studentAndTeacherOperationService){
        $this->studentAndTeacherOperationService = $studentAndTeacherOperationService;
    }


    //api to get The most baying courses
    public function best_seller($subject_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->studentAndTeacherOperationService->best_seller($subject_id);
            return Response::Success($data['courses'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to send a report about a student
    public function send_report_student(ReasonRequest $request ,$student_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->studentAndTeacherOperationService->send_report_student($request , $student_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to send a report about a teacher
    public function send_report_teacher(ReasonRequest $request ,$teacher_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->studentAndTeacherOperationService->send_report_teacher($request , $teacher_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to get all reports
    public function get_reports() : JsonResponse
    {
        $data = [];
        try {
            $data = $this->studentAndTeacherOperationService->get_reports();
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }




}
