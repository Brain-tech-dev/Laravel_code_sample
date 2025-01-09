<input type="hidden" id="filter-page" value="{{ $paginator->currentPage() }}">

@if ($paginator->hasPages())
    <div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @php
                    // $nextPage = false;
                    $nPage = $paginator->currentPage() - 1;
                    $nextPage = $nPage < $paginator->total() ? false: true; 
                @endphp

                @if ($paginator->onFirstPage() && $nPage)
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span class="page-link" aria-hidden="true">&lsaquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" onclick="attachNextPage('{{ $nPage }}')"
                            href="javascript:void(0);" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span
                                class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span
                                        class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link"
                                        onclick="attachNextPage('{{ $page }}')"
                                        href="javascript:void(0);">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @php
                    // $nextPage = false;
                    $nPage = $paginator->currentPage() + 1;
                    $nextPage = $nPage > $paginator->total() ? false: true; 
                @endphp
                @if ($paginator->hasMorePages() && $nextPage)
                    <li class="page-item">
                        <a class="page-link" onclick="attachNextPage('{{ $nPage }}')"
                            href="javascript:void(0);" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link" aria-hidden="true">&rsaquo;</span>
                    </li>
                @endif
            </ul>
        </nav>

    </div>

@endif

@push('scripts')
    <script>
        function attachNextPage(page) {
            document.getElementById('filter-page').value = page;
            ajaxTableData();
        }
    </script>
@endpush
