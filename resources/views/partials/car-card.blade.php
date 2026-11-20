<article class="card car-card">
    <a class="car-link" href="{{ route('shop.show', $car) }}" aria-label="{{ $car->title }}">
        <div class="car-media">
            @if($car->cover_url)
                <img src="{{ $car->cover_url }}" alt="{{ $car->title }}" loading="lazy">
            @else
                <span class="no-photo">No photo yet</span>
            @endif
            @if($car->status !== 'available')
                <span class="badge {{ $car->status }}">{{ ucfirst($car->status) }}</span>
            @endif
            @if($car->is_featured)
                <span class="badge featured">Featured</span>
            @endif
        </div>
        <div class="car-body">
            <h3>{{ $car->title }}</h3>
            <div class="price">EGP {{ number_format($car->price) }}</div>
            <ul class="chips">
                <li>{{ $car->year }}</li>
                <li>{{ number_format($car->mileage) }} km</li>
                <li>{{ $car->transmission }}</li>
                <li>{{ $car->governorate }}</li>
            </ul>
        </div>
    </a>
</article>
