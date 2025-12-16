 @extends('reports.layout.master')
 @section('content')

 <img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">
 <h1>DOMAIN RECORDS</h1>

@foreach($records as $index => $domain)
<h3>Record # {{ $loop->iteration }}</h3>
  <div class="section page-break">
    <h2>Domain: {{ $domain['domainName'] ?? 'N/A' }}</h2>

    @foreach($domain['contacts'] ?? [] as $contact)
      <table>
        <tr><th>Full Name</th><td>{{ $contact['firstName'] }} {{ $contact['middleName'] }} {{ $contact['lastName'] }}</td></tr>
        <tr><th>Gender</th><td>{{ $contact['gender'] ?? 'N/A' }}</td></tr>
        <tr><th>Email</th><td>{{ implode(', ', $contact['emails'] ?? []) }}</td></tr>
        <tr><th>Phone</th><td>{{ implode(', ', $contact['phones'] ?? []) }}</td></tr>
        <tr><th>Address</th>
          <td>{{ $contact['houseNumber'] }} {{ $contact['streetName'] }} {{ $contact['streetType'] }}, {{ $contact['city'] }}, {{ $contact['state'] }} {{ $contact['zip'] }}</td>
        </tr>
        <tr><th>County</th><td>{{ trim($contact['county']) }}</td></tr>
        <tr><th>Country</th><td>{{ $contact['country'] }}</td></tr>
        <tr><th>Created On</th><td>{{ $contact['dates'][0]['creationDate'] ?? 'N/A' }}</td></tr>
        <tr><th>Last Updated</th><td>{{ $contact['dates'][0]['lastUpdated'] ?? 'N/A' }}</td></tr>
      </table>
    @endforeach
  </div>
@endforeach
@endsection
