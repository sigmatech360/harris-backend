@extends('reports.layout.master')
@section('content')

@php
function safe($value, $fallback = 'N/A') {
    if(is_array($value)){
            return !empty($value) ? json_encode($value) : $fallback;
        }
    return !empty($value) ? e($value) : $fallback;
}
@endphp

<img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">

<h1>OFAC Record</h1>

<div class="section">

    {{-- Record ID --}}
    <h2>Record ID: {{ safe($record['recordId']) }}</h2>

    {{-- Main Section --}}
    <h3>Main Information</h3>
    <table>
        @foreach(($record['main'] ?? []) as $key => $value)
            <tr>
                <th>{{ ucfirst($key) }}</th>
                <td>{{ safe($value) }}</td>
            </tr>
        @endforeach
    </table>

    {{-- Addresses --}}
    <h3>Addresses</h3>
    @if(!empty($record['addresses']))
        <table>
            @foreach($record['addresses'] as $address)
                @foreach($address as $aKey => $aValue)
                    <tr>
                        <th>{{ ucfirst($aKey) }}</th>
                        <td>{{ safe($aValue) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    @else
        <p><em>No Addresses Found</em></p>
    @endif

    {{-- Aliases --}}
    <h3>Aliases</h3>
    @if(!empty($record['aliases']))
        <table>
            @foreach($record['aliases'] as $alias)
                @foreach($alias as $alKey => $alValue)
                    <tr>
                        <th>{{ ucfirst($alKey) }}</th>
                        <td>{{ safe($alValue) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    @else
        <p><em>No Aliases Found</em></p>
    @endif

    {{-- Details --}}
    <h3>Details</h3>
    @if(!empty($record['details']))
        @foreach($record['details'] as $detail)
            <div class="detail-block mb-3 p-2 border rounded">
                <table>
                    @foreach($detail as $dKey => $dValue)
                        <tr>
                            <th>{{ ucfirst($dKey) }}</th>
                            <td>{{ safe($dValue) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    @else
        <p><em>No Details Found</em></p>
    @endif

</div>

@endsection
