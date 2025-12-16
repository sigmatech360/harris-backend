@extends('reports.layout.master')
@section('content')
 
    <img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">

    <h1>DEA Licenses</h1>

    @php $i = 1; @endphp

    @forelse($records ?? [] as $dea)
        <div class="card mb-3">
            <h3>Record {{ $i }} of {{ count($report['deaRecords']) }}</h3>

            {{-- Basic Identity --}}
            <p><strong>Full Name:</strong> {{ safe($dea['name']['fullName'], 'N/A') }}</p>
            <p><strong>Prefix:</strong> {{ safe($dea['name']['prefix'], 'N/A') }}</p>
            <p><strong>First Name:</strong> {{ safe($dea['name']['firstName'], 'N/A') }}</p>
            <p><strong>Middle Name:</strong> {{ safe($dea['name']['middleName'], 'N/A') }}</p>
            <p><strong>Last Name:</strong> {{ safe($dea['name']['lastName'], 'N/A') }}</p>
            <p><strong>Suffix:</strong> {{ safe($dea['name']['suffix'], 'N/A') }}</p>
            <p><strong>Person ID:</strong> {{ safe($dea['name']['personId'], 'N/A') }}</p>
            <p><strong>Tahoe ID:</strong> {{ safe($dea['name']['tahoeId'], 'N/A') }}</p>

            {{-- Address --}}
            <h4>Address</h4>
            <p>{{ safe($dea['address']['fullAddress'], 'N/A') }}</p>
            <p><strong>City:</strong> {{ safe($dea['address']['city'], 'N/A') }}</p>
            <p><strong>State:</strong> {{ safe($dea['address']['state'], 'N/A') }}</p>
            <p><strong>Zip Code:</strong> {{ safe($dea['address']['zipCode'], 'N/A') }}</p>

            {{-- Mailing Address --}}
            @if(!empty($dea['mailingAddress']['fullAddress']))
                <h4>Mailing Address</h4>
                <p>{{ safe($dea['mailingAddress']['fullAddress'], 'N/A') }}</p>
            @endif

            {{-- License Details --}}
            <h4>License Details</h4>
            <p><strong>DEA Registration Number:</strong> {{ safe($dea['details']['deaRegistrationNumber'], 'N/A') }}</p>
            <p><strong>Business Activity Code:</strong> {{ safe($dea['details']['businessActivityCode'], 'N/A') }}</p>
            <p><strong>Business Activity Sub-Code:</strong> {{ safe($dea['details']['businessActivitySubCode'], 'N/A') }}</p>
            <p><strong>Business Description:</strong> {{ safe($dea['details']['businessDescription'], 'N/A') }}</p>
            <p><strong>Business Name:</strong> {{ safe($dea['details']['businessName'], 'N/A') }}</p>
            <p><strong>Activity:</strong> {{ safe($dea['details']['activity'], 'N/A') }}</p>
            <p><strong>Payment Indicator:</strong> {{ safe($dea['details']['paymentInd'], 'N/A') }}</p>
            <p><strong>Additional Info:</strong> {{ safe($dea['details']['additionalInfo'], 'N/A') }}</p>
            <p><strong>Expiration Date:</strong> {{ safe($dea['details']['expDate'], 'N/A') }}</p>

            {{-- Drug Schedules --}}
            <h4>Drug Schedules</h4>
            <p><strong>Codes:</strong> {{ safe($dea['details']['drugSchedules'], 'N/A') }}</p>

            @if(!empty($dea['details']['mappedDrugSchedules']))
                <ul>
                    @foreach($dea['details']['mappedDrugSchedules'] as $schedule)
                        <li>{{ safe($schedule, 'N/A') }}</li>
                    @endforeach
                </ul>
            @else
                <p>No detailed drug schedules available.</p>
            @endif

            <hr>
        </div>
        @php $i++; @endphp
    @empty
        <p>No DEA License records found.</p>
    @endforelse

@endsection
