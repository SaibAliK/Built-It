@extends('emails.user.layouts.app')

@section('content')
    <table border='0' cellpadding='0' cellspacing='0' style="width: 100%;">
        <tr>
            <td style="width: 100%; padding:0 50px 28px;background: #fff; box-sizing: border-box;">
                <div style="text-align: center;margin-bottom: 40px;">
                    <a href="{!! url('/') !!}"><img style="height: 90px " src=" {!! url('/') !!} " border="0"
                                                    alt=""></a>
                </div>
                <span style="font-size: 22px;margin-bottom: 15px; display: block; color: #7d7d7d;">Hi,</span>
                <span style="color: #7d7d7d; font-size: 20px; display: block;">Forgot Password Request</span>
                <p style="font-size: 12px;line-height: 2; color: #7d7d7d;">You are receiving this email because we
                    received a password reset request for your account.</p>
                <p style="font-size: 12px;line-height: 2; color: #7d7d7d;">To reset your <a
                        href="{!! url('/') !!}">{!! config('settings.company_name') !!}</a> account password please use
                    <b>{!! $code !!}</b>.</p>
                <p style="font-size: 12px;line-height: 2; color: #7d7d7d;">If you did not request a password reset, no
                    further action is required.</p>
            </td>
        </tr>
    </table>
@endsection
