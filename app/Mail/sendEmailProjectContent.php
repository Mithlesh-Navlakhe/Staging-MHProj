<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendEmailProjectContent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	public $name;
	public $emailTask;
	public $subject;
    public function __construct($name, $subject, $emailTask)
    {
        //
		$this->name = $name;
		$this->subject = $subject;
		$this->emailTask = $emailTask;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@admin.com',$this->name)->subject($this->subject)->view('mail.sendEmailContent');
    }
}
