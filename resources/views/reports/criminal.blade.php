@extends('reports.layout.master')
@section('content')

@php
function safe($value, $fallback = 'Not Specified') {
    if(is_array($value)){
        return !empty($value) ? json_encode($value) : $fallback;
    }
    return !empty($value) ? e($value) : $fallback;
}
@endphp

<img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">

<h1>Criminal Record</h1>

<div class="section">

    {{-- Top Level Fields --}}
    <h2>General Information</h2>
    <table>
        <tr><th>Poseidon ID</th><td>{{ safe($record['poseidonId'] ?? null) }}</td>
            <td rowspan="2" style="text-align:center">
                <img 
                    src="{{ $tempImagePath ?? $defaultImagePath }}" 
                    height="150" 
                    alt="{{ $tempImagePath ? 'person image' : 'default image' }}"
                    style="display: block; margin: auto;"
                />
            </td>

        </tr>
        <tr><th>Short Category</th><td>{{ safe($record['shortCat'] ?? null) }}</td></tr>
    </table>

    {{-- Names --}}
    <h2>Names</h2>
    @if(!empty($record['names']))
        <table>
            @foreach($record['names'] as $name)
                @foreach($name as $key => $value)
                    <tr>
                        <th>{{ ucfirst($key) }}</th>
                        <td>{{ safe($value) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    @else
        <p><em>No Names Found</em></p>
    @endif

    {{-- Offender Attributes --}}
    <h2>Offender Attributes</h2>
    @if(!empty($record['offenderAttributes']))
        @foreach($record['offenderAttributes'] as $attr)
            <table>
                @foreach($attr as $aKey => $aValue)
                    @if(is_array($aValue))
                        <tr>
                            <th>{{ ucfirst($aKey) }}</th>
                            <td>
                                <table>
                                    @foreach($aValue as $nestedKey => $nestedValue)
                                        <tr>
                                           @if($aKey != 'nameHashes')
                                                <th>{{ ucfirst($nestedKey) }}</th>
                                            @endif
                                            <td>{{ safe($nestedValue) }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <th>{{ ucfirst($aKey) }}</th>
                            <td>{{ safe($aValue) }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @endforeach
    @else
        <p><em>No Offender Attributes Found</em></p>
    @endif

    {{-- Photos --}}
    <h2>Photos</h2>
    @if(!empty($record['photos']))
        @foreach($record['photos'] as $photo)
            <table>
                @foreach($photo as $pKey => $pValue)
                    @if(is_array($pValue))
                        <tr>
                            <th>{{ ucfirst($pKey) }}</th>
                            <td>
                                <table>
                                    @foreach($pValue as $nestedKey => $nestedValue)
                                        <tr>
                                            @if($pKey != 'nameHashes')
                                                <th>{{ ucfirst($nestedKey) }}</th>
                                            @endif
                                            <td>{{ safe($nestedValue) }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <th>{{ ucfirst($pKey) }}</th>
                            <td>{{ safe($pValue) }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @endforeach
    @else
        <p><em>No Photos Found</em></p>
    @endif

    {{-- Addresses --}}
    <h2>Addresses</h2>
    @if(!empty($record['addresses']))
        <table>
            @foreach($record['addresses'] as $addr)
                @foreach($addr as $aKey => $aValue)
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

    {{-- Case Details --}}
    <h2>Case Details</h2>
    @if(!empty($record['caseDetails']))
        @foreach($record['caseDetails'] as $case)
            <table>
                @foreach($case as $cKey => $cValue)
                    <tr>
                        <th>{{ ucfirst($cKey) }}</th>
                        <td>{{ safe($cValue) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    @else
        <p><em>No Case Details Found</em></p>
    @endif

    {{-- Offenses --}}
    <h2>Offenses</h2>
    @if(!empty($record['offenses']))
        @foreach($record['offenses'] as $offense)
            <table>
                @foreach($offense as $oKey => $oValue)
                    @if(is_array($oValue))
                        <tr>
                            <th>{{ ucfirst($oKey) }}</th>
                            <td>
                                <table>
                                    @foreach($oValue as $nestedKey => $nestedValue)
                                        <tr>
                                            @if($oKey != 'offenseDescription' && $oKey != 'photos')
                                                <th>{{ ucfirst($nestedKey) }}</th>
                                            @endif
                                            <td>{{ safe($nestedValue) }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <th>{{ ucfirst($oKey) }}</th>
                            <td>{{ safe($oValue) }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @endforeach
    @else
        <p><em>No Offenses Found</em></p>
    @endif

    {{-- Others --}}
    <h2>Other Information</h2>
    @if(!empty($record['others']))
        @foreach($record['others'] as $other)
            <table>
                @foreach($other as $oKey => $oValue)
                    @if(is_array($oValue))
                        <tr>
                            <th>{{ ucfirst($oKey) }}</th>
                            <td>
                                <table>
                                    @foreach($oValue as $nestedKey => $nestedValue)
                                        <tr>
                                            <th>{{ ucfirst($nestedKey) }}</th>
                                            <td>{{ safe($nestedValue) }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <th>{{ ucfirst($oKey) }}</th>
                            <td>{{ safe($oValue) }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @endforeach
    @else
        <p><em>No Other Information Found</em></p>
    @endif


    

</div>

@endsection