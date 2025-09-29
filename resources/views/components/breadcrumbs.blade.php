{{-- Breadcrumbs Blade Component --}}
@props(['breadcrumbs'])

@if (!empty($breadcrumbs))
    <nav class="text-xs text-black font-medium pb-6" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1" itemscope itemtype="https://schema.org/BreadcrumbList">
            @foreach ($breadcrumbs as $index => $breadcrumb)
                <li class="flex items-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    @if (!empty($breadcrumb['url']))
                        <a href="{{ $breadcrumb['url'] }}" class="hover:underline text-gray-600" itemprop="item">
                            <span itemprop="name">{{ $breadcrumb['title'] }}</span>
                        </a>
                    @else
                        <span class="text-gray-800" itemprop="name">{{ $breadcrumb['title'] }}</span>
                    @endif
                    <meta itemprop="position" content="{{ $index + 1 }}" />
                    @if (!$loop->last)
                        <span class="mx-1">&gt;</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif

{{-- Breadcrumbs --}}
{{-- @php
    $breadcrumbs = [
    ['title' => 'Home', 'url' => '/'],
    ['title' => 'T-shirt', 'url' => '/categories/footwear'],
    ['title' => 'Premium Comfort']
    ];
    @endphp

    <x-breadcrumbs :breadcrumbs="$breadcrumbs" /> --}}

{{-- End Breadcrumbs --}}
