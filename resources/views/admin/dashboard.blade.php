@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<div class="admin-top">
    <div>
        <h1 class="h-md">Overview</h1>
        <p class="muted">Welcome back, {{ auth()->user()->name }}.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.cars.create') }}">+ Add car</a>
</div>

<div class="kpis">
    <div class="card kpi"><span>Total cars</span><strong>{{ $stats['total'] }}</strong></div>
    <div class="card kpi"><span>Available</span><strong>{{ $stats['available'] }}</strong></div>
    <div class="card kpi"><span>Reserved</span><strong>{{ $stats['reserved'] }}</strong></div>
    <div class="card kpi"><span>Sold</span><strong>{{ $stats['sold'] }}</strong></div>
    <div class="card kpi"><span>Unread messages</span><strong>{{ $stats['unread'] }}</strong></div>
    <div class="card kpi"><span>Stock value (EGP)</span><strong>{{ number_format($stats['value']) }}</strong></div>
</div>

<div class="two-col">
    <section class="card panel">
        <h2 class="h-sm">Cars by brand</h2>
        @php $max = max(1, (int) $byBrand->max('total')); @endphp
        @forelse($byBrand as $row)
            <div class="bar-row">
                <span>{{ $row->brand }}</span>
                <div class="bar"><i class="w-{{ (int) round(($row->total / $max) * 20) * 5 }}"></i></div>
                <strong>{{ $row->total }}</strong>
            </div>
        @empty
            <p class="muted">No data yet.</p>
        @endforelse
    </section>

    <section class="card panel">
        <h2 class="h-sm">Latest messages</h2>
        @forelse($latestInquiries as $inquiry)
            <p>
                <a href="{{ route('admin.inquiries.show', $inquiry) }}"><strong>{{ $inquiry->subject }}</strong></a>
                @if(is_null($inquiry->read_at))<span class="status-pill reserved">New</span>@endif<br>
                <span class="muted">{{ $inquiry->name }} &middot; {{ $inquiry->created_at->diffForHumans() }}</span>
            </p>
        @empty
            <p class="muted">No messages yet.</p>
        @endforelse
    </section>
</div>

<section class="card panel mt">
    <h2 class="h-sm">Recently added cars</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Car</th><th>Price</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($latestCars as $car)
                <tr>
                    <td>{{ $car->title }}</td>
                    <td>EGP {{ number_format($car->price) }}</td>
                    <td><span class="status-pill {{ $car->status }}">{{ ucfirst($car->status) }}</span></td>
                    <td><a class="btn btn-ghost btn-sm" href="{{ route('admin.cars.edit', $car) }}">Edit</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">No cars yet. Add your first one.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
