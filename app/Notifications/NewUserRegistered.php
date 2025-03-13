<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class NewUserRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $notifiable->id]
        );

        return (new MailMessage)
            ->subject('Confirma tu correo electrónico')
            ->greeting('Hola ' . $this->user->name . ' 😊')
            ->line('Gracias por registrarte. Para continuar, verifica tu correo electrónico.')
            ->action('Verificar Email', $verificationUrl)
            ->line('Este enlace expirará en 60 minutos.')
            ->line('Si no creaste una cuenta, ignora este mensaje.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nuevo usuario registrado',
            'details' => 'Bienvenido ' . $this->user->name . ', verifica tu correo electrónico.',
            'url' => route('verification.notice'),
        ];
    }
}
