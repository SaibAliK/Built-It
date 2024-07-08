@if(isset($bag))
    @error($input, $bag)
    <span class="help-block error">
    <small class="text-danger  gothic-normel error" >{{ $message  }}</small>
</span>
    @enderror
@else
    @error($input)
    <span class="help-block error">
    <small class="text-danger  gothic-normel error" >{{ $message  }}</small>
</span>
    @enderror
@endif
