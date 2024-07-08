<div class="status">{{__('Status:')}}
    @if( $order->status == "pending")
        <span class="">{{__('Pending')}}</span>
    @elseif( $order->status == "completed")
        <span class="">{{__('Completed')}}</span>
    @elseif( $order->status == "accepted")
        <span class="">{{__('Accepted')}}</span>
    @elseif( $order->status == "in-progress")
        <span class="">{{__('In Progress')}}</span>
    @elseif( $order->status == "cancelled")
        <span class="">{{__('Cancelled')}}</span>
    @endif
</div>
