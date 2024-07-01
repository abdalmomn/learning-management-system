<?php

namespace App\Http\Controllers\Operation;

use App\actions\NotificationOperationAction;
use App\Http\Controllers\Controller;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationOperationController extends Controller
{
    private NotificationOperationAction $notificationOperationAction;

    public function __construct(NotificationOperationAction $notificationOperationAction){
        $this->notificationOperationAction = $notificationOperationAction;
    }


    //api to get all read notifications
    public function get_all_notifications_read() : JsonResponse
    {
        $data = [];
        try {
            $data = $this->notificationOperationAction->get_all_notifications_read();
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to get all not read notifications
    public function get_all_notifications_not_read() : JsonResponse
    {
        $data = [];
        try {
            $data = $this->notificationOperationAction->get_all_notifications_not_read();
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to make notification read
    public function markAsRead($notification_id) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->notificationOperationAction->markAsRead($notification_id);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }
}
