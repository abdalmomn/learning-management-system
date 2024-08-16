<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Course_user_pivot;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserOperationService
{

    //function to get all students in the app by admin
    public function show_students(): array
    {
        // تحقق من صلاحيات المستخدم
        if (Auth::user()->hasRole('admin')) {
            // جلب الطلاب إذا كان المستخدم مدير
            $students = User::query()->where('type', 'student')->get();

            if (!$students->isEmpty()) {
                $data = $students;
                $message = __('strings.Getting all students');
                $code = 200;
            } else {
                $data = []; // تأكد من أن $data معرفة دائماً
                $message = __('strings.Not found');
                $code = 404;
            }
        } else if (Auth::user()->hasRole('teacher')) {
            // جلب الدورات التي لم يتم دفعها إذا كان المستخدم معلم
            $teacher_courses = Course_user_pivot::query()
                ->where('paid', 0)
                ->where('user_id', Auth::id())
                ->get();

            $courses_id = [];
            $students = collect(); // استخدم collection بدلاً من array

            if (!$teacher_courses->isEmpty()) {
                // جلب معرفات الدورات التدريبية
                foreach ($teacher_courses as $teacher_course) {
                    $courses_id[] = $teacher_course->course_id;
                }

                // جلب الطلاب لكل دورة تدريبية
                foreach ($courses_id as $course_id) {
                    $students = $students->merge(
                        Course_user_pivot::query()
                            ->where('paid', 1)
                            ->where('course_id', $course_id)
                            ->get()
                    );
                }

                if (!$students->isEmpty()) {
                    $data = $students;
                    $message = __('strings.Getting all students');
                    $code = 200;
                } else {
                    $data = []; // تأكد من أن $data معرفة دائماً
                    $message = __('strings.No students found in your courses');
                    $code = 404;
                }
            } else {
                $data = []; // تأكد من أن $data معرفة دائماً
                $message = __('strings.Not found any course belongs to you');
                $code = 404;
            }
        } else {
            $data = []; // تأكد من أن $data معرفة دائماً
            $message = __('strings.You do not have permission');
            $code = 403;
        }

        return [
            'user' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to get all students in special course
    public function show_student_special_course($course_id): array
    {
        // الحصول على معلومات الكورس بناءً على معرف الكورس
        $course = Course::find($course_id);

        if (!is_null($course)) {
            // تحقق من دور المستخدم الحالي
            if (Auth::user()->hasRole('admin')) {
                // في حالة كان المستخدم مديراً، احضر جميع الطلاب المدفوعين للكورس المطلوب
                $students = Course_user_pivot::query()
                    ->where('paid', 1)
                    ->where('course_id', $course_id)
                    ->with('users') // قم بتحميل العلاقة مع المستخدمين
                    ->get()
                    ->pluck('users') // احصل على المستخدمين فقط
                    ->flatten(); // اجعل القائمة مسطحة لتجنب القوائم المتداخلة

                $data = $students;
                $message = __('strings.Students for this course');
                $code = 200;
            } else if (Auth::user()->hasRole('teacher')) {
                // في حالة كان المستخدم معلمًا، تحقق إذا كان المعلم مسجل في الكورس ومن ثم احضر الطلاب المدفوعين
                $teacher_course = Course_user_pivot::query()
                    ->where('paid', 0)
                    ->where('user_id', Auth::id())
                    ->where('course_id', $course_id)
                    ->exists();

                if ($teacher_course) {
                    $students = Course_user_pivot::query()
                        ->where('paid', 1)
                        ->where('course_id', $course_id)
                        ->with('users') // قم بتحميل العلاقة مع المستخدمين
                        ->get()
                        ->pluck('users') // احصل على المستخدمين فقط
                        ->flatten(); // اجعل القائمة مسطحة لتجنب القوائم المتداخلة

                    $data = $students;
                    $message = __('strings.Students for this course');
                    $code = 200;
                } else {
                    $data = [];
                    $message = __('strings.You are not associated with this course');
                    $code = 403;
                }
            } else {
                $data = [];
                $message = __('strings.You do not have any permission');
                $code = 403;
            }
        } else {
            $data = [];
            $message = __('strings.This course not found in data');
            $code = 404;
        }

        return [
            'data' => $data,
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
                    $message = __('strings.getting all teachers');
                    $code = 200;

                }else{

                    $message = __('strings.Not found');
                    $code = 404;

                }
            }else{

                $message = __('strings.You do not have permission');
                $code = 403;

            }
            return [
                'user' => $data ??[],
                'message' => $message,
                'code' => $code,
            ];

    }

    //function to get all teachers in special subject
    public function show_teachers_by_subject($subject_id): array
    {
        // الحصول على الكورسات المرتبطة بالمادة المحددة
        $courses = Course::where('subject_id', $subject_id)->pluck('id');

        if ($courses->isNotEmpty()) {
            // تحقق من دور المستخدم الحالي
            if (Auth::user()->hasRole('admin')) {
                // في حالة كان المستخدم مديراً، احضر جميع الأساتذة المرتبطين بالكورسات المحددة
                $teachers = Course_user_pivot::query()
                    ->where('paid' , 0)
                    ->whereIn('course_id', $courses)
                    ->with('users') // قم بتحميل العلاقة مع المستخدمين
                    ->get()
                    ->pluck('users') // احصل على المستخدمين فقط
                    ->flatten()
                    ->unique('id'); // إزالة التكرار بناءً على معرف المستخدم

                $data = $teachers;
                $message = __('strings.Teachers for this subject');
                $code = 200;
            } else {
                $data = [];
                $message = __('strings.You do not have any permission');
                $code = 403;
            }
        } else {
            $data = [];
            $message = __('strings.No courses found for this subject');
            $code = 404;
        }

        return [
            'data' => $data,
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
                    $message = __('strings.Deleted successfully');
                    $code = 200;

                } else {

                    $data = [];
                    $message = __('strings.This account belongs to teacher not student');
                    $code = 403;

                }
            }else{

                    $data = [];
                $message = __('strings.You do not have permission to delete this account');
                    $code = 403;

                }

        }else{
            $data = [];
            $message = __('strings.Not found');
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
                $message = __('strings.Deleted successfully');
                $code = 200;
                } else {

                    $data = [];
                    $message = __('strings.This account belongs to teacher not student');
                    $code = 403;

                }
            }else{

                $data = [];
                $message = __('strings.You do not have permission to delete this account');
                $code = 403;
            }
        }else{
            $data = [];
            $message = __('strings.Not found');
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
            $message = __('strings.Updated profile successfully');
            $code=200;

        } else {

             $user = [];
             $message = __('strings.Updating profile not for admin');
             $code=403;
        }
        return [
            'user' => $user,
            'message' => $message,
            'code' => $code,
        ];
    }
    //show profile for user (student and teacher)
    public function show_profile() : array
    {
            $user=User::query()->where('id',Auth::id())->first();
            $message = __('strings.Your profile');
            $code=200;
        return [
            'user' => $user,
            'message' => $message,
            'code' => $code,
        ];
    }
}
