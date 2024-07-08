@if ($paginator->hasPages())
    <div class="row">
        <div class="col-12 text-right mt-1">
            <div class="pagination-ctm ">

                @if (!$paginator->onFirstPage())
                    <div class="arrow-sss-left">
                        <a href="{{ $paginator->previousPageUrl() }}" class="page-arow-l"><i
                                class="fas fa-angle-left"></i></a>
                    </div>
                @endif

                <ul class="my-pagination d-inline-block">
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="item"><a href="javascript:void(0)" class="link">…</a>
                        @endif
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="item"><a class="link active"
                                                        href="javascript:void(0);">{{ $page }}</a></li>
                                @else
                                    <li class="item"><a href={{ $url }} class="link">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </ul>

                @if ($paginator->hasMorePages())
                    <div class="arrow-sss-left-right">
                        <a href="{{ $paginator->nextPageUrl() }}" class="page-arow-l"><i class="fas fa-angle-right"></i></a>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endif






