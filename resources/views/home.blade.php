@extends('layouts.app')
@section('title', 'Shopping')

@section('content')
    @include('frontend.hero-section')

    @foreach ($homeSections as $section)
        @php $products = $sectionProducts[$section->id] ?? collect(); @endphp
        @if ($section->type === 'new_arrivals')
            @include('frontend.dynamic-new-arrivals', ['section' => $section, 'products' => $products, 'topCategories' => $topCategories])
        @elseif (in_array($section->type, ['mens_fashion', 'womens_fashion', 'kids_fashion']))
            @include('frontend.dynamic-fashion', ['section' => $section, 'products' => $products])
        @endif
        <hr>
    @endforeach
@endsection

