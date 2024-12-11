<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacturaMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $factura;
    protected $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($factura, $pdfPath)
    {
        $this->factura = $factura;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Factura Generada')
                    ->view('emails.factura')
                    ->with([
                        'factura' => $this->factura,
                    ])
                    ->attach($this->pdfPath, [
                        'as' => 'factura.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}