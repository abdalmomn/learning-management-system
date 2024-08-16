<?php

namespace App\Services\Operation;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use App\Models\Video;

class SearchService
{
    //function to search about special course
    public function search_course($request,$subject_id)
    {
        $subject = Subject::query()->find($subject_id);
        if (!is_null($subject)) {
            $query = $request['course_name'];
            $course = Course::query()
                ->where('subject_id', $subject_id)
                ->where('name', 'like', '%' . $query . '%')
                ->first();

            $message = __('strings.Getting course successfully') ;
            $code = 200;
        }else{
            $course = [];
            $message = __('strings.subject not found');
            $code = 404;

        }

        return [
            'data' => $course,
            'message' => $message,
            'code' => $code
        ];
    }

    //function to search about special video
    public function search_video($request,$course_id)
    {
        $course = Course::query()->find($course_id);
        if (!is_null($course)) {
            $query = $request->input('video_id');
            $video = Video::query()
                ->where('course_id', $course_id)
                ->where('id', 'like', $query . '%')
                ->first();

            $message = __('strings.Getting video successfully');
            $code = 200;

        }else{
            $video = [];
            $message = __('strings.course not found');
            $code = 404;
        }

        return [
            'data' => $video,
            'message' => $message,
            'code' => $code
        ];
    }


}
