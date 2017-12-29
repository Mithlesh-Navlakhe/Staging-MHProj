<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	public $name;
	public $status;
	public $task_detail;
	public $description;
	public $subject;
	public $lead;
	public $comments;
    public function __construct($name, $status, $subject, $task_detail, $description, $lead, $comments)
    {
        //
		$this->name = $name;
		$this->status = $status;
		$this->subject = $subject;
		$this->task_detail = $task_detail;
		$this->description = $description;
		$this->lead = $lead;
		$this->comments = $comments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@admin.com')->subject($this->subject)->view('mail.StatusMail');
    }
}
