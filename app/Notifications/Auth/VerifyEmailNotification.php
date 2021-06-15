<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        $url = url('/api/auth/verify-email/' . $notifiable->verification_token);
        return (new MailMessage)
                    
                ->subject('Confirm your account')
                ->greeting('Hello ' . ucwords($notifiable->name))
                ->line('You registered an account at ' .
                    env('APP_NAME') .
                    ', before being able to use your account you need to verify that this is your email address by clicking here.')
                ->action('Confirm Account', url($url))
                ->salutation('Thanks! – The ' . env('APP_NAME') . ' Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'Message',
            'content' => 'Hello ' . $notifiable->name. ' Welcome on board ',
            'time' => now()
        ];
    }
}
