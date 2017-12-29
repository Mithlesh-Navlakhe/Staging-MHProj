<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendUserReport extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
	public $name;
	public $subject;
	public $totalTimes;
	public $startdate;
	public $enddate;
	
    public function __construct($name, $subject, $totalTimes, $startdate, $enddate)
    {
        $this->name = $name;
		$this->subject = $subject;
		$this->totalTimes = $totalTimes;
		$this->startdate = $startdate;
		$this->enddate = $enddate;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@admin.com')->subject($this->subject)->view('mail.sendUserReport');
    }
}