{{-- ترقيم صفحات بالعربية ومتوافق مع الاتجاه من اليمين لليسار --}}
<div class="fr-pagination">
    <div class="text-muted">
        @if ($paginator->total() > 0)
            عرض <b class="text-dark">{{ $paginator->firstItem() }}</b> إلى <b class="text-dark">{{ $paginator->lastItem() }}</b>
            من أصل <b class="text-dark">{{ $paginator->total() }}</b> سجل
        @endif
    </div>

    @if ($paginator->hasPages())
        <nav aria-label="ترقيم الصفحات">
            <ul class="pagination">
                {{-- الأولى + السابق --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled"><span class="page-link" title="الصفحة الأولى"><i class="la la-angle-double-right"></i></span></li>
                    <li class="page-item disabled"><span class="page-link" title="السابق"><i class="la la-angle-right"></i></span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}" title="الصفحة الأولى"><i class="la la-angle-double-right"></i></a></li>
                    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" title="السابق"><i class="la la-angle-right"></i></a></li>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- التالي + الأخيرة --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" title="التالي"><i class="la la-angle-left"></i></a></li>
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" title="الصفحة الأخيرة"><i class="la la-angle-double-left"></i></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link" title="التالي"><i class="la la-angle-left"></i></span></li>
                    <li class="page-item disabled"><span class="page-link" title="الصفحة الأخيرة"><i class="la la-angle-double-left"></i></span></li>
                @endif
            </ul>
        </nav>
    @endif
</div>
