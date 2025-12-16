@extends('reports.layout.master')
@section('content')

<img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">
<h1>Business Records Report</h1>

  @foreach($records as $index => $record)
    @php
      $business = $record['usCorpFilings'][0] ?? null;
    @endphp

    @if($business)
    <h2>RECORD # {{$loop->iteration}}</h2>
      <div class="section">
        <h2>{{ $business['name'] ?? 'N/A' }}</h2>

        <table>
          <tr><th>Corporate Status</th><td>{{ $business['corpStatus'] ?? 'N/A' }}</td></tr>
          <tr><th>Status Date</th><td>{{ $business['corpStatusDate'] ?? 'N/A' }}</td></tr>
          <tr><th>Corporation Type</th><td>{{ $business['corpType'] ?? 'N/A' }}</td></tr>
          <tr><th>Registry Number</th><td>{{ $business['registryNumber'] ?? 'N/A' }}</td></tr>
          <tr><th>Filing Date</th><td>{{ $business['filingDate'] ?? 'N/A' }}</td></tr>
          <tr><th>Term</th><td>{{ $business['term'] ?? 'N/A' }}</td></tr>
          <tr><th>Filing Type</th><td>{{ $business['filingType'] ?? 'N/A' }}</td></tr>
        </table>

        @if(!empty($business['corpMainAddresses']))
          <h3>Main Addresses</h3>
          <table>
            <thead>
              <tr><th>#</th><th>Full Address</th><th>City</th><th>State</th><th>ZIP</th></tr>
            </thead>
            <tbody>
              @foreach($business['corpMainAddresses'] as $i => $addr)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $addr['fullAddress'] ?? '-' }}</td>
                  <td>{{ $addr['city'] ?? '-' }}</td>
                  <td>{{ $addr['state'] ?? '-' }}</td>
                  <td>{{ $addr['zip'] ?? '-' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif

        @if(!empty($business['officers']))
          <h3>Officers / Agents</h3>
          <table>
            <thead>
              <tr><th>Name</th><th>Title</th><th>Start Date</th><th>Address</th></tr>
            </thead>
            <tbody>
              @foreach($business['officers'] as $officer)
                <tr>
                  <td>{{ $officer['name']['nameRaw'] ?? '-' }}</td>
                  <td>{{ $officer['title'] ?? '-' }}</td>
                  <td>{{ $officer['startDate'] ?? '-' }}</td>
                  <td>{{ $officer['address']['fullAddress'] ?? '-' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
    @endif
  @endforeach



@endsection