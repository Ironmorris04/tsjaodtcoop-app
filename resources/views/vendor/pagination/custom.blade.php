@if ($paginator->hasPages())
    <nav class="custom-pagination">
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item disabled">
                    <span class="pagination-link">
                        <i class="fas fa-chevron-left"></i>
                        <span class="pagination-text">Previous</span>
                    </span>
                </li>
            @else
                <li class="pagination-item">
                    <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-chevron-left"></i>
                        <span class="pagination-text">Previous</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                // Get window of links around current page
                $start = max($paginator->currentPage() - 2, 1);
                $end = min($start + 4, $paginator->lastPage());
                $start = max($end - 4, 1);
            @endphp

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <li class="pagination-item active">
                        <span class="pagination-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="pagination-item">
                        <a class="pagination-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span class="pagination-text">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="pagination-item disabled">
                    <span class="pagination-link">
                        <span class="pagination-text">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>

        {{-- Pagination Info --}}
        <div class="pagination-info">
            <i class="fas fa-info-circle"></i>
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>
    </nav>

    <style>
        .custom-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding: 20px 0;
            border-top: 2px solid #e5e7eb;
        }

        .pagination-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 8px;
        }

        .pagination-item {
            display: inline-block;
        }

        .pagination-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            min-width: 44px;
            justify-content: center;
        }

        .pagination-link:hover {
            background: #f3f4f6;
            border-color: #3b82f6;
            color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .pagination-item.active .pagination-link {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }

        .pagination-item.disabled .pagination-link {
            background: #f9fafb;
            border-color: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .pagination-item.disabled .pagination-link:hover {
            transform: none;
            box-shadow: none;
            background: #f9fafb;
            border-color: #e5e7eb;
            color: #9ca3af;
        }

        .pagination-text {
            font-size: 14px;
        }

        .pagination-link i {
            font-size: 12px;
        }

        .pagination-info {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .pagination-info i {
            color: #3b82f6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .custom-pagination {
                flex-direction: column;
                gap: 16px;
            }

            .pagination-text {
                display: none;
            }

            .pagination-link {
                padding: 10px 12px;
                min-width: 40px;
            }

            .pagination-info {
                font-size: 13px;
            }
        }
    </style>
@endif
