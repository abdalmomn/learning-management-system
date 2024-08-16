<?php

namespace App\actions;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationOperationAction
{
    //function to get all read notification
    public function get_all_notifications_read()
    {
        $notifications = Notification::query()
            ->where('notifiable_id', Auth::id())
            ->whereNotNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
        if ($notifications->isNotEmpty()){
            $message = __('strings.Getting all notifications');
            $code = 200;
        }else{
            $message = __('strings.There is no any notification in this time');
            $code = 404;
        }

        return [
            'data' => $notifications,
            'message' => $message,
            'code' => $code
        ];
    }

    //function to get all not read notification
    public function get_all_notifications_not_read()
    {
        $notifications = Notification::query()
            ->where('notifiable_id', Auth::id())
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
        if ($notifications->isNotEmpty()){
            $message = __('strings.Getting all notifications');
            $code = 200;
        }else{
            $message = __('strings.There is no any notification in this time');
            $code = 404;
        }

        return [
            'data' => $notifications,
            'message' => $message,
            'code' => $code
        ];
    }

    //function to make notification read
    public function markAsRead($notification_id)
    {
        $notification = Notification::query()
            ->where('id', $notification_id)
            ->where('notifiable_id', Auth::id())
            ->first();

        if ($notification) {

            Notification::query()
                ->where('id', $notification_id)
                ->where('notifiable_id', Auth::id())
                ->update([
                    'read_at' => now(),
                ]);                $message = __('strings.Notification marked as read');
                $code = 200;
        } else {
                $message = __('strings.Notification not found');
                $code = 404;
        }
        return [
            'data' => [],
            'message' => $message,
            'code' => $code
        ];
    }

}
