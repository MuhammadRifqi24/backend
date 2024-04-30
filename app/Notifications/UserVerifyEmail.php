<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserVerifyEmail extends Notification
{
    use Queueable;

    protected $user;
    protected $datas;

    public function __construct($user, $datas = [])
    {
        $this->user = $user;
        $this->datas = $datas;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('admin@admin.com', 'DeCafe.com')
            ->subject(__('Verifikasi akun Anda'))
            ->greeting('Hai, ' . $this->user->name . ' Teman DeCafe,')
            ->line(__('Terima kasih sudah mendaftar di DeCafe.'))
            ->line(('Silahkan klik tombol di bawah untuk memverifikasi akun Anda.'))
            ->action('Verifikasi Akun Saya', $this->datas['url']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}