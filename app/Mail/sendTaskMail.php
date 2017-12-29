<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendTaskMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	public $name;
	public $task_detail;
	public $subject;
	public $lead;
	public $leademail;
	
    public function __construct($name, $subject, $task_detail, $lead, $leademail)
    {
        //
		$this->name = $name;
		$this->subject = $subject;
		$this->task_detail = $task_detail;
		$this->lead = $lead;
		$this->leademail = $leademail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->leademail)->subject($this->subject)->view('mail.CreateTask');
    }
}
