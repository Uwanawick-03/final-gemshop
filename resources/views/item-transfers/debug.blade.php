@extends('layouts.app')

@section('title', 'Debug Item Transfers')

@section('content')
<div class="container-fluid">
    <h1>Debug Item Transfers</h1>
    
    @php
        $transfers = \App\Models\ItemTransfer::with(['item', 'transferredBy'])->take(5)->get();
    @endphp
    
    <p>Total transfers: {{ $transfers->count() }}</p>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Reference</th>
                <th>Status</th>
                <th>Reason</th>
                <th>Status Color</th>
                <th>Reason Color</th>
                <th>Status Label</th>
                <th>Reason Label</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transfers as $transfer)
                <tr>
                    <td>{{ $transfer->id }}</td>
                    <td>{{ $transfer->reference_number }}</td>
                    <td>{{ $transfer->status }}</td>
                    <td>{{ $transfer->reason }}</td>
                    <td>{{ $transfer->status_color }}</td>
                    <td>{{ $transfer->reason_color }}</td>
                    <td>{{ $transfer->status_label }}</td>
                    <td>{{ $transfer->reason_label }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <h3>Raw HTML Output:</h3>
    @foreach($transfers as $transfer)
        <p><strong>Transfer {{ $transfer->id }}:</strong></p>
        <p>Status: <span class="badge badge-{{ $transfer->status_color }}">{{ $transfer->status_label }}</span></p>
        <p>Reason: <span class="badge badge-{{ $transfer->reason_color }}">{{ $transfer->reason_label }}</span></p>
        <hr>
    @endforeach
</div>
@endsection
