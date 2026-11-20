@extends('layouts.admin')

@section('title', 'Cars')

@section('content')
<div class="admin-top">
    <h1 class="h-md">Cars</h1>
    <a class="btn btn-primary" href="{{ route('admin.cars.create') }}">+ Add car</a>
</div>

<form method="GET" class="mb">
    <label class="muted" for="status">Filter by status</label>
    <select id="status" name="status" data-autosubmit class="select-auto">
        <option value="">All</option>
        @foreach(\App\Models\Car::STATUSES as $s)
            <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
</form>

<div class="card panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th></th><th>Car</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($cars as $car)
                <tr>
                    <td>@if($car->cover_url)<img class="thumb" src="{{ $car->cover_url }}" alt="">@endif</td>
                    <td><strong>{{ $car->title }}</strong><br><span class="muted">{{ $car->year }} &middot; {{ $car->governorate }}</span></td>
                    <td>EGP {{ number_format($car->price) }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.cars.status', $car) }}">
                            @csrf @method('PATCH')
                            <select name="status" data-autosubmit aria-label="Status for {{ $car->title }}">
                                @foreach(\App\Models\Car::STATUSES as $s)
                                    <option value="{{ $s }}" @selected($car->status === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <div class="row-actions">
                            <a class="btn btn-ghost btn-sm" href="{{ route('shop.show', $car) }}" target="_blank" rel="noopener">View</a>
                            <a class="btn btn-ghost btn-sm" href="{{ route('admin.cars.edit', $car) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.cars.destroy', $car) }}" data-confirm="Delete this car and its photos?">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">No cars found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($cars->hasPages())
        <div class="mt">{{ $cars->links('pagination::simple-default') }}</div>
    @endif
</div>
@endsection
