@extends('reports.layout.master')
@section('content')
@php
    function safe($value,$fallback='N/A') {
        if(is_array($value)){
            return !empty($value) ? json_encode($value) : $fallback;
        }
        return !empty($value) ? e($value) : $fallback;
    }
@endphp
<img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">

<h1>Professional License Report</h1>

@foreach($records as $license)
  <h3>RECORD # {{ $loop->iteration }}</h3>

  <div class="section">
    <table>
      <tr><th>License ID</th><td>{{ safe($license['proLicensesId']) }}</td></tr>
      <tr><th>License Number</th><td>{{ safe($license['licenseNumber']) }}</td></tr>
      <tr><th>License Status</th><td>{{ safe($license['licenseStatus']) }}</td></tr>
      <tr><th>License Type</th><td>{{ safe($license['licenseType']) }}</td></tr>
      <tr><th>License Category</th><td>{{ safe($license['licenseCategory']) }}</td></tr>
      <tr><th>License State</th><td>{{ safe($license['licenseState']) }}</td></tr>
      <tr><th>Original Issue Date</th><td>{{ safe($license['originalIssueDate']) }}</td></tr>
      <tr><th>Issue Date</th><td>{{ safe($license['issueDate']) }}</td></tr>
      <tr><th>Expire Date</th><td>{{ safe($license['expireDate']) }}</td></tr>
    </table>

    <h2>Associated Names</h2>
    @if(!empty($license['names']))
    <table>
      <thead>
        <tr>
          <th>Full Name</th><th>Gender</th><th>Tahoe ID</th><th>Individual ID</th>
        </tr>
      </thead>
      <tbody>
        @foreach($license['names'] as $person)
        <tr>
          <td>{{ safe($person['fullName']) }}</td>
          <td>{{ safe($person['gender']) }}</td>
          <td>{{ safe($person['tahoeId']) }}</td>
          <td>{{ safe($person['individualId']) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
      <p><em>No associated names found.</em></p>
    @endif

    <h2>Addresses</h2>
    @if(!empty($license['addresses']))
    <table>
      <thead>
        <tr><th>Full Address</th><th>City</th><th>State</th><th>Zip</th><th>Lat</th><th>Lng</th></tr>
      </thead>
      <tbody>
        @foreach($license['addresses'] as $address)
        <tr>
          <td>{{ safe($address['houseNumber']) }} {{ safe($address['streetName']) }} {{ safe($address['streetType']) }}</td>
          <td>{{ safe($address['city']) }}</td>
          <td>{{ safe($address['state']) }}</td>
          <td>{{ safe($address['zipCode']) }}</td>
          <td>{{ safe($address['latitude']) }}</td>
          <td>{{ safe($address['longitude']) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
      <p><em>No address data available.</em></p>
    @endif

    <h2>Phone Numbers</h2>
    @if(!empty($license['phones']))
      <ul>
        @foreach($license['phones'] as $phone)
          <li>{{ safe($phone) }}</li>
        @endforeach
      </ul>
    @else
      <p><em>No phone numbers found.</em></p>
    @endif

    <h2>License History</h2>
    @if(!empty($license['attributes']))
    <table>
      <thead>
        <tr><th>License Number</th><th>Status</th><th>Issue Date</th><th>Expire Date</th><th>Category</th><th>Type</th><th>State</th></tr>
      </thead>
      <tbody>
        @foreach($license['attributes'] as $attr)
        <tr>
          <td>{{ safe($attr['licenseNumber']) }}</td>
          <td>{{ safe($attr['licenseStatus']) }}</td>
          <td>{{ safe($attr['originalIssuedate']) }}</td>
          <td>{{ safe($attr['maxExpiredate']) }}</td>
          <td>{{ safe($attr['licenseCategory']) }}</td>
          <td>{{ safe($attr['licenseType']) }}</td>
          <td>{{ safe($attr['licenseState']) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
      <p><em>No license attributes found.</em></p>
    @endif
  </div>
@endforeach

@endsection
