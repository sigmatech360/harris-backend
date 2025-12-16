 @extends('reports.layout.master')
 @section('content')
 <img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">
    <h1>PROPERTY RECORD</h1>
    @foreach($records as $index => $record)
    <h3>Record # {{ $loop->iteration }}</h3>
        @php $data = $record['property']['summary'] ?? []; @endphp
        <div class="section page-break">
        <h2>Address</h2>
        <table>
            <tr><th>Full Address</th><td>{{ $data['address']['fullAddress'] ?? 'N/A' }}</td></tr>
            <tr><th>APN</th><td>{{ $data['apn'] ?? 'N/A' }}</td></tr>
        </table>

        <h2>Owners</h2>
        <table>
            <tr><th>Current Owners</th>
            <td>
                @foreach($data['currentOwners'] ?? [] as $owner)
                - {{ $owner['name']['fullName'] ?? 'N/A' }}<br>
                @endforeach
            </td>
            </tr>
            <tr><th>Previous Owners</th>
            <td>
                @foreach($data['previousOwners'] ?? [] as $owner)
                - {{ $owner['name']['fullName'] ?? 'N/A' }}<br>
                @endforeach
            </td>
            </tr>
        </table>

        <h2>Property Details</h2>
        <table>
            <tr><th>Type</th><td>{{ $data['propertyDetails']['type'] ?? 'N/A' }}</td></tr>
            <tr><th>Lot Size</th><td>{{ $data['propertyDetails']['lotSize'] ?? 'N/A' }}</td></tr>
            <tr><th>Living Area</th><td>{{ $data['propertyDetails']['livingArea'] ?? 'N/A' }}</td></tr>
            <tr><th>Year Built</th><td>{{ $data['propertyDetails']['yearBuilt'] ?? 'N/A' }}</td></tr>
            <tr><th>Beds</th><td>{{ $data['propertyDetails']['beds'] ?? 'N/A' }}</td></tr>
            <tr><th>Baths</th><td>{{ $data['propertyDetails']['baths'] ?? 'N/A' }}</td></tr>
        </table>

        <h2>Valuation</h2>
        <table>
            <tr><th>Market Value</th><td>{{ $data['propertyValue']['marketValue'] ?? 'N/A' }}</td></tr>
            <tr><th>Assessed Value</th><td>{{ $data['propertyValue']['assessedValue'] ?? 'N/A' }}</td></tr>
            <tr><th>Tax Amount ({{ $data['propertyValue']['taxYear'] ?? '' }})</th><td>{{ $data['propertyValue']['taxAmount'] ?? 'N/A' }}</td></tr>
        </table>

        <h2>Mailing Address</h2>
        @foreach($data['currentOwnerMetaData']['mailingAddresses'] ?? [] as $addr)
            <table>
                <tr><th>Address Line</th><td>{{ $addr['fullAddress'] ?? 'N/A' }}</td></tr>
                <tr><th>City</th><td>{{ $addr['city'] ?? 'N/A' }}</td></tr>
                <tr><th>State</th><td>{{ $addr['state'] ?? 'N/A' }}</td></tr>
                <tr><th>Zip</th><td>{{ $addr['zipCode'] ?? 'N/A' }}</td></tr>
            </table>
        @endforeach

        </div>
    @endforeach
@endsection
