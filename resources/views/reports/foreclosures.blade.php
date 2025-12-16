@extends('reports.layout.master')
@section('content')

@php
if (!function_exists('safeFn')) {
    function safeFn($value, $fallback = 'N/A') {
        if(is_array($value)){
            return !empty($value) ? json_encode($value) : $fallback;
        }
        return !empty($value) ? e($value) : $fallback;
    }
}
@endphp


<img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">

<h1>Foreclosure Records</h1>

@forelse($records as $record)
    <h2>Record #{{ $loop->iteration }}</h2>
    <div class="section">

        {{-- Top Level Fields --}}
        <table>
            <tr><th>Poseidon ID</th><td>{{ safeFn($record['poseidonId']) }}</td></tr>
            <tr><th>Clip</th><td>{{ safeFn($record['clip']) }}</td></tr>
            <tr><th>Previous Clip</th><td>{{ safeFn($record['previousClip']) }}</td></tr>
        </table>

        {{-- Borrower ETAL --}}
        <h3>Borrower ETAL</h3>
        <table>
            <tr><th>Etal Code</th><td>{{ safeFn($record['borrowerETAL']['etalCode'] ?? null) }}</td></tr>
            <tr><th>Etal Label</th><td>{{ safeFn($record['borrowerETAL']['etalLabel'] ?? null) }}</td></tr>
            <tr><th>Situs Mode</th><td>{{ safeFn($record['borrowerETAL']['situsMode'] ?? null) }}</td></tr>
            <tr><th>Situs Quadrant</th><td>{{ safeFn($record['borrowerETAL']['situsQuadrant'] ?? null) }}</td></tr>
            <tr><th>Deed Code</th><td>{{ safeFn($record['borrowerETAL']['deedCode'] ?? null) }}</td></tr>
            <tr><th>Deed Label</th><td>{{ safeFn($record['borrowerETAL']['deedLabel'] ?? null) }}</td></tr>
        </table>

        {{-- Site Address --}}
        <h3>Site Address</h3>
        <table>
            @foreach(($record['siteAddress'] ?? []) as $k => $v)
                <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
            @endforeach
        </table>

        {{-- Borrowers --}}
        <h3>Borrowers</h3>
        @forelse($record['borrowers'] ?? [] as $borrower)
            <table>
                @foreach($borrower as $k => $v)
                    <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
                @endforeach
            </table>
        @empty
            <p><em>No Borrowers Found</em></p>
        @endforelse

        {{-- Lender --}}
        <h3>Lender</h3>
        <table>
            @foreach(($record['lender'] ?? []) as $k => $v)
                @if(is_array($v))
                    <tr><th>{{ ucfirst($k) }}</th><td>
                        <table>
                            @foreach($v as $subK => $subV)
                                <tr><th>{{ ucfirst($subK) }}</th><td>{{ safeFn($subV) }}</td></tr>
                            @endforeach
                        </table>
                    </td></tr>
                @else
                    <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
                @endif
            @endforeach
        </table>

        {{-- Trustee --}}
        <h3>Trustee</h3>
        <table>
            @foreach(($record['trustee'] ?? []) as $k => $v)
                @if(is_array($v))
                    <tr><th>{{ ucfirst($k) }}</th><td>
                        <table>
                            @foreach($v as $subK => $subV)
                                <tr><th>{{ ucfirst($subK) }}</th><td>{{ safeFn($subV) }}</td></tr>
                            @endforeach
                        </table>
                    </td></tr>
                @else
                    <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
                @endif
            @endforeach
        </table>

        {{-- Property Identification --}}
        <h3>Property Identification</h3>
        <table>
            @foreach(($record['propertyIdentification'] ?? []) as $k => $v)
                <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
            @endforeach
        </table>

        {{-- Transaction Summary --}}
        <h3>Transaction Summary</h3>
        <table>
            @foreach(($record['transactionSummary'] ?? []) as $k => $v)
                <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
            @endforeach
        </table>

        {{-- Preforeclosure Transaction --}}
        <h3>Preforeclosure Transaction</h3>
        <table>
            @foreach(($record['preforeclosureTransaction'] ?? []) as $k => $v)
                <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
            @endforeach
        </table>

        {{-- Auction --}}
        <h3>Auction</h3>
        <table>
            @foreach(($record['auction'] ?? []) as $k => $v)
                <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
            @endforeach
        </table>

        {{-- Subject Transaction --}}
        <h3>Subject Transaction</h3>
        <table>
            @foreach(($record['subjectTransaction'] ?? []) as $k => $v)
                <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
            @endforeach
        </table>

        {{-- Record Actions --}}
        <h3>Record Actions</h3>
        <table>
            @if(!empty($record['recordActions']))
                @foreach(($record['recordActions'] ?? []) as $k => $v)
                    <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
                @endforeach
            @else
                <tr><td colspan="2">N/A</td></tr>
            @endif
        </table>

        {{-- Court Histories --}}
        <h3>Court Histories</h3>
        @forelse($record['courtHistories'] ?? [] as $history)
            <table>
                @foreach($history as $k => $v)
                    <tr><th>{{ ucfirst($k) }}</th><td>{{ safeFn($v) }}</td></tr>
                @endforeach
            </table>
        @empty
            <p><em>No Court Histories Found</em></p>
        @endforelse

    </div>
@empty
    <p><em>No foreclosure records found.</em></p>
@endforelse

@endsection
