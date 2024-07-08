<?php

namespace App\Http\Dtos;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class SendEmailDto extends DataTransferObject
{

    public string $reply_to;

    public string $return_path;

    public string $receiver_name;

    public string $receiver_email;

    public string $subject;

    public string $sender_email;

    public string $sender_name;

    public string $view;

    public ?string $attachment = null;

    public string $code = '';

    public string $message_text = '';

    public string $link = '';

//    public array $data = [
//        'receiver_name' => '',
//        'verification_code' => '',
//        'name' => '',
//        'email' => '',
//        'message_text' => '',
//    ];

    public function __construct($args)
    {
        parent::__construct($args);
    }

    /**
     * @throws UnknownProperties
     */
    public static function fromCollection(Collection $params): self
    {
        return new self([
            'reply_to' => $params->get('reply_to',config('settings.email', 'no-reply@seven.com')),
            'return_path' => $params->get('return_path',config('settings.email','no-reply@seven.com')),
            'receiver_name' => $params->get('receiver_name') ?? "",
            'receiver_email' => $params->get('receiver_email') ?? '',
            'message_text' => $params->get('message_text') ?? '',
            'subject' =>$params->get('subject'),
            'sender_email' =>$params->get('sender_email'),
            'sender_name' => $params->get('sender_name'),
            'view' => $params->get('view'),
            'link' => $params->get('link') ?? '',
            'code' => $params->get('code') ?? '',
            'attachment' => $params->get('attachment'),
        ]);
    }

}
