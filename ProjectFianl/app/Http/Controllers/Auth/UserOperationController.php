<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserOperation\UpdateProfileRequest;
use App\Http\Responses\Response;
use App\Models\User;
use App\Services\UserOperationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserOperationController extends Controller
{
    private UserOperationService $userOperationService;
    public function __construct(UserOperationService $userOperationService)
    {
        $this->userOperationService = $userOperationService;
    }

    //api for show all students in app by admin
    public function show_students() : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userOperationService->show_students();
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for show all teachers in app by admin
    public function show_teachers() : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userOperationService->show_teachers();
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for delete student by the admin
    public function delete_student($id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userOperationService->delete_student($id);
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for delete teacher by the admin
    public function delete_teacher($id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userOperationService->delete_teacher($id);
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for updation profile
    public function update_profile(UpdateProfileRequest $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userOperationService->update_profile($request->validated());
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }


}
