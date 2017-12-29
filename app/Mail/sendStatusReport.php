<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendStatusReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	public $name;
	public $todayTask;
	public $subject;
	public $replyToEmail;
    public function __construct($name, $subject, $todayTask, $replyToEmail)
    {
        //
		$this->name = $name;
		$this->subject = $subject;
		$this->todayTask = $todayTask;
		$this->replyToEmail = $replyToEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@admin.com',$this->name)->replyTo($this->replyToEmail,$this->name)->subject($this->subject)->view('mail.StatusReport');
    }
}
