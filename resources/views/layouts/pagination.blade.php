<!-- Custom Pagination -->
<style>
    .pagination-btn:hover:not(:disabled) {
        background-color: wheat !important;
        transform: scale(1.05);
    }
</style>

<div class="pagination-wrapper" style="margin-top: 20px;">

    @if ($data->isNotEmpty())
        <div style="margin-bottom: 10px; padding-top: 5px;">
            Showing {{ $data->firstItem() }}–{{ $data->lastItem() }} of {{ $data->total() }}
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px;">

            <!-- Previous Button -->
            @if ($data->onFirstPage())
                <button disabled class="pagination-btn"
                    style="padding:6px 12px; border:none; border-radius:5px; background:#ddd; cursor:not-allowed;">
                    «
                </button>
            @else
                <button onclick="window.location='{{ $data->previousPageUrl() }}'" class="pagination-btn"
                    style="padding:6px 12px; border:none; border-radius:5px; background:#f0f0f0; cursor:pointer;">
                    «
                </button>
            @endif


            <!-- Page Numbers -->
            @php
                $elements = $data->links()->elements;
            @endphp

            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <button onclick="window.location='{{ $url }}'" class="pagination-btn"
                            style="
                                    padding:6px 12px;
                                    border:none;
                                    border-radius:5px;
                                    background:{{ $data->currentPage() == $page ? 'var(--gold)' : '#f0f0f0' }};
                                    color:{{ $data->currentPage() == $page ? '#fff' : '#000' }};
                                    cursor:pointer;
                                ">
                            {{ $page }}
                        </button>
                    @endforeach
                @endif
            @endforeach


            <!-- Next Button -->
            @if ($data->hasMorePages())
                <button onclick="window.location='{{ $data->nextPageUrl() }}'" class="pagination-btn"
                    style="padding:6px 12px; border:none; border-radius:5px; background:#f0f0f0; cursor:pointer;">
                    »
                </button>
            @else
                <button disabled class="pagination-btn"
                    style="padding:6px 12px; border:none; border-radius:5px; background:#ddd; cursor:not-allowed;">
                    »
                </button>
            @endif

        </div>
    @endif

</div>
