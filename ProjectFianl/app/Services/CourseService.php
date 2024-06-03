<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Course_user_pivot;
use App\Models\User;
use App\Models\User_video_pivot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class CourseService
{
    //show all courses that belongs to the teacher
    public function teacher_courses() :array
    {
        if (Auth::user()->hasRole('teacher')){
            $courses = [];
            $courses_ids = Course_user_pivot::query()->where('user_id', Auth::id())->select('course_id')->get();

            foreach ($courses_ids as $courses_id) {
                $course = Course::query()->where('id', $courses_id->course_id)->first();
                if ($course) {
                    $courses[] = $course;
                }
            }
            if (!$courses){
                $message = 'There are no courses for this subject at the moment';
                $code=404;
            }else{
                $message = 'Getting all courses for this teacher';
                $code=200;
            }}else{
            $courses = [];
            $message = 'you dont have permission for getting courses';
            $code=403;
        }
        return [
            'courses' => $courses,
            'message' => $message,
            'code' => $code,

        ];
    }

    //show all courses for special subject
    public function show_courses($subject_id) :array
    {
        $courses = Course::query()
            ->where('subject_id',$subject_id)
            ->get();
        if ($courses->isEmpty()){
            $message = 'There are no courses for this subject at the moment';
            $code=404;
        }else{
            $message = 'Getting all courses for this subject';
            $code=200;
        }
        return [
            'courses' => $courses,
            'message' => $message,
            'code' => $code,

        ];
    }

    //show all courses that already paid by all user
    public function student_myspace_show_courses()
    {
        $courses = [];
        $course_ids = Course_user_pivot::query()->
        where('user_id' , Auth::id())
            ->where('paid' , '=' , 1)
            ->select('course_id')->distinct()->get();
        foreach ($course_ids as $course_id){
            $course = Course::query()->where('id' , $course_id->course_id)->first();
            if ($course){
                $courses[] = $course;
            }
        }
        if (!$courses){
            $message = 'There are no courses for this subject at the moment';
            $code=404;
        }else{
            $message = 'Getting all paided courses';
            $code=200;
        }
        return [
            'courses' => $courses,
            'message' => $message,
            'code' => $code,

        ];
    }

    //create course for special subject with this teacher or any subject and any teacher by admin
    public function create_course($request) : array
    {
        if (!Auth::user()->hasRole('student')){
            $course = Course::query()->create([
                'subject_id' => $request['subject_id'],
                'name' => $request['name'],
                'content' => $request['content'],
                'poster' => $request['poster'],
                'requirements' => $request['requirements'],
                'price' => $request['price'],
            ]);
            Course_user_pivot::query()->create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
            ]);
            $message = 'Course created successfully';
            $code=200;
        }else{
            $course = [];
            $message = 'you dont have permission for creating a course';
            $code=403;
        }
        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    //update special course for teacher role or any course for admin role
    public function update_course($request,$id) : array
    {

        $course=Course::query()->find($id);
        if (!is_null($course)) {
            $any = Course_user_pivot::query()->where('paid',0)->where('course_id',$id)->first();
            if (!is_null($any)) {
                if ((Auth::user()->hasRole('teacher') && Auth::id() == $any->user_id) || Auth::user()->hasRole('admin')) {
                    Course::query()->find($id)->update([
                        'subject_id' => $request['subject_id'] ?? $course['subject_id'],
                        'name' => $request['name'] ?? $course['name'],
                        'content' => $request['content'] ?? $course['content'],
                        'poster' => $request['poster'] ?? $course['poster'],
                        'hour' => $request['hour'] ?? $course['hour'],
                        'requirements' => $request['requirements'] ?? $course['requirements'],
                        'price' => $request['price'] ?? $course['price'],
                    ]);
                    $course=Course::query()->find($id);
                    $message = 'Updated course successfully';
                    $code=200;
                } else {
                    $course = [];
                    $message = 'you dont have permission for updating this course';
                    $code=403;
                }

            }else{
                $course = [];
                $message = 'This course does not belongs to you to delete it or not found in data';
                $code = 403;
            }
        }
        else{
            $course = [];
            $message = 'This course not found';
            $code=404;
        }

        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    //delete course for special subject with this teacher or any subject and any teacher by admin
    public function delete_course($id) : array
    {
        $course=Course::query()->find($id);
        if (!is_null($course)) {
            $any = Course_user_pivot::query()->where('paid',0)->where('course_id',$id)->first();
            if (!is_null($any)) {
                if (!((Auth::user()->hasRole('teacher') && Auth::id() == $any->user_id)) || Auth::user()->hasRole('admin')) {
                    $course = [];
                    $message = 'you dont have permission for deleting this course';
                    $code = 403;
                } else {

                    $course = $course->delete();
                    $message = 'Deleting course successfully';
                    $code = 200;
                }
            }
            else{
                $course = [];
                $message = 'This course does not belongs to you to delete it or not found in data';
                $code = 403;
            }
        }else{
            $course = [];
            $message = 'This course not found';
            $code=404;
        }

        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    //paid for course
    public function paid_course($courseId): array
    {
        $userId = Auth::id();
        $course = Course::query()
            ->join('course_user_pivots', 'courses.id', '=', 'course_user_pivots.course_id')
            ->join('users as teacher', function($join) {
                $join->on('course_user_pivots.user_id', '=', 'teacher.id')
                    ->where('course_user_pivots.paid', '=', 0);
            })
            ->join('users as student', function($join) use ($userId) {
                $join->on('student.id', '=', DB::raw($userId));
            })
            ->where('courses.id', $courseId)
            ->select(
                'courses.id as course_id',
                'courses.price as course_price',
                'student.wallet as user_wallet',
                'teacher.id as teacher_id',
                'teacher.wallet as teacher_wallet',
                'course_user_pivots.favorite as favorite_status',
                'course_user_pivots.user_id as pivot_user_id'
            )
            ->first();

        if ($course) {
            $userWallet = $course->user_wallet;
            $coursePrice = $course->course_price;
            $teacherWallet = $course->teacher_wallet;
            $teacherId = $course->teacher_id;
            $favoriteStatus = $course->favorite_status;
            $pivotUserId = $course->pivot_user_id;

            if ($userWallet >= $coursePrice) {
                $newUserWallet = $userWallet - $coursePrice;
                $newTeacherWallet = $teacherWallet + $coursePrice;

                User::query()->where('id', $userId)->update(['wallet' => $newUserWallet]);
                User::query()->where('id', $teacherId)->update(['wallet' => $newTeacherWallet]);

                if ($pivotUserId == $userId) {
                    // Update existing record
                    Course_user_pivot::query()
                        ->where('course_id', $courseId)
                        ->where('user_id', $userId)
                        ->update(['favorite' => $favoriteStatus, 'paid' => 1]);
                } else {
                    // Create new record
                    Course_user_pivot::query()->create([
                        'user_id' => $userId,
                        'course_id' => $courseId,
                        'favorite' => $favoriteStatus,
                        'paid' => 1
                    ]);
                }

                $message = 'Course has been paid successfully';
                $code = 200;
            } else {
                $course = [];
                $message = 'You have run out of funds';
                $code = 403;
            }
        } else {
            $course = [];
            $message = 'Course not found';
            $code = 404;
        }

        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    //paid for course
//    public function paid_course($courseId):array
//    {
//        $course = Course::query()->where('id' , $courseId)->first();//get course
//
//        if ($course){
//            $course_id_from_pivot = Course_user_pivot::query()->
//            where('course_id' , $courseId)->pluck('course_id')->first();
//
//            $user_id_from_pivot = Course_user_pivot::query()->
//            where('course_id' , $courseId)
//                ->where('user_id' , Auth::id())->pluck('user_id')->first();
//
//            $favorite_status = Course_user_pivot::query()->
//            where('course_id' , $courseId)
//                ->where('user_id' , Auth::id())->pluck('favorite')->first();
//
//            $course_price = Course::query()->where('id' , $courseId)->pluck('price')->first(); //get course price
//
//            $user_wallet = User::query()->where('id' , Auth::id())
//                ->pluck('wallet')->first();//get authenticated user wallet value
//
//            $teacher_id = Course_user_pivot::query()->where('course_id' , $courseId)->
//            where('paid' , 0)->pluck('user_id')->first(); //get teacher_id that have the course
//
//            $teacher_wallet = User::query()->where('id' , $teacher_id)
//                ->pluck('wallet')->first(); // get teacher wallet value
//
//            if ($user_wallet >= $course_price){
//                if (Auth::user()->hasRole('student')){
//                    if ($course_id_from_pivot && $user_id_from_pivot == Auth::id()){//if the course already exist => update
//                        $user_wallet = $user_wallet - $course_price;
//                        User::query()->where('id' , Auth::id())->update([
//                            'wallet' => $user_wallet
//                        ]);
//                        $teacher_wallet = $teacher_wallet + $course_price;
//                        User::query()->where('id' , $teacher_id)->update([
//                            'wallet' =>  $teacher_wallet
//                        ]);
//                        Course_user_pivot::query()->update([
//                            'favorite' => $favorite_status,
//                            'paid' => 1
//                        ]);}else{
//                        $user_wallet = $user_wallet - $course_price;
//                        User::query()->where('id' , Auth::id())->update([
//                            'wallet' => $user_wallet
//                        ]);
//                        $teacher_wallet = $teacher_wallet + $course_price;
//                        User::query()->where('id' , $teacher_id)->update([
//                            'wallet' =>  $teacher_wallet
//                        ]);
//                        Course_user_pivot::query()->create([
//                            'user_id' => Auth::id(),
//                            'course_id' => $courseId,
//                            'favorite' => 0,
//                            'paid' => 1
//                        ]);
//                    }
//                }else if (Auth::user()->hasRole('teacher')){
//                    if ($course_id_from_pivot && $user_id_from_pivot == Auth::id()){//if the course already exist => update
//
//                        $user_wallet = $user_wallet - $course_price;
//                        User::query()->where('id' , Auth::id())->update([
//                            'wallet' => $user_wallet
//                        ]);
//
//                        $teacher_wallet = $teacher_wallet + $course_price;
//                        User::query()->where('id' , $teacher_id)->update([
//                            'wallet' =>  $teacher_wallet
//                        ]);
//                        Course_user_pivot::query()->update([
//                            'favorite' => $favorite_status,
//                            'paid' => 1
//                        ]);
//                    }else{
//                        $user_wallet = $user_wallet - $course_price;
//                        User::query()->where('id' , Auth::id())->update([
//                            'wallet' => $user_wallet
//                        ]);
//                        $teacher_wallet = $teacher_wallet + $course_price;
//                        User::query()->where('id' , $teacher_id)->update([
//                            'wallet' =>  $teacher_wallet
//                        ]);
//                        Course_user_pivot::query()->create([
//                            'user_id' => Auth::id(),
//                            'course_id' => $courseId,
//                            'favorite' => 0,
//                            'paid' => 1
//                        ]);
//                    }
//                }
//                $message = 'course have been pay successfully';
//                $code = 200;
//            }else{
//                $course = [];
//                $message = 'you have run out of founds';
//                $code = 403;
//            }
//        }else{
//            $course = [];
//            $message = 'course not found';
//            $code = 404;
//        }
//        return [
//            'course' => $course,
//            'message' => $message,
//            'code' => $code,
//        ];
//    }

    //add the course to favorite
    public function add_to_favorite($course_id)
    {
        $course = Course::query()->where('id' , $course_id)->first();//get course
        if ($course){
            $course_id_from_pivot = Course_user_pivot::query()->
            where('course_id' , $course_id)->pluck('course_id')->first();

            $user_id_from_pivot = Course_user_pivot::query()->
            where('course_id' , $course_id)
                ->where('user_id' , Auth::id())->pluck('user_id')->first();
//        $user_id = Auth::id();
//        $course = Course_user_pivot::query()->
//        join('courses' , 'courses.id' , '=' , 'course_user_pivots.course_id')
//            ->join('users' , 'course_user_pivots.user_id' , '=' , 'users.id')
//            ->select('user_id' , 'courses.name' , 'full_name' , 'type' , 'courses.valuation' , 'course_user_pivots.paid')
//            ->get();
            if ($course_id_from_pivot && $user_id_from_pivot == Auth::id()){//if the course already exist => update
                Course_user_pivot::query()->where('course_id' , $course_id)
                    ->where('user_id' , Auth::id())->update([
                        'favorite' => 1
                    ]);
                $message = 'added successfully';
                $code = 200;
            }else{ //else if does not exist => create

                Course_user_pivot::query()->create([
                    'user_id' => Auth::id(),
                    'course_id' => $course_id,
                    'paid' => 0,
                    'favorite' => 1
                ]);
                $message = 'added successfully';
                $code = 200;
            }
        }else{
            $message = 'course not found';
            $code = 404;
        }
        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    //remove the course from the favorite
    public function remove_from_favorite($course_id):array
    {
        $course = Course::query()->where('id' , $course_id)->first();//get course

        $course_id_from_pivot = Course_user_pivot::query()->
        where('course_id' , $course_id)->pluck('course_id')->first();

        $user_id_from_pivot = Course_user_pivot::query()->
        where('course_id' , $course_id)
            ->where('user_id' , Auth::id())->pluck('user_id')->first();

        if ($course_id_from_pivot && $user_id_from_pivot == Auth::id()){
            Course_user_pivot::query()->where('course_id' , $course_id)
                ->where('user_id' , Auth::id())->update([
                    'favorite' => 0
                ]);
            $message = 'removed successfully';
            $code = 200;
        }else{
            $course = [];
            $message = 'course not found';
            $code = 200;
        }
        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    //show the favorite for the user
    public function show_favorite()
    {
        $courses_from_pivot = Course_user_pivot::query()
            ->where('user_id' , Auth::id())
            ->where('favorite' ,'=', 1)->pluck('course_id')->first();
        $course = Course::query()
            ->where('id' , $courses_from_pivot)->get();

        if (!$courses_from_pivot){
            $message = 'there are no favorites at the moment';
            $code = 404;
        }else{
            $message = 'all favorite courses';
            $code = 200;
        }
        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function add_rating_to_course($request, $course_id): array
    {
        $course = Course::query()->where('id', $course_id)->first();
        if ($course) {
            $user_id_from_pivot = Course_user_pivot::query()
                ->where('course_id', $course_id)
                ->where('user_id', Auth::id())
                ->pluck('user_id')
                ->first();

            $paid = Course_user_pivot::query()
                ->where('course_id', $course_id)
                ->pluck('paid')
                ->first();

            if ($course && Auth::id() == $user_id_from_pivot && $paid == 1) {
                Course_user_pivot::query()->where('course_id', $course_id)
                    ->where('user_id', Auth::id())
                    ->update(['rate' => $request['rate']]);

                // Get the count and sum of rates that are greater than 0
                $rates = Course_user_pivot::query()
                    ->where('course_id', $course_id)
                    ->where('rate', '>', 0)
                    ->pluck('rate');

                $rate_count = $rates->count();
                $rate_sum = $rates->sum();
                if ($rate_count > 0) {
                    $course->valuation = $rate_sum / $rate_count;
                    $course->save();
                }
                $message = 'rated success';
                $code = 200;
            } else {
                $course = [];
                $message = 'purchase for the course first';
                $code = 403;
            }
        }else{
            $course = '';
            $message = 'course not found';
            $code = 404;
        }
        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }


}
