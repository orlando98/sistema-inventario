<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class stock_productos extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Productos por agotarse";
    public $productos;

    /**
     * Create a new message instance.
     *
     * @return void
     */


    public function __construct($stock_agotandose)
    {
        $this->productos = $stock_agotandose;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('email.stockproductos');
    }
}
