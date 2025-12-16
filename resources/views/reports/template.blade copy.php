 @extends('reports.layout.master')
 @section('content')
 @php
function safe($value, $fallback = 'N/A') {
    return $value !== null && $value !== '' ? e($value) : $fallback;
}
@endphp

    @php use Illuminate\Support\Str; @endphp
    <img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">
    <h1>COMPREHENSIVE REPORT<br>MR {{ $summary['Name'] }}</h1>

    <div class="section page-break">
      <h2>REPORT SUMMARY</h2>
      <div class="small">
        <strong>Subject Information</strong>
        <table>
          <tr><th>Name & Gender</th><td>{{ $summary['Name'] }} ({{ $summary['Gender'] }})</td></tr>
          <tr><th>SSN</th><td>{{ $summary['SSN'] }}</td></tr>
          <tr><th>DOB (Age)</th><td>{{ $summary['DOB'] }} (Age {{ $summary['Age'] }})</td></tr>
        </table>

        <strong>Indicators</strong>
        <table>
          @php $labels = array_column($results, 'label'); @endphp
         
          @foreach($labels as $label)
            <tr><th>{{ $label }}</th><td>Included</td></tr>
          @endforeach
        </table>
      </div>
    </div>

    <div class="section page-break">
      <h2>TABLE OF CONTENTS</h2>
      @forelse($results as $section)
        <div class="toc-item">{{ $section['label'] ?? 'Unknown Section' }}</div>
      @empty
      <div class="toc-item">Data not found</div>
      @endforelse
    </div>

@php $data = $results[0]; @endphp
<div class="report">
    <h2>Comprehensive Report</h2>

   {{--<h2>Metadata</h2>
    <ul>
        <li><strong>Request ID:</strong> {{ safe($data['requestId']) }}</li>
        <li><strong>Request Type:</strong> {{ safe($data['requestType']) }}</li>
        <li><strong>Request Time:</strong> {{ safe($data['requestTime']) }}</li>
        <li><strong>Total Execution Time (ms):</strong> {{ safe($data['totalRequestExecutionTimeMs']) }}</li>
    </ul> 

    <h2>Summary Counts</h2>
    @if (!empty($data['counts']))
        <ul>
            @foreach ($data['counts'] as $key => $count)
                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ safe($count) }}</li>
            @endforeach
        </ul>
    @else
        <p>No data found.</p>
    @endif--}}

    <h2>Persons</h2>
    @php $person = $data; @endphp
        <ul>
            <li><strong>Full Name:</strong> {{ safe($person['name']['firstName']) }} {{ safe($person['name']['middleName']) }} {{ safe($person['name']['lastName']) }}</li>
            <li><strong>Age:</strong> {{ safe($person['age']) }}</li>
            <li><strong>Is Deceased:</strong> {{ safe($person['deathRecords']['isDeceased'] ? 'Yes' : 'No') }}</li>
        </ul>

        <h2>AKAs</h2>
        @forelse($person['akas'] ?? [] as $aka)
            <ul>
                <li><strong>Name:</strong> {{ safe($aka['firstName']) }} {{ safe($aka['middleName']) }} {{ safe($aka['lastName']) }}</li>
                <li><strong>Raw Names:</strong> {{ implode(', ', $aka['rawNames'] ?? []) }}</li>
            </ul>
        @empty
            <p>No data found.</p>
        @endforelse

        <h2>Addresses</h2>
        @forelse($person['addresses'] ?? [] as $address)
            <h3>Record #{{ $loop->iteration }}</h3>
            <ul>
                <li><strong>Full Address:</strong> {{ safe($address['fullAddress']) }}</li>
                <li><strong>City:</strong> {{ safe($address['city']) }}</li>
                <li><strong>State:</strong> {{ safe($address['state']) }}</li>
                <li><strong>Zip:</strong> {{ safe($address['zip']) }}</li>
                <li><strong>Reported From:</strong> {{ safe($address['firstReportedDate']) }} - {{ safe($address['lastReportedDate']) }}</li>
            </ul>

            <h3>Phone Numbers at this Address</h3>
            @forelse($address['phoneNumbers'] ?? [] as $phone)
                <li>{{ safe($phone) }}</li>
            @empty
                <p>No phone data found for this address.</p>
            @endforelse
        @empty
            <p>No address data found.</p>
        @endforelse
   

    <h2>Phone Numbers</h2>
    @forelse($data['phoneNumbers'] ?? [] as $phone)
        <h3>Record #{{ $loop->iteration }}</h3>
        <ul>
            <li><strong>Phone Number:</strong> {{ safe($phone['phoneNumber']) }}</li>
            <li><strong>Type:</strong> {{ safe($phone['phoneType']) }}</li>
            <li><strong>Location:</strong> {{ safe($phone['location']) }}</li>
            <li><strong>Company:</strong> {{ safe($phone['company']) }}</li>
            <li><strong>Is Connected:</strong> {{ safe($phone['isConnected'] ? 'Yes' : 'No') }}</li>
        </ul>
    @empty
        <p>No phone numbers found.</p>
    @endforelse

    <h2>Email Addresses</h2>
    @forelse($data['emailAddresses'] ?? [] as $email)
        <li>{{ safe($email['emailAddress']) }}</li>
    @empty
        <p>No email addresses found.</p>
    @endforelse

    <h2>Relatives</h2>
    @forelse($data['relativesSummary'] ?? [] as $relative)
        <h3>Record #{{ $loop->iteration }}</h3>
        <ul>
            <li><strong>Name:</strong> {{ safe($relative['firstName']) }} {{ safe($relative['middleName']) }} {{ safe($relative['lastName']) }}</li>
            <li><strong>Type:</strong> {{ safe($relative['relativeType']) }}</li>
            <li><strong>DOB:</strong> {{ safe($relative['dob']) }}</li>
        </ul>
    @empty
        <p>No relatives found.</p>
    @endforelse

    <h2>Associates</h2>
    @forelse($data['associatesSummary'] ?? [] as $associate)
        <h3>Record #{{ $loop->iteration }}</h3>
        <ul>
            <li><strong>Name:</strong> {{ safe($associate['firstName']) }} {{ safe($associate['middleName']) }} {{ safe($associate['lastName']) }}</li>
            <li><strong>DOB:</strong> {{ safe($associate['dob']) }}</li>
        </ul>
    @empty
        <p>No associates found.</p>
    @endforelse

    <h2>Indicators</h2>
    @if (!empty($data['indicators']))
        <ul>
            @foreach ($data['indicators'] as $key => $val)
                <li><strong>{{ ucfirst(str_replace('has', '', $key)) }}:</strong> {{ $val ? 'Yes' : 'No' }}</li>
            @endforeach
        </ul>
    @else
        <p>No indicators data found.</p>
    @endif
</div>

@endsection