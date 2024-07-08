@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
                <notifications calledfrom="dashboard" currentpagenumber="{{ $page }}"
                               usersettings="{{ auth()->user()->settings }}"
                               v-on:notifications-loaded="showNotifications = true">
                </notifications>
            </div>
            <template v-if="showNotifications" class="mt-5">
                {{ $notifications->appends(request()->query())->links('front.common.pagination') }}
            </template>
        </div>
    </section>


@endsection

@push('scripts')
    <script></script>
@endpush
