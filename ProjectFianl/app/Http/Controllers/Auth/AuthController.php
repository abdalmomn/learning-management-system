<?php

namespace App\Http\Controllers\Auth;

use App\actions\ResetCodePasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AcceptedTeacherRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Requests\ResetCodePassword\UserCheckCode;
use App\Http\Requests\ResetCodePassword\UserForgotPasswordRequest;
use App\Http\Requests\ResetCodePassword\UserResetPassword;
use App\Http\Responses\Response;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AuthController extends Controller
{
    private UserService $userService;
    private ResetCodePasswordAction $resetCodePasswordAction;

    public function __construct(UserService $userService,ResetCodePasswordAction $resetCodePasswordAction){
        $this->userService = $userService;
        $this->resetCodePasswordAction = $resetCodePasswordAction;
    }

    //api for register process
    public function register(UserRegisterRequest $request) : JsonResponse
    {
        $data = [];
        try {
            $imagePath = $request->file('image')->store('images' , 'public');
            $imageUrl = Storage::url($imagePath);
            $validatedData = $request->validated();
            $validatedData['image'] = $imageUrl;
            $data = $this->userService->register($validatedData);
            return Response::Success($data['user'],$data['message']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for login process
    public function login(UserLoginRequest $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->login($request->validated());
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for logout process
    public function logout() : JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->logout();
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for delete the last code for this user and send a new one
    public function user_forgot_password(UserForgotPasswordRequest $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->resetCodePasswordAction->user_forgot_password($request->validated());
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api for delete the last code for this user and send a new one
    public function user_check_code(UserCheckCode $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->resetCodePasswordAction->user_check_code($request->validated());
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to take the code with the new password
    public function user_reset_password(UserResetPassword $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->resetCodePasswordAction->user_reset_password($request->validated());
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to Accept teacher in our app and make a new account for him and sending reapply by email
    //يستقبل هذا التابع ال id تبع الاشعار و مصفوفة البيانات تبع الاستاذ
    public function Accept_teacher_coming_by_email(AcceptedTeacherRequest $request,$id) : jsonResponse
    {
        $data = [];
        try {

            $imagePath = $request->file('image')->store('images' , 'public');
            $imageUrl = Storage::url($imagePath);
            $validatedData = $request->validated();
            $validatedData['image'] = $imageUrl;
            $data = $this->userService->Accept_teacher_coming_by_email($validatedData,$id);
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to add teacher by admin roles in real life
    public function admin_adding_new_teacher(AcceptedTeacherRequest $request) : jsonResponse
    {
        $data = [];
        try {

            $imagePath = $request->file('image')->store('images' , 'public');
            $imageUrl = Storage::url($imagePath);
            $validatedData = $request->validated();
            $validatedData['image'] = $imageUrl;
            $data = $this->userService->admin_adding_new_teacher($validatedData);
            return Response::Success($data['user'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }



}
