@extends('emails.admin.layouts.app')

@include('emails.admin.layouts.header', ['mailTitle' => 'Password Reset Link'])

@section('mail_body')
<table border='0' cellpadding='0' cellspacing='0' style="width: 100%;">
    <tr>
        <td style="width: 100%; padding:0 50px 28px;background: #ebebeb; box-sizing: border-box;">
            <div style="text-align: center;margin-bottom: 40px;">
{{--                <a href="{!! route('admin.dashboard.index') !!}"><img  style="height: 90px " src=" {!! url(config('settings.email_header')) !!} " border="0" alt=""></a>--}}
            </div>
            <span style="font-size: 22px;margin-bottom: 15px; display: block; color: #7d7d7d;">Hi, </span>
            <span style="color: #7d7d7d; font-size: 20px; display: block;">Forgot Password Request</span>
            <p style="font-size: 12px;line-height: 2; color: #7d7d7d;">You are receiving this email because we received a password reset request for your account.</p>
            <a href="{!! route('admin.auth.forgot-password.show-reset-form', $token) !!}" style="width: 200px; height: 20px; background: #20658E; padding:10px 0; display: block; color: #fff; text-align: center; text-decoration:none; font-size: 14px; border-radius: 5px;">Set New Password</a>
            <p style="font-size: 12px;line-height: 2; color: #7d7d7d;">If you did not request a password reset, no further action is required.</p>
            {{--<span style="color: #7d7d7d; font-size: 18px; display: block;padding:15px 0 5px;">Dr. Sawsan</span>--}}
        </td>
    </tr>
</table>
@endsection
