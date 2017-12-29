<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class mailCreateUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $email;
    public $password;
    public function __construct($name, $password, $email)
    {
       $this->name = $name;
       $this->email = $email;
       $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->
        from('raveesh@applauz.in')->
        subject("New User")->view('mail.createUser');
    }
}
