<!-- <div style="height:40px; background:#20658E; "></div> -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <!-- <tr>
        <td style="width: 100%; padding:28px 50px;background: #20658E; box-sizing: border-box; font-size: 14px; color: #7d7d7d; margin-top: 40px; text-align: center;">
            {{--<div style="margin-bottom: 5px;" style="color: #7d7d7d;">454545454545</div>--}}
            {{--<div><a href="mailto:" style="color: #7d7d7d;">mtesting</a></div>--}}
        </td>
    </tr> -->
    <tr style="background-color:#000; height:170px" >
        <td valign="middle" align="center" style="padding-left:30px;padding-right:30px">
            <a href="{!! Config::get('settings.facebook_url') !!}" target="_blank" style="width:30px;height:26px;margin:0 5px;display:inline-block;"><img src="{{asset('assets/admin/email/fbpng-min.png')}}" alt="facebook" width="24" /></a>
            <a href="{!! Config::get('settings.twitter_url') !!}" target="_blank" style="width:30px;height:26px;margin:0 5px;display:inline-block;"><img src="{{asset('assets/admin/email/twipng-min.png')}}" alt="twitter" width="24" /></a>
            <a href="{!! Config::get('settings.instagram_url') !!}" target="_blank" style="width:30px;height:26px;margin:0 5px;display:inline-block;"><img src="{{asset('assets/admin/email/instpng-min.png')}}" alt="Instagram" width="24" /></a>
{{--            <a href="https://wa.me/{!! Config::get('settings.contact_number')  !!}/?text=contactus" data-text="share" target="_blank" style="width:30px;height:26px;margin:0 5px;display:inline-block;"><img src="{{asset('assets/email/whtspng-min.png')}}" alt="whatsapp" width="24" /></a>--}}
        </td>
    </tr>
</table>
