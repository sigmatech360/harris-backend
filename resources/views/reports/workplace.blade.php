@extends('reports.layout.master')
@section('content')

<img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">
<h1>WORKPLACE RECORD</h1>

@foreach($records as $index => $record)
    <h3> Record # {{ $loop->iteration}}</h3>
  <div class="section page-break">
    <h2>Basic Information</h2>
    <table>
      <tr><th>Full Name</th><td>{{ $record['fullName'] ?? 'N/A' }}</td></tr>
      <tr><th>First Name</th><td>{{ $record['firstName'] ?? 'N/A' }}</td></tr>
      <tr><th>Last Name</th><td>{{ $record['lastName'] ?? 'N/A' }}</td></tr>
      {{--<tr><th>Social Profile</th>
        <td>
          @foreach($record['personUrls'] ?? [] as $url)
            <a href="{{ $url }}">{{ $url }}</a><br>
          @endforeach
        </td>
      </tr>--}}
    </table>

    @foreach($record['currentEmployment'] ?? [] as $emp)
      <h2>Current Employment</h2>
      <table>
        <tr><th>Employer</th><td>{{ $emp['employer'] ?? 'N/A' }}</td></tr>
        <tr><th>Job Title</th><td>{{ $emp['jobTitle'] ?? 'N/A' }}</td></tr>
        <tr><th>Department</th><td>{{ $emp['department'] ?? 'N/A' }}</td></tr>
        <tr><th>Level</th><td>{{ $emp['level'] ?? 'N/A' }}</td></tr>
        <tr><th>Location</th><td>{{ $emp['city'] ?? '' }}, {{ $emp['state'] ?? '' }} {{ $emp['zip'] ?? '' }}</td></tr>
      </table>
    @endforeach
  </div>
@endforeach
@endsection