@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 text-gray-300 bg-gray-100 rounded-xl cursor-not-allowed text-xs font-black uppercase tracking-widest">
                Prev
            </span>
        @else
            <button wire:click="previousPage" class="px-4 py-2 bg-white border border-gray-200 text-orbita-blue hover:bg-orbita-gold hover:text-white transition-all duration-300 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm">
                Prev
            </button>
        @endif

        {{-- Pagination Elements --}}
        <div class="hidden md:flex gap-2">
            @foreach ($elements as $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 bg-orbita-blue text-white rounded-xl text-xs font-black shadow-lg shadow-orbita-blue/20">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="px-4 py-2 bg-white border border-gray-200 text-gray-500 hover:border-orbita-gold hover:text-orbita-gold transition rounded-xl text-xs font-black">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" class="px-4 py-2 bg-white border border-gray-200 text-orbita-blue hover:bg-orbita-gold hover:text-white transition-all duration-300 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm">
                Next
            </button>
        @else
            <span class="px-4 py-2 text-gray-300 bg-gray-100 rounded-xl cursor-not-allowed text-xs font-black uppercase tracking-widest">
                Next
            </span>
        @endif
    </nav>
@endif