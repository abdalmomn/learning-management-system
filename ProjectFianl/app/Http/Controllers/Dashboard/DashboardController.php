<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\commentAndReplies\AddCommentRequest;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Requests\Quiz\CreateAnswerRequest;
use App\Http\Requests\Quiz\CreateQuestionRequest;
use App\Http\Requests\Quiz\SubmitAnswersRequest;
use App\Http\Requests\Quiz\UpdateAnswerRequest;
use App\Http\Requests\Quiz\UpdateQuestionRequest;
use App\Http\Requests\UserOperation\UpdateProfileRequest;
use App\Http\Requests\Video\AddVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Http\Responses\Response;
use App\Models\Question;
use App\Models\Transaction;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Throwable;
class DashboardController extends Controller
{
    public DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function show_all_students(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_all_students();
            return Response::Success($data['users'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function show_all_teachers(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_all_teachers();
            return Response::Success($data['users'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function show_all_courses()
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_all_courses();
            return Response::Success($data['courses'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
    public function show_all_courses_by_subject($subject_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_courses_by_subject($subject_id);
            return Response::Success($data['courses'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function show_videos_by_course($course_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_videos_by_course($course_id);
            return Response::Success($data['videos'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function show_all_videos(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_all_videos();
            return Response::Success($data['videos'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function show_all_quizzes(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_all_quizzes();
            return Response::Success($data['quizzes'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
    public function show_quizzes_by_course($course_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_quizzes_by_course($course_id);
            return Response::Success($data['quizzes'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function show_profile($user_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_profile($user_id);
            return Response::Success($data['user'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function update_profile(UpdateProfileRequest $request, $user_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->update_profile($request, $user_id);
            return Response::Success($data['user'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function delete_profile($user_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->delete_profile($user_id);
            return Response::Success($data['user'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function show_course($course_id)
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_course($course_id);
            return Response::Success($data['video'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function create_course(CreateCourseRequest $request): jsonResponse
    {
        $data = [];
        try {
            $imagePath = $request->file('poster')->store('images', 'public');
            $imageUrl = Storage::disk('public')->path($imagePath);
            $validatedData = $request->validated();
            $validatedData['poster'] = $imageUrl;
            $data = $this->dashboardService->create_course($validatedData);
            return Response::Success($data['course'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    public function update_course(UpdateCourseRequest $request, $id): jsonResponse
    {
        $data = [];
        try {
            $imagePath = $request->file('poster')->store('images', 'public');
            $imageUrl = Storage::disk('public')->path($imagePath);
            $validatedData = $request->validated();
            $validatedData['poster'] = $imageUrl;
            $data = $this->dashboardService->update_course($validatedData, $id);
            return Response::Success($data['course'], $data['message'], $data['code']);

        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    public function delete_course($id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->delete_course($id);
            return Response::Success($data['course'], $data['message'], $data['code']);

        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }


    public function create_video(AddVideoRequest $request, $course_id): JsonResponse
    {
        $video = [];
        try {
            if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')) {
                // Store the video in 'public/videos'
                $videoPath = $request->file('url')->store('videos', 'public');
                $videoUrl = Storage::url($videoPath);

                // Validate and store video data
                $validatedData = $request->validated();
                $validatedData['url'] = $videoUrl;

                // Fetch video duration using the service
                $validatedData['duration'] = $this->dashboardService->getVideoDuration($request->file('url'));

                // Add video to the database
                $video = $this->dashboardService->add_Video($validatedData, $course_id);

                return Response::Success($video['video'], $video['message']);
            } else {
                return response()->json(['message' => 'unauthorized'], 403);
            }
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }


    public function update_video(UpdateVideoRequest $request, $course_id, $video_id): JsonResponse
    {
        $video = [];
        try {
            if (Auth::user()->hasRole('teacher')) {
                $videoPath = $request->file('url')->store('videos', 'public');
                $videoUrl = Storage::url($videoPath);

                $validatedData = $request->validated();
                $validatedData['url'] = $videoPath;
                $validatedData['duration'] = $this->dashboardService->videoDuration;

                $video = $this->dashboardService->update_videos($validatedData, $course_id, $video_id);
                return Response::Success($video['video'], $video['message']);
            } else {
                return response()->json(['message' => 'unauthorized']);
            }
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }

    public function delete_video($course_id, $video_id): JsonResponse
    {
        $video = [];
        try {
            $video = $this->dashboardService->delete_video($course_id, $video_id);
            return Response::Success($video['video'], $video['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }

    public function show_video($course_id, $video_id)
    {
        $video = [];
        try {
            $video = $this->dashboardService->show_video($course_id, $video_id);
            return Response::Success($video['video'], $video['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }


    public function add_like($video_id): JsonResponse
    {
        $video = [];
        try {
            $video = $this->dashboardService->add_like($video_id);
            return Response::Success($video['video'], $video['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }

    public function remove_like($video_id): JsonResponse
    {
        $video = [];
        try {
            $video = $this->dashboardService->remove_like($video_id);
            return Response::Success($video['video'], $video['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }

    public function add_dislike($video_id): JsonResponse
    {
        $video = [];
        try {
            $video = $this->dashboardService->add_dislike($video_id);
            return Response::Success($video['video'], $video['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }

    public function remove_dislike($video_id): JsonResponse
    {
        $video = [];
        try {
            $video = $this->dashboardService->remove_dislike($video_id);
            return Response::Success($video['video'], $video['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }


    //quizzes -  ------------------------------------------------------------

    public function create_quiz($course_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->create_quiz($course_id);
            return Response::Success($data['quiz'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    //api to delete quiz from course by teacher from his courses only
    public function delete_quiz($quiz_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->delete_quiz($quiz_id);
            return Response::Success($data['quiz'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    public function show_quizzes_with_question_and_answer(): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_quizzes_with_question_and_answer();
            return Response::Success($data['quiz'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    //api to go to the quiz in the end of course
    public function go_to_quiz($course_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->go_to_quiz($course_id);
            return Response::Success($data['quiz'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    //api to submit choices and getting mark
    public function submit_quiz(SubmitAnswersRequest $request, $quiz_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->submit_quiz($request->validated(), $quiz_id);
            return Response::Success($data['data'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }


    //answer ----------------------------------------------

    public function create_answer(CreateAnswerRequest $request, $question_id): jsonResponse
    {
        $data = [];
        try {

            $data = $this->dashboardService->create_answer($request->validated(), $question_id);
            return Response::Success($data['answer'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    //api to delete answer from question by teacher for his questions only
    public function delete_answer($answer_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->delete_answer($answer_id);
            return Response::Success($data['answer'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    //api to update answer from question by teacher for his questions only
    public function update_answer(UpdateAnswerRequest $request, $answer_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->update_answer($request->validated(), $answer_id);
            return Response::Success($data['answer'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    //api to get answers for special question
    public function show_answer($question_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_answer($question_id);
            return Response::Success($data['answer'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }


    //questions -----------------------------------------------------------

    public function create_question(CreateQuestionRequest $request, $quiz_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->create_question($request->validated(), $quiz_id);
            return Response::Success($data['question'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    //api to delete question from  quiz by teacher from his questions only
    public function delete_question($question_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->delete_question($question_id);
            return Response::Success($data['question'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    //api to update question from quiz by teacher from his questions only
    public function update_question(UpdateQuestionRequest $request, $question_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->update_question($request->validated(), $question_id);
            return Response::Success($data['question'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }

    //api to get question for special quiz
    public function show_question($quiz_id): jsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->show_question($quiz_id);
            return Response::Success($data['question'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }


    //comments and replies ----------------------------------------------


    public function get_comments($video_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->get_comments($video_id);
            return Response::Success($data['data'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }

    }

    //api to get all replies for special comment
    public function get_replies($comment_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->get_replies($comment_id);
            return Response::Success($data['data'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }

    }

    //api to add comment for special video
    public function add_comment(AddCommentRequest $request, $video_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->add_comment($request, $video_id);
            return Response::Success($data['data'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }

    }

    //api to add reply for special comment
    public function add_reply(AddCommentRequest $request, $comment_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->add_reply($request, $comment_id);
            return Response::Success($data['data'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }

    }

    //api to add reply for special comment
    public function delete_comment($comment_id): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->delete_comment($comment_id);
            return Response::Success($data['data'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }

    }


    public function get_reports(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->get_reports();
            return Response::Success($data['data'], $data['message'], $data['code']);

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);

        }
    }








    public function search_course(Request $request,$subject_id) : JsonResponse
    {
    $data = [];
    try {
        $data = $this->dashboardService->search_course($request,$subject_id);
        return Response::Success($data['data'],$data['message'],$data['code']);

    }catch (\Throwable $th){
        $message=$th->getMessage();
        return Response::Error($data,$message);

    }

    }

    //api for searching about video in course
    public function search_video(Request $request,$course_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->search_video($request,$course_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    public function all_transactions():JsonResponse
    {
        $data = [];
        try {
            $data = $this->dashboardService->all_transactions();
            return Response::Success($data['trans'],$data['message']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }
}
