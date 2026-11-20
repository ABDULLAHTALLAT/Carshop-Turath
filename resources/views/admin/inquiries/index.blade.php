@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="admin-top"><h1 class="h-md">Messages</h1></div>

<div class="card panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>From</th><th>Subject</th><th>Car</th><th>Received</th><th></th></tr></thead>
            <tbody>
            @forelse($inquiries as $inquiry)
                <tr>
                    <td>{{ $inquiry->name }}<br><span class="muted">{{ $inquiry->email }}</span></td>
                    <td>@if(is_null($inquiry->read_at))<span class="status-pill reserved">New</span> @endif{{ $inquiry->subject }}</td>
                    <td>{{ $inquiry->car?->title ?? '-' }}</td>
                    <td>{{ $inquiry->created_at->diffForHumans() }}</td>
                    <td><a class="btn btn-ghost btn-sm" href="{{ route('admin.inquiries.show', $inquiry) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">No messages yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($inquiries->hasPages())
        <div class="mt">{{ $inquiries->links('pagination::simple-default') }}</div>
    @endif
</div>
@endsection
