@extends('reports.layout.master')
@section('content')
 
 <img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">

<h1>Debt Record Report</h1>

@foreach($records as $record)
<h2>RECORD # {{$loop->iteration}}</h2>
  <div class="section">
    <h2>General Information</h2>
    <table>
      <tr><th>Debt Type</th><td>{{ $record['debtType'] ?? '-' }}</td></tr>
      <tr><th>Filing Date</th><td>{{ $record['filingDate'] ?? '-' }}</td></tr>
      <tr><th>Report Date</th><td>{{ $record['reportDate'] ?? '-' }}</td></tr>
    </table>

    <h2>Debtors</h2>
    <table>
      <thead>
        <tr>
          <th>Full Name</th><th>Type</th><th>Address</th>
        </tr>
      </thead>
      <tbody>
        @foreach($record['names'] ?? [] as $name)
          <tr>
            <td>{{ $name['fullName'] ?? '-' }}</td>
            <td>{{ $name['type'] ?? '-' }}</td>
            <td>
              {{ $name['address']['fullAddress'] ?? '-' }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    @if(!empty($record['addresses']))
      <h2>Associated Addresses</h2>
      <table>
        <thead>
          <tr>
            <th>Type</th>
            <th>Address</th>
            <th>County</th>
          </tr>
        </thead>
        <tbody>
          @foreach($record['addresses'] as $addr)
            <tr>
              <td>{{ $addr['type'] ?? '-' }}</td>
              <td>{{ $addr['fullAddress'] ?? '-' }}</td>
              <td>{{ $addr['county'] ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif

    @if(!empty($record['courts']))
      <h2>Court Details</h2>
      <table>
        <thead>
          <tr><th>Name</th><th>Phone</th></tr>
        </thead>
        <tbody>
          @foreach($record['courts'] as $court)
            <tr>
              <td>{{ $court['name'] ?? '-' }}</td>
              <td>{{ $court['phone'] ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif

    @if(!empty($record['caseDetails']))
      <h2>Case Details</h2>
      <table>
        <thead>
          <tr>
            <th>Case Number</th>
            <th>Book/Page</th>
            <th>Amount</th>
            <th>Plaintiff Law Firm</th>
          </tr>
        </thead>
        <tbody>
          @foreach($record['caseDetails'] as $case)
            <tr>
              <td>{{ $case['caseNumber'] ?? '-' }}</td>
              <td>{{ $case['book'] ?? '-' }}/{{ $case['page'] ?? '-' }}</td>
              <td>${{ number_format($case['liabilityAmount'] ?? 0, 2) }}</td>
              <td>{{ $case['plaintiffLawFirm'] ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif

  </div>
@endforeach

@endsection