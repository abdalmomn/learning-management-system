<?php

namespace App\Notifications;

use Illuminate\Support\Facades\URL;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendAskDepositMail extends Notification //implements ShouldQueue
{
    use Queueable;
    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // إنشاء رابط مع توقيع لتنفيذ طلب الإيداع
        $url = URL::signedRoute('wallet.deposit', [
            'user_id' => $this->data['user_id'],
            'amount' => $this->data['amount']
        ]);

        return (new MailMessage)
            ->line($this->data['name'] . ' asked you for deposit money')
            ->line('User Name: ' . $this->data['name'])
            ->line('User id: ' . $this->data['user_id'])
            ->line('Amount: ' . $this->data['amount'])
            ->action('Accept', $url);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'data' => $this->data,
        ];
    }
}
