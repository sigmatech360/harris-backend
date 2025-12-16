@extends('reports.layout.master')
@section('content')

<img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">

<h1>Eviction Record Report</h1>

@foreach($records as  $eviction)
<h3>RECORD # {{$loop->iteration}}</h3>
  <div class="section">

    <table>
      <tr>
        <th>Case Number</th>
        <td>{{ $eviction['evictionDetails'][0]['caseNumber'] ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Filing Date</th>
        <td>{{ $eviction['evictionDetails'][0]['fileDate'] ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Report Date</th>
        <td>{{ $eviction['evictionDetails'][0]['reportDate'] ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Liability Amount</th>
        <td>{{ $eviction['evictionDetails'][0]['liabilityAmount'] ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Court Name</th>
        <td>{{ $eviction['courts'][0]['courtName'] ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Court Phone</th>
        <td>{{ $eviction['courts'][0]['courtPhoneOne'] ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Court Website</th>
        <td>{{ $eviction['courts'][0]['courtUrl'] ?? 'N/A' }}</td>
      </tr>
    </table>

    <h2>Defendants</h2>
    <table>
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Gender</th>
        </tr>
      </thead>
      <tbody>
        @foreach($eviction['defendantNames'] as $defendant)
          <tr>
            <td>{{ $defendant['fullName'] ?? 'N/A' }}</td>
            <td>{{ $defendant['gender'] ?? 'N/A' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <h2>Plaintiffs</h2>
    <table>
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Is Owner</th>
        </tr>
      </thead>
      <tbody>
        @foreach($eviction['plaintiffNames'] as $plaintiff)
          <tr>
            <td>{{ $plaintiff['fullName'] ?? 'N/A' }}</td>
            <td>{{ $plaintiff['isOwner'] ? 'Yes' : 'No' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <h2>Addresses</h2>
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Full Address</th>
        </tr>
      </thead>
      <tbody>
        @foreach($eviction['addresses'] as $address)
          <tr>
            <td>{{ $address['addressType'] ?? 'N/A' }}</td>
            <td>{{ $address['fullAddress'] ?? 'N/A' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endforeach

@endsection