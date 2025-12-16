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

<h1>Vehicle Registration Records</h1>

@forelse($records as $record)
    <h2>Record #{{ $loop->iteration }}</h2>
    <div class="section">

        {{-- Registrant --}}
        <h3>Registrant</h3>
        <table>
            @foreach(($record['registrant'] ?? []) as $key => $value)
                @if(is_array($value))
                    <tr>
                        <th>{{ ucfirst($key) }}</th>
                        <td>
                            <table>
                                @foreach($value as $subKey => $subValue)
                                    @if(is_array($subValue))
                                        <tr>
                                            <th>{{ ucfirst($subKey) }}</th>
                                            <td>
                                                <table>
                                                    @foreach($subValue as $innerKey => $innerValue)
                                                        <tr>
                                                            <th>{{ ucfirst($innerKey) }}</th>
                                                            <td>{{ safeFn($innerValue) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                        </tr>
                                    @else
                                        <tr><th>{{ ucfirst($subKey) }}</th><td>{{ safeFn($subValue) }}</td></tr>
                                    @endif
                                @endforeach
                            </table>
                        </td>
                    </tr>
                @else
                    <tr><th>{{ ucfirst($key) }}</th><td>{{ safeFn($value) }}</td></tr>
                @endif
            @endforeach
        </table>

        {{-- Registrant History --}}
        <h3>Registrant History</h3>
        @forelse($record['registrantHistory'] ?? [] as $history)
            <div class="registrant-history mb-4 p-2 border rounded">
                <table>
                    @foreach($history as $hKey => $hValue)
                        @if(is_array($hValue))
                            <tr>
                                <th>{{ ucfirst($hKey) }}</th>
                                <td>
                                    <table>
                                        @foreach($hValue as $subKey => $subValue)
                                            @if(is_array($subValue))
                                                <tr>
                                                    <th>{{ ucfirst($subKey) }}</th>
                                                    <td>
                                                        <table>
                                                            @foreach($subValue as $innerKey => $innerValue)
                                                                <tr>
                                                                    <th>{{ ucfirst($innerKey) }}</th>
                                                                    <td>{{ safeFn($innerValue) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <th>{{ ucfirst($subKey) }}</th>
                                                    <td>{{ safeFn($subValue) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <th>{{ ucfirst($hKey) }}</th>
                                <td>{{ safeFn($hValue) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        @empty
            <p><em>No Registrant History Found</em></p>
        @endforelse

    </div>
@empty
    <p><em>No Vehicle Registrations Found</em></p>
@endforelse

@endsection