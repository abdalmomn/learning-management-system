<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\AddingRateRequest;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Responses\Response;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CourseController extends Controller
{
    private  CourseService $courseService;

    public function __construct(CourseService $courseService){
        $this->courseService = $courseService;
    }

    //api to show all course for special subject to all roles
    public function teacher_show_courses() : jsonResponse
    {
        $data = [];
        try {

            $data = $this->courseService->teacher_courses();
            return Response::Success($data['courses'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }
    public function show_courses($subject_id) : jsonResponse
    {
        $data = [];
        try {

            $data = $this->courseService->show_courses($subject_id);
            return Response::Success($data['courses'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to create course for special subject by this teacher or any subject and any course by admin
    public function create_course(CreateCourseRequest $request) : jsonResponse
    {
        $data = [];
        try {
            $imagePath = $request->file('poster')->store('images' , 'public');
            $imageUrl = Storage::url($imagePath);
            $validatedData = $request->validated();
            $validatedData['poster'] = $imageUrl;
            $data = $this->courseService->create_course($validatedData);
            return Response::Success($data['course'], $data['message'], $data['code']);
        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to update course for special subject by this teacher or any subject and any course by admin
    public function update_course(UpdateCourseRequest $request,$id) : jsonResponse
    {
        $data = [];
        try {
            $imagePath = $request->file('poster')->store('images' , 'public');
            $imageUrl = Storage::url($imagePath);
            $validatedData = $request->validated();
            $validatedData['poster'] = $imageUrl;
            $data = $this->courseService->update_course($validatedData,$id);
            return Response::Success($data['course'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to delete course for special subject by this teacher or any subject and any course by admin
    public function delete_course($id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->courseService->delete_course($id);
            return Response::Success($data['course'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    public function myspace_course():JsonResponse
    {
        $data = [];
        try {
            $data = $this->courseService->student_myspace_show_courses();
            return Response::Success($data['courses'],$data['message'],$data['code']);
        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    public function paid_for_course($course_id):JsonResponse
    {
        $data = [];
        try {
            $data = $this->courseService->paid_course($course_id);
            return Response::Success($data['course'],$data['message'],$data['code']);
        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    public function add_to_favorite($course_id):JsonResponse
    {
        $data = [];
        try {
            $data = $this->courseService->add_to_favorite($course_id);
            return Response::Success($data['course'] , $data['message'] , $data['code']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error($data,$message);
        }
    }
    public function remove_from_favorite($course_id):JsonResponse
    {
        $data = [];
        try {
            $data = $this->courseService->remove_from_favorite($course_id);
            return Response::Success($data['course'] , $data['message'] , $data['code']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error($data,$message);
        }
    }
    public function show_favorite():JsonResponse
    {
        $data = [];
        try {
            $data = $this->courseService->show_favorite();
            return Response::Success($data['course'] , $data['message'] , $data['code']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error($data,$message);
        }
    }

    public function add_rate_for_course(AddingRateRequest $request,$course_id)
    {
        $data = [];
        try {
            $validatedData = $request->validated();
            $data = $this->courseService->add_rating_to_course($validatedData,$course_id);
            return Response::Success($data['course'] , $data['message'] , $data['code']);
        }catch(Throwable $th){
            $message = $th->getMessage();
            return Response::Error($data,$message);
        }
    }
}
