@extends('reports.layout.master')
 @section('content')
 <img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">
  <!-- Marriage Record Template -->
  <h1>MARRIAGE RECORD</h1>
  <div class="section">
    <h2>REPORT SUMMARY</h2>
    <div class="small">
      <strong>Subject Information</strong>
      <table>
          <tr><th>Name & Age</th><td>{{ $summary['name'] }}  ({{ $summary['age'] }})</td></tr>
      </table>
    </div>
  </div>

@foreach($records as $record)
  <div class="section"> 
  <h2>RECORD # {{$loop->iteration}}</h2>
    <table>
      <tr>
        <th>Spouse Name</th>
        <td>{{ $record['spouseFirstName'] }} {{ $record['spouseMiddleName'] }} {{ $record['spouseLastName'] }}</td>
      </tr>
      <tr>
        <th>Spouse Maiden Name</th>
        <td>{{ $record['spouseMaidenName'] ?: 'N/A' }}</td>
      </tr>
      <tr>
        <th>Spouse Age</th>
        <td>{{ $record['spouseAge'] ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Other Spouse Name</th>
        <td>{{ $record['otherSpouseFullName'] }}</td>
      </tr>
      <tr>
        <th>Other Spouse Age</th>
        <td>{{ $record['otherSpouseAge'] ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Marriage Date</th>
        <td>{{ $record['marriageDate'] }}</td>
      </tr>
      <tr>
        <th>County, State</th>
        <td>{{ $record['county'] }}, {{ $record['state'] }}</td>
      </tr>
      <tr>
        <th>Certificate No.</th>
        <td>{{ $record['certificatNo'] }}</td>
      </tr>
      <tr>
        <th>File ID</th>
        <td>{{ $record['fileId'] }}</td>
      </tr>
    </table>
  </div>
@endforeach

@endsection
