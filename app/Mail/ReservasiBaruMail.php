<?php

namespace App\Mail;

use App\Models\Reservasi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservasiBaruMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reservasi;

    public function __construct(Reservasi $reservasi)
    {
        $this->reservasi = $reservasi;
    }

    public function build()
    {
        return $this->subject('Reservasi Baru AutoNexa')
                    ->markdown('emails.reservasi-baru');
    }
}