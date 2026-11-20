@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<section class="section">
    <div class="container">
        <span class="eyebrow">Shop</span>
        <h1>Cars for sale</h1>

        <form class="card filters" method="GET" action="{{ route('shop') }}">
            <div class="field">
                <label for="q">Search</label>
                <input id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Brand or model">
            </div>
            <div class="field">
                <label for="brand">Brand</label>
                <select id="brand" name="brand">
                    <option value="">All</option>
                    @foreach($brands as $b)
                        <option value="{{ $b }}" @selected(($filters['brand'] ?? '') === $b)>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="body_type">Body</label>
                <select id="body_type" name="body_type">
                    <option value="">All</option>
                    @foreach(\App\Models\Car::BODY_TYPES as $b)
                        <option @selected(($filters['body_type'] ?? '') === $b)>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="governorate">Governorate</label>
                <select id="governorate" name="governorate">
                    <option value="">All</option>
                    @foreach(\App\Models\Car::GOVERNORATES as $g)
                        <option @selected(($filters['governorate'] ?? '') === $g)>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="max_price">Max price (EGP)</label>
                <input id="max_price" type="number" min="0" name="max_price" value="{{ $filters['max_price'] ?? '' }}">
            </div>
            <div class="field">
                <label for="sort">Sort</label>
                <select id="sort" name="sort">
                    @foreach(['newest' => 'Newest', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low', 'year_desc' => 'Year: newest'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filters-actions">
                <button class="btn btn-primary" type="submit">Apply</button>
                <a class="btn btn-ghost" href="{{ route('shop') }}">Reset</a>
            </div>
        </form>

        @if($cars->isEmpty())
            <div class="card empty">
                <h3>No cars match your filters</h3>
                <p>Try removing a filter or <a href="{{ route('shop') }}" class="link-accent">see all cars</a>.</p>
            </div>
        @else
            <p class="muted">{{ $cars->total() }} {{ \Illuminate\Support\Str::plural('car', $cars->total()) }} found</p>
            <div class="grid">
                @foreach($cars as $car)
                    @include('partials.car-card', ['car' => $car])
                @endforeach
            </div>

            @if($cars->hasPages())
                <nav class="pagination" aria-label="Pagination">
                    @if($cars->onFirstPage())
                        <span class="disabled">&laquo;</span>
                    @else
                        <a href="{{ $cars->previousPageUrl() }}" rel="prev">&laquo;</a>
                    @endif
                    @foreach($cars->getUrlRange(1, $cars->lastPage()) as $page => $url)
                        @if($page === $cars->currentPage())
                            <span class="active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($cars->hasMorePages())
                        <a href="{{ $cars->nextPageUrl() }}" rel="next">&raquo;</a>
                    @else
                        <span class="disabled">&raquo;</span>
                    @endif
                </nav>
            @endif
        @endif
    </div>
</section>
@endsection
