<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class FacturaGenerated extends Notification
{
    use Queueable;

    protected $factura;
    protected $pdfPath;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($factura, $pdfPath)
    {
        $this->factura = $factura;
        $this->pdfPath = $pdfPath;
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
        return (new MailMessage)
                    ->subject('Factura Generada')
                    ->greeting('Hola,')
                    ->line('Adjunto encontrarás la factura generada.')
                    ->attach($this->pdfPath, [
                        'as' => 'factura.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->line('Gracias por usar nuestra aplicación!');
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
            //
        ];
    }
}