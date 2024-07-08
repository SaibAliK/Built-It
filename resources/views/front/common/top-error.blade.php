<div class="error_container" {{count($errors) ? '' : ''}}>
    <div class="error_wrapper">
        <ul id="error_list">
            @if(count($errors))
                @foreach($errors->all() as $error)
                    <li class="error">{{$error}}</li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
