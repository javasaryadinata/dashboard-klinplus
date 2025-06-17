<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pelanggan;
    public $order;
    public $detailLayanan;
    public function __construct($pelanggan, $order, $detailLayanan)
    {
        $this->pelanggan = $pelanggan;
        $this->order = $order;
        $this->detailLayanan = $detailLayanan;
    }

    public function build()
    {
        return $this->view('emails.invoice');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
