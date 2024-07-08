<?php

namespace App\Jobs;

use App\Http\Dtos\SendEmailDto;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Swift_TransportException;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SendEmailDto $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::send($this->data->view, $this->data->toArray(), function ($message) {
                $message->getHeaders()->addTextHeader('Reply-To', $this->data->reply_to);
                $message->getHeaders()->addTextHeader('Return-Path', $this->data->return_path);
                $message->to($this->data->receiver_email, $this->data->receiver_name);
                $message->subject($this->data->subject);
                $message->from($this->data->sender_email, $this->data->sender_name);
                $message->sender($this->data->sender_email, $this->data->sender_name);
                if (!is_null($this->data->attachment)) {
                    $message->attach($this->data->attachment);
                }
            });
            logger("email sending success", ['msg' => '$test_data']);
        } catch (Swift_TransportException $ex) {    
            logger("email sending failed", ['error' => $ex->getMessage()]);
//            return "spam";
        }

    }
}
