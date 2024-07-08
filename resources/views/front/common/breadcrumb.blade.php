<div class="next-banner d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if(!empty($breadcrumbTitle))
                <div class="bred-title">
                    {{$breadcrumbTitle}}
                </div>
                @endif
                <nav>
                    <ol class="breadcrumb ">
                        @foreach($breadcrumbs as $url => $bc)
                        @if($loop->first)
                        <li class="breadcrumb-item"><a href="{{$url}}">{{$bc['title']}}</a></li>
                        @else
                        <li class="breadcrumb-item"><a href="{{$url}}">{{$bc['title']}}</a></li>
                        @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
