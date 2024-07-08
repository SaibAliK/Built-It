@if (count($errors->messages()) > 0)
<div class="alert alert-danger alert-dismissable text-left alert-danger-admin">
    <ul>
        @foreach($errors->messages() as $key => $message)
        <li>{!! __($message[0]) !!}</li>
        @endforeach
    </ul>
</div>
@endif
@if(session()->has('status'))
<div class="alert alert-success alert-dismissable text-left">
    {!! __(session()->get('status')) !!}
</div>
@endif
@if (session('err'))
<div class="alert alert-danger alert-dismissable">
    {!! __(session('err')) !!}
</div>
@endif

@if($maintenance_mode == 0)
    <div class="alert alert-info">
        <strong>Maintenance Mode is activated!</strong> Your site is down at this moment.
        <a href="{!! url('en/maintenance_mode') !!}" class="btn btn-accent" target="_blank">
            {!! __('Visit Site') !!}
        </a>
    </div>
@endif
