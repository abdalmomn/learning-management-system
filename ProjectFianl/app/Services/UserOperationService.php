<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserOperationService
{

    //function to get all students in the app by admin
    public function show_students() : array
    {
        if(Auth::user()->hasRole('admin')){
        $students = User::query()->where('type','student')->get();
            if(!is_null($students)){

                $data = $students;
                $message = 'getting all students';
                $code = 200;

            }else{

                $message = 'Not found';
                $code = 404;

            }
        }else{

            $message = 'You do not have permission';
            $code = 403;

        }
        return [
            'user' => $data ??[],
            'message' => $message,
            'code' => $code,
        ];

    }

    //function to delete student in the app by admin
    public function show_teachers() : array
    {
        if(Auth::user()->hasRole('admin')){
            $teachers = User::query()->where('type','teacher')->get();
                if(!is_null($teachers)){

                    $data = $teachers;
                    $message = 'getting all teachers';
                    $code = 200;

                }else{

                    $message = 'Not found';
                    $code = 404;

                }
            }else{

                $message = 'You do not have permission';
                $code = 403;

            }
            return [
                'user' => $data ??[],
                'message' => $message,
                'code' => $code,
            ];

    }

    //function to delete student in the app
    public function delete_student($id) : array
    {

        $student = User::query()->find($id);
        if(!is_null($student)){
            if (Auth::user()->hasRole('admin') ) {
                if ($student->type == 'student') {

                    $data = $student;
                    $student->delete();
                    $message = 'Deleted successfully';
                    $code = 200;

                } else {

                    $data = [];
                    $message = 'This account belongs to teacher not student';
                    $code = 403;

                }
            }else{

                    $data = [];
                $message = 'You do not have permission to delete this account';
                    $code = 403;

                }

        }else{
            $data = [];
            $message = 'Not found ';
            $code =404;

        }
        return [
            'user' => $data,
            'message' => $message,
            'code' => $code
        ];

    }

    //function to delete teacher in the app
    public function delete_teacher($id) : array
    {
        $teacher = User::query()->find($id);
        if(!is_null($teacher)){
            if(Auth::user()->hasRole('admin' )){
                if ($teacher->type == 'teacher' ){

                $data = $teacher;
                $teacher->delete();
                $message = 'Deleted successfully';
                $code = 200;
                } else {

                    $data = [];
                    $message = 'This account belongs to teacher not student';
                    $code = 403;

                }
            }else{

                $data = [];
                $message = 'You do not have permission to delete this account';
                $code = 403;

            }

        }else{
            $data = [];
            $message = 'Not found ';
            $code =404;

        }
        return [
            'user' => $data,
            'message' => $message,
            'code' => $code
        ];

    }

    //update profile for user (student and teacher)
    public function update_profile($request) : array
    {
          if ((Auth::user()->hasRole('teacher')) || Auth::user()->hasRole('student')) {
                $user=User::query()->find(Auth::id());
               $user->update([
                   'full_name' => $request['full_name'] ?? $user['full_name'],
                   'phone' => $request['phone'] ?? $user['phone'],
                   'birthday' => $request['birthday'] ?? $user['birthday'],
                   'address' => $request['address'] ?? $user['address'],
                    'image' => $request['image'] ?? $user['image'],
            ]);
            $user=User::query()->find(Auth::id());
            $message = 'Updated profile successfully';
            $code=200;

        } else {

             $user = [];
             $message = 'Updating profile not for admin';
             $code=403;

        }

        return [
            'user' => $user,
            'message' => $message,
            'code' => $code,
        ];
    }



}
