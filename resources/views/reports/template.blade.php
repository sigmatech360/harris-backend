@extends('reports.layout.master')
<style>
    h4,h2{
        color:#00315f !important;
     }
     h3{
        border:3px solid #00315f !important;
        padding:4px !important;
     }
     section{
        text-align:'left';
     }
</style>
<!--h2=> light backgound color heading-->
<!--h3 blue border heading-->
<!--h4 simple light blue color text heading-->
@section('content')

@php
function safe($value, $fallback = 'Not Specified') {
    if(is_array($value)){
        return !empty($value) ? json_encode($value) : $fallback;
    }
    return !empty($value) ? e($value) : $fallback;
}

function recordCount($array, $label = 'Found', $emptyFallback = 'None Found') {
    return !empty($array) ? count($array) . ' ' . $label : $emptyFallback;
}
@endphp

<img src="{{ public_path('images/logo-virtual-pi.png') }}" class="watermark" alt="Logo">
<div class="report page-1">
    <h1>COMPREHENSIVE REPORT</h1>
    <!-- Report Criteria -->

    
      <!-- Report Summary -->
    <section class="report-summary">
        <h2>REPORT SUMMARY</h2>
            <h4>Subject Information</h4>
            <p><strong>Name:</strong> {{ safe($summary['name']) }}</p>
            <p><strong>DOB:</strong>  {{ safe($report['dob']) }}</p>
            <p><strong>Age:</strong>  {{ safe($summary['age']) }}</p>
    </section>
    
    <!-- All DOBs-->
     <section class="mt-2">
        <h2>All DOBs ({{ recordCount($report['datesOfBirth']) }})</h2>
            <ul>
                @forelse($report['datesOfBirth'] as $dob)
                    <li>DOB:{{$dob['dob']}} Age({{$dob['age']}})</li>
                @empty
                    <p><em>No dobs found.</em></p>
                @endforelse
            </ul>
    </section>
    
    
    <!--aliases-->
    <section class="mt-2">
        <h2>Other Observed Names ({{ recordCount($report['aliases']) }})</h2>
            @forelse($report['aliases'] as $alias)
                <div class="alias-record mb-4 p-3 border rounded">
                        <p><strong>{{ $alias['firstName'] .' '. $alias['middleName'] .' '. $alias['lastName']?? '—' }}</strong> </p>
                </div>
            @empty
                <p><em>No other observed name found.</em></p>
            @endforelse
    </section>

    <!-- Associated Addresses -->
    <section class="addresses">
        <h2>Associated Addresses</h2>
         <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr><th>Full Address</th><th>First Reported</th><th>Last Reported</th></tr>
            </thead>
            <tbody>
                @foreach($report['associatedAddresses'] as $addr)
                <tr>
                    <td>{{ safe($addr['fullAddress']) }}</td>
                    <td>{{ safe($addr['firstReportedDate']) }}</td>
                    <td>{{ safe($addr['lastReportedDate']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <!-- Associated Phone Numbers -->
    <section class="phones-summary">
        <h2>Associated Phone Numbers</h2>
        @if(!empty($report['phoneNumbers']))
         <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr><th>Number</th><th>Company</th><th>From</th><th>To</th></tr>
            </thead>
            <tbody>
                @foreach($report['phoneNumbers'] as $ph)
                <tr>
                    <td>{{ safe($ph['phoneNumber']) }}</td>
                    <td>{{ safe($ph['company']) }}</td>
                    <td>{{ safe($ph['firstReportedDate']) }}</td>
                    <td>{{ safe($ph['lastReportedDate']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
          <p> NO Associated Phone Numbers Found</p>
        @endif
    </section>

    <!-- criminal records -->
    <section class="">
        <h2>Criminal & Traffic Records</h2>
        <p>{{ recordCount($report['criminalRecords'],' record(s) found') }}</p>
    </section>
    

       {{-- Vehicles --}}
<section class="mt-2">
    <h2>Vehicles ({{ recordCount($report['vehicles']) }})</h2>

    @forelse($report['vehicles'] as $veh)
        <div class="vehicle-record mb-4 p-4 border rounded">
            {{-- Primary Description --}}
            <p class="font-semibold text-lg">
                {{ ($veh['year'] ?? '') . ' ' . ($veh['make'] ?? '') . ' ' . ($veh['model'] ?? '') }}
                @if(!empty($veh['bodyStyle']))
                    {{ $veh['bodyStyle'] }}
                @endif
                @if(!empty($veh['vin']))
                    — VIN: {{ $veh['vin'] }}
                @endif
            </p>

            {{-- Basic Details --}}
            <ul class="list-disc list-inside mb-2">
                @if(!empty($veh['stateOfOrigin']))
                    <li><strong>State of Origin:</strong> {{ $veh['stateOfOrigin'] }}</li>
                @endif
                @if(!empty($veh['series']))
                    <li><strong>Series:</strong> {{ $veh['series'] }}</li>
                @endif
                @if(!empty($veh['price']))
                    <li><strong>Price:</strong> {{ $veh['price'] }}</li>
                @endif
                @if(!empty($veh['type']))
                    <li><strong>Type:</strong> {{ $veh['type'] }}</li>
                @endif
                @if(!empty($veh['dataSource']))
                    <li><strong>Data Source:</strong> {{ $veh['dataSource'] }}</li>
                @endif
            </ul>

            {{-- Specs --}}
            @if(!empty($veh['transmission']) || !empty($veh['features']))
                <div class="mb-2">
                    <strong>Specifications:</strong>
                    <ul class="list-disc list-inside">
                        @if(!empty($veh['transmission']))
                            <li><strong>Transmission:</strong> {{ $veh['transmission'] }}</li>
                        @endif
                        @foreach($veh['features'] ?? [] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Registrants --}}
            @if(!empty($veh['registrants']))
                <div class="mb-2">
                    <strong>Registrants:</strong>
                    <ul class="list-disc list-inside">
                        @foreach($veh['registrants'] as $reg)
                            <li>
                                {{ $reg['date'] ?? '—' }}
                                @if(!empty($reg['ssn']))
                                    (SSN: {{ $reg['ssn'] }})
                                @endif
                                @if(!empty($reg['driverLicense']))
                                    — DL#: {{ $reg['driverLicense'] }}
                                @endif
                                @if(!empty($reg['tag']))
                                    — Tag: {{ $reg['tag'] }}
                                @endif
                                @if(!empty($reg['registrationDate']) || !empty($reg['expirationDate']))
                                    — {{ $reg['registrationDate'] ?? '—' }} 
                                    – {{ $reg['expirationDate'] ?? '—' }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Owners --}}
            @if(!empty($veh['owners']))
                <div class="mb-2">
                    <strong>Owners:</strong>
                    <ul class="list-disc list-inside">
                        @foreach($veh['owners'] as $owner)
                            <li>
                                {{ $owner['name'] ?? '—' }}
                                @if(!empty($owner['ssn']))
                                    (SSN: {{ $owner['ssn'] }})
                                @endif
                                @if(!empty($owner['driverLicense']))
                                    — DL#: {{ $owner['driverLicense'] }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Title information --}}
            @if(!empty($veh['titleNumber']) || !empty($veh['titleIssueDates']))
                <p>
                    @if(!empty($veh['titleNumber']))
                        <strong>Title Number:</strong> {{ $veh['titleNumber'] }}
                    @endif
                    @if(!empty($veh['titleIssueDates']))
                        — <strong>Title Issue Dates:</strong> {{ $veh['titleIssueDates'] }}
                    @endif
                </p>
            @endif
        </div>
    @empty
        <p><em>No vehicle records found.</em></p>
    @endforelse
</section>


{{-- People at Work --}}
<section class="mt-2">
    <h2>People at Work ({{ recordCount($report['workRecords']) }})</h2>

    @forelse($report['workRecords'] as $w)
        <div class="work-record mb-4 p-4 border rounded">
            {{-- Header line --}}
            <p class="font-semibold">
                {{ strtoupper($w['name'] ?? ($w['firstName'].' '.$w['lastName'] ?? '—')) }}
            </p>

            {{-- SSN & Business ID --}}
            <p>
                @if(!empty($w['ssn'] ?? $w['ssnMasked']))
                    {{ $w['ssn'] ?? $w['ssnMasked'] }}
                @else
                    —
                @endif

                @if(!empty($w['businessId']))
                    &nbsp;Business ID: {{ $w['businessId'] }}
                @endif
            </p>

            {{-- Confidence & Dates Seen --}}
            <p>
                @if(isset($w['confidenceLevel']))
                    Confidence Level: {{ $w['confidenceLevel'] }}
                @endif

                @if(!empty($w['datesSeen']))
                    Dates Seen: {{ $w['datesSeen'][0] ?? '' }}
                    @if(isset($w['datesSeen'][1])) – {{ $w['datesSeen'][1] }} @endif
                @endif
            </p>

            {{-- Role / Company --}}
            @if(!empty($w['companyName'] ?? $w['company'] ?? $w['employer']))
                <p>
                    {{ strtoupper($w['name'] ?? ($w['firstName'].' '.$w['lastName'] ?? '—')) }}
                    &nbsp;–&nbsp;
                    {{ strtoupper($w['companyName'] ?? $w['company'] ?? $w['employer']) }}
                </p>
            @endif
        </div>
    @empty
        <p><em>No people-at-work records found.</em></p>
    @endforelse
</section>

        <h2>Drivers Licenses</h2>
        <p>{{ recordCount($report['driversLicenses'],'record(s) found') }}</p>

        <h2>Bankruptcy Records</h2>
        <p>{{ recordCount($report['bankruptcyRecords'],'record(s) found') }}</p>
    </section>
</div>

<div class="report">
    <h1>COMPREHENSIVE REPORT</h1>
    <h2>MR {{ safe($summary['name']) }}</h2>

    {{-- Record Counts --}}
    <section>
        <h4>Record Counts</h4>
        <table>
            @foreach($report['counts'] as $key => $val)
                <tr>
                    <td>{{ ucwords(str_replace(['V2','SearchIds','Records','Summary'], ['', '', '', ''], $key)) }}</td>
                    <td>{{ $val }}</td>
                </tr>
            @endforeach
        </table>
    </section>

    {{-- Persons 
    <section>
        <h3>Person(s)</h3>
        @forelse($report['persons'] ?? [] as $p)
            <div class="card">
                <p><strong>{{ safe($p['name']['firstName']) }} {{ safe($p['name']['middleName']) }} {{ safe($p['name']['lastName']) }}</strong></p>
                <p>DOB: {{ safe($p['dob']) }} (Age {{ safe($p['age']) }})</p>
                <p>Opted out: {{ $p['isOptedOut'] ? 'Yes' : 'No' }}</p>
            </div>
        @empty
            <p>No persons found.</p>
        @endforelse
    </section>--}}

   
            {{-- Addresses --}}
<section class="mt-2">
    <h2>Addresses ({{ recordCount($report['associatedAddresses']) }})</h2>

    @forelse($report['associatedAddresses'] as $addr)
        <div class="address-record mb-6 p-4 border rounded">
            {{-- Summary line --}}
            <p class="font-semibold">
                {{ $addr['fullAddress'] ?? '—' }}
                @if(!empty($addr['firstReportedDate']) || !empty($addr['lastReportedDate']))
                    ({{ \Carbon\Carbon::parse($addr['firstReportedDate'])->format('M Y') ?? '' }}
                    – {{ \Carbon\Carbon::parse($addr['lastReportedDate'])->format('M Y') ?? '' }})
                @endif
            </p>

            {{-- Subject info --}}
            @if(!empty($addr['subjectName']))
                <p><strong>Subject Name:</strong> {{ $addr['subjectName'] }}</p>
            @endif

            {{-- Current residents --}}
            @if(!empty($addr['currentResidents']) && is_array($addr['currentResidents']))
                <div>
                    <strong>Current Residents at Address:</strong>
                    <ul class="list-disc ml-6">
                        @foreach($addr['currentResidents'] as $res)
                            <li>
                                {{ $res['fullName'] ?? ($res['firstName'].' '.$res['lastName']) }}
                                &ndash; {{ $res['dob'] ?? '' }} (Age {{ $res['age'] ?? '—' }})
                                &ndash; {{ $res['ssnMasked'] ?? $res['ssn'] ?? '—' }}
                                @if(!empty($res['issuedState']))
                                    issued in {{ $res['issuedState'] }}
                                    between {{ $res['issuedFrom'] ?? '' }}
                                    and {{ $res['issuedTo'] ?? '' }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Neighborhood Profile --}}
            @if(!empty($addr['neighborhoodProfile']))
                <div class="mt-2">
                    <strong>Neighborhood Profile (Census):</strong>
                    <p>
                        Average Age: {{ $addr['neighborhoodProfile']['averageAge'] ?? '—' }},
                        Median Household Income: {{ $addr['neighborhoodProfile']['medianHouseholdIncome'] ?? '—' }},
                        Average Home Value: {{ $addr['neighborhoodProfile']['averageHomeValue'] ?? '—' }},
                        Average Education: {{ $addr['neighborhoodProfile']['averageEducation'] ?? '—' }}
                    </p>
                </div>
            @endif

            {{-- Property Ownership --}}
            @if(!empty($addr['propertyOwnership']))
                <div class="mt-2">
                    <strong>Property Ownership Information for this Address:</strong>
                    <p>{{ $addr['propertyOwnership']['propertyIdentifier'] ?? '' }} – {{ $addr['propertyOwnership']['status'] ?? '' }}</p>

                    @if(!empty($addr['propertyOwnership']['owners']))
                        <p><strong>Owners:</strong></p>
                        <ul class="list-disc ml-6">
                            @foreach($addr['propertyOwnership']['owners'] as $owner)
                                <li>{{ $owner['name'] ?? '—' }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if(!empty($addr['propertyOwnership']['assessment']))
                        <p><strong>Assessment:</strong></p>
                        <ul class="list-disc ml-6">
                            <li>Parcel Number: {{ $addr['propertyOwnership']['assessment']['parcelNumber'] ?? '—' }}</li>
                            <li>Location: {{ $addr['propertyOwnership']['assessment']['location'] ?? '—' }}</li>
                            <li>Assessed Total Value: {{ $addr['propertyOwnership']['assessment']['assessedTotalValue'] ?? '—' }}</li>
                            <li>Assessed Improvement Value: {{ $addr['propertyOwnership']['assessment']['assessedImprovementValue'] ?? '—' }}</li>
                            <li>Market Land Value: {{ $addr['propertyOwnership']['assessment']['marketLandValue'] ?? '—' }}</li>
                            <li>Market Improvement Value: {{ $addr['propertyOwnership']['assessment']['marketImprovementValue'] ?? '—' }}</li>
                            <li>Market Total Value: {{ $addr['propertyOwnership']['assessment']['marketTotalValue'] ?? '—' }}</li>
                            <li>Market Value Year: {{ $addr['propertyOwnership']['assessment']['marketValueYear'] ?? '—' }}</li>
                            <li>Assessed Value Year: {{ $addr['propertyOwnership']['assessment']['assessedValueYear'] ?? '—' }}</li>
                            <li>Tax Year: {{ $addr['propertyOwnership']['assessment']['taxYear'] ?? '—' }}</li>
                            <li>Tax Amount: {{ $addr['propertyOwnership']['assessment']['taxAmount'] ?? '—' }}</li>
                            <li>Owner Occupied: {{ $addr['propertyOwnership']['assessment']['ownerOccupied'] ?? '—' }}</li>
                            <li>Lot Number: {{ $addr['propertyOwnership']['assessment']['lotNumber'] ?? '—' }}</li>
                            <li>Year Built: {{ $addr['propertyOwnership']['assessment']['yearBuilt'] ?? '—' }}</li>
                            <li>No of Stories: {{ $addr['propertyOwnership']['assessment']['stories'] ?? '—' }}</li>
                            <li>Land Square Footage: {{ $addr['propertyOwnership']['assessment']['landSquareFootage'] ?? '—' }}</li>
                        </ul>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <p><em>No address records found.</em></p>
    @endforelse
</section>

         @php
            $reportPhoneNumbers = array_filter($report['phoneNumbers'] ?? [], function ($num) {
                return ($num['phoneType'] ?? '') === "LandLine/Services";
            });
                
            $reportMobilePhoneNumbers = array_filter($report['phoneNumbers'] ?? [], function($num){
                return ($num['phoneType'] ?? '') !== "LandLine/Services";
            });
        @endphp     

   {{-- Phone Numbers --}}
<section class="mt-2">
    <h2>Phone Numbers ({{ recordCount($reportPhoneNumbers) }})</h2>

    @forelse($reportPhoneNumbers as $phone)
        <div class="phone-record mb-4 p-4 border rounded">
            {{-- Phone and Type --}}
            <p class="font-semibold">
                {{ $phone['phoneNumber'] ?? '—' }}
                @if(!empty($phone['location']))
                    ({{ $phone['location'] }})
                @endif
                — {{ $phone['phoneType'] ?? '—' }}
            </p>

            {{-- Name / Listing --}}
            @if(!empty($phone['name']))
                <p>
                    <strong>Name:</strong>
                    {{ $phone['name'] }}
                    @if(!empty($phone['listedAs']))
                        (Listed as {{ $phone['listedAs'] }})
                    @endif
                </p>
            @endif

            {{-- Carrier --}}
            @if(!empty($phone['company']))
                <p><strong>Carrier:</strong> {{ $phone['company'] }}</p>
            @endif

            {{-- Dates Seen --}}
            @if(!empty($phone['firstReportedDate']) || !empty($phone['lastReportedDate']))
                <p>
                    <strong>Dates Seen:</strong>
                    {{ optional(\Carbon\Carbon::parse($phone['firstReportedDate']))->format('m/d/Y') ?? '—' }}
                    –
                    {{ optional(\Carbon\Carbon::parse($phone['lastReportedDate']))->format('m/d/Y') ?? '—' }}
                </p>
            @endif
        </div>
    @empty
        <p><em>No phone records found.</em></p>
    @endforelse
</section>

   {{-- Mobile Phone Numbers --}}
<section class="mt-2">
    <h2>Mobile Phone Numbers ({{ recordCount($reportMobilePhoneNumbers) }})</h2>

    @forelse($reportMobilePhoneNumbers as $phone)
        <div class="phone-record mb-4 p-4 border rounded">
            {{-- Phone and Type --}}
            <p class="font-semibold">
                {{ $phone['phoneNumber'] ?? '—' }}
                @if(!empty($phone['location']))
                    ({{ $phone['location'] }})
                @endif
                — {{ $phone['phoneType'] ?? '—' }}
            </p>

            {{-- Name / Listing --}}
            @if(!empty($phone['name']))
                <p>
                    <strong>Name:</strong>
                    {{ $phone['name'] }}
                    @if(!empty($phone['listedAs']))
                        (Listed as {{ $phone['listedAs'] }})
                    @endif
                </p>
            @endif

            {{-- Carrier --}}
            @if(!empty($phone['company']))
                <p><strong>Carrier:</strong> {{ $phone['company'] }}</p>
            @endif

            {{-- Dates Seen --}}
            @if(!empty($phone['firstReportedDate']) || !empty($phone['lastReportedDate']))
                <p>
                    <strong>Dates Seen:</strong>
                    {{ optional(\Carbon\Carbon::parse($phone['firstReportedDate']))->format('m/d/Y') ?? '—' }}
                    –
                    {{ optional(\Carbon\Carbon::parse($phone['lastReportedDate']))->format('m/d/Y') ?? '—' }}
                </p>
            @endif
        </div>
    @empty
        <p><em>No phone records found.</em></p>
    @endforelse
</section>


    {{-- Email Addresses --}}
    <section class="mt-2">
        <h2>Email Addresses</h2>
        @forelse($report['emailAddresses'] ?? [] as $em)
            <p>{{ safe($em['emailAddress']) }} ({{ $em['nonBusiness'] ? 'Personal' : 'Business' }})</p>
        @empty
            <p>No email addresses found.</p>
        @endforelse
    </section>

    {{-- Relatives Section --}}
<section class="mt-2">

    <h2>Relatives Summary</h2>
    @forelse($report['relativesSummary'] as $rel)
        <p>
            {{ 
                trim(
                    ($rel['prefix'] ?? '') . ' ' .
                    ($rel['firstName'] ?? '') . ' ' .
                    ($rel['middleName'] ?? '') . ' ' .
                    ($rel['lastName'] ?? '')
                )
            }}
            (Age {{ $rel['age'] ?? \Carbon\Carbon::parse($rel['dob'])->age }})
            – {{ ucfirst($rel['relativeType']) }} ({{ $rel['relativeLevel'] }})
        </p>
    @empty
        <p><em>No relatives found.</em></p>
    @endforelse

    <h2>Relatives ({{ recordCount($report['relativesDetails']) }})</h2>
    <p>{{ recordCount($report['relativesDetails'] ,'1st Degree Relative Record(s)') }} </p>

    @forelse($report['relativesDetails'] as $rel)
        <div class="relative-detail mb-4 p-2 border rounded">
            <strong>{{ $rel['fullName']}}</strong>
            @if(!empty($rel['lastCohabitation']))
                Date Last Cohabitation: {{ $rel['lastCohabitation'] }}<br>
            @endif
            @if(isset($rel['confidence']))
                Confidence: {{ $rel['confidence'] }}%<br>
            @endif

            {{-- Aliases --}}
            <section class="mt-2">
                
                <h4>Aliases ({{ recordCount($rel['akas']) }})</h4>

                @forelse($rel['akas'] as $alias)
                
                    <div class="alias-record mb-4 p-3 border rounded">
                        <p>
                            <strong>{{ $alias['firstName'] . $alias['middleName'] . $alias['lastName']?? '—' }}</strong>    
                        </p>

                        <p>
                            @if(!empty($alias['dob']))
                                {{ $alias['dob'] }}
                                ({{ $alias['age'] ?? '—' }})
                            @endif
                            @if(!empty($alias['correctness']))
                                &nbsp;({{ $alias['correctness'] }})
                            @endif
                        </p>

                        @if(!empty($alias['ssn']))
                            <p>
                                SSN: {{ $alias['ssn'] }}
                                @if(!empty($alias['issuedState']) || !empty($alias['issuedBetween']))
                                    &nbsp;issued in {{ $alias['issuedState'] ?? '—' }}
                                    @if(!empty($alias['issuedBetween']))
                                        between {{ $alias['issuedBetween'] }}
                                    @endif
                                @endif
                            </p>
                        @endif
                    </div>
                @empty
                    <p><em>No aliases found.</em></p>
                @endforelse
            </section>


           {{-- Addresses --}}
            <h4>Addresses</h4>

            @if(!empty($rel['addresses']))
                <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>Address</th>
                            <th>Residents</th>
                            <th>Phones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rel['addresses'] as $index => $addr)
                            <tr>
                                {{-- Serial Number --}}
                                <td>{{ $index + 1 }}</td>

                                {{-- Address + Date Range + County --}}
                                <td>
                                    {{ $addr['fullAddress'] ?? '—' }}<br>
                                    @if(!empty($addr['firstReportedDate']) || !empty($addr['lastReportedDate']))
                                        ({{ $addr['firstReportedDate'] ?? '—' }} – {{ $addr['lastReportedDate'] ?? 'Present' }})
                                    @endif
                                    @if(!empty($addr['county']))
                                        <br><strong>{{ strtoupper($addr['county']) }}</strong>
                                    @endif
                                </td>

                                {{-- Residents --}}
                                <td>
                                    @php
                                        $neighbors = $addr['neighbors'] ?? [];
                                    @endphp
                                    @forelse($neighbors as $n)
                                        <div style="margin-bottom: 8px;">
                                            {{ $n['fullName'] ?? '—' }}<br>
                                            @if(!empty($n['dob']))
                                                {{ \Carbon\Carbon::parse($n['dob'])->format('m/##/Y') }}
                                                ({{ \Carbon\Carbon::parse($n['dob'])->age }})
                                            @endif
                                            <!-- <br>SSN: {{ rand(100, 999) }}-##-#### -->
                                        </div>
                                    @empty
                                        <em>No residents found.</em>
                                    @endforelse
                                </td>

                                {{-- Phones --}}
                                <td>
                                    @if(!empty($addr['phoneNumbers']))
                                        @foreach($addr['phoneNumbers'] as $phone)
                                            {{ $phone }}<br>
                                        @endforeach
                                    @else
                                        <em>No phones</em>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p><em>No address history found.</em></p>
            @endif

        </div>
    @empty
        <p><em>No detailed relatives data available.</em></p>
    @endforelse
</section>


{{-- Associates Section --}}
<section class="mt-2">
    <h2>Associates ({{ recordCount($report['associatesSummary']) }})</h2>

    {{-- One-line summary list --}}
    @forelse($report['associatesSummary'] as $assoc)
        <p>
            {{ trim(($assoc['prefix'] ?? '') . ' ' . ($assoc['firstName'] ?? '') . ' ' . ($assoc['middleName'] ?? '') . ' ' . ($assoc['lastName'] ?? '')) }}
        </p>
    @empty
        <p><em>No associates found.</em></p>
    @endforelse

    <h3>{{ recordCount($report['associatesDetails'], ' Associate Record(s)') }}</h3>

    @forelse($report['associatesDetails'] as $assoc)
        <div class="associate-detail mb-4 p-3 border rounded">

            {{-- Full name --}}
            <strong>{{ $assoc['name']['firstName'] ?? '' }} {{ $assoc['name']['middleName'] ?? '' }} {{ $assoc['name']['lastName'] ?? '' }}</strong><br>

            {{-- Last cohabitation & confidence --}}
            @if(!empty($assoc['lastCohabitation']))
                Date Last Cohabitation: {{ $assoc['lastCohabitation'] }}<br>
            @endif
            @if(isset($assoc['confidence']))
                Confidence: {{ $assoc['confidence'] }}%<br>
            @endif

            {{-- Aliases --}}
            <h4 class="mt-2">Aliases</h4>
            @php $aliasList = $assoc['akas'] ?? []; @endphp
            @forelse($aliasList as $alias)
                <p>
                    {{ trim(($alias['prefix'] ?? '') . ' ' . ($alias['firstName'] ?? '') . ' ' . ($alias['middleName'] ?? '') . ' ' . ($alias['lastName'] ?? '')) }}
                    @if(!empty($alias['dob']))
                        – {{ $alias['dob'] }} (Age {{ \Carbon\Carbon::parse($alias['dob'])->age }})
                    @endif
                    @if(!empty($alias['deathDate']))
                        – Death: {{ $alias['deathDate'] }}
                    @endif
                </p>
            @empty
                <p><em>No aliases found.</em></p>
            @endforelse

            {{-- Addresses --}}
            <h4 class="mt-2">Addresses</h4>
            @if(!empty($assoc['addresses']))
                 <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>Address</th>
                            <th>Residents</th>
                            <th>Phones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assoc['addresses'] as $i => $addr)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    {{ $addr['fullAddress'] ?? ($addr['houseNumber'].' '.$addr['streetName'].' '.$addr['streetType'].', '.$addr['city'].', '.$addr['state'].' '.$addr['zip']) }}
                                    @if(!empty($addr['firstReportedDate']) || !empty($addr['lastReportedDate']))
                                        <br>({{ $addr['firstReportedDate'] ?? '—' }} – {{ $addr['lastReportedDate'] ?? 'Present' }})
                                    @endif
                                    @if(!empty($addr['county']))
                                        <br><strong>{{ strtoupper($addr['county']) }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @php $neighbors = $addr['neighbors'] ?? []; @endphp
                                    @forelse($neighbors as $res)
                                        <div style="margin-bottom: 6px;">
                                            {{ $res['fullName'] ?? '—' }} –
                                            @if(!empty($res['dob']))
                                                {{ \Carbon\Carbon::parse($res['dob'])->format('m/##/Y') }}
                                                ({{ \Carbon\Carbon::parse($res['dob'])->age }})
                                            @endif
                                            – SSN: {{ rand(100,999) }}-##-####
                                            @if(!empty($res['deathRecords']['isDeceased']) && $res['deathRecords']['isDeceased'])
                                                <br>Death: {{ $res['deathRecords']['deathDate'] ?? '—' }}
                                            @endif
                                        </div>
                                    @empty
                                        <em>No residents found.</em>
                                    @endforelse
                                </td>
                                <td>
                                    @if(!empty($addr['phoneNumbers']))
                                        @foreach($addr['phoneNumbers'] as $ph)
                                            {{ $ph }}<br>
                                        @endforeach
                                    @else
                                        <em>No phones</em>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p><em>No addresses found for this associate.</em></p>
            @endif
        </div>
    @empty
        <p><em>No detailed associates data available.</em></p>
    @endforelse
</section>

   
    {{-- Recent 5 Addresses with Neighbors --}}
    <section>
        <h2>Recent 5 Addresses and Their Neighbors</h2>
        
        @if(!empty($report['fiveAddressWithNeighbors']) && count($report['fiveAddressWithNeighbors']) > 0)
            @foreach($report['fiveAddressWithNeighbors'] as $address => $neighbors)
                <div class="address-block" style="margin-bottom: 30px;">
                    <h3>{{ $loop->iteration.') '.$address }}</h3>
    
                    @if(!empty($neighbors) && count($neighbors) > 0)
                        @foreach($neighbors as $neighbor)
                            <P><strong>Full Name:&nbsp;</strong>{{ safe($neighbor['fullName'] ?? 'N/A') }}</P>
                            <P><strong>Phone:&nbsp;</strong>{{ safe($neighbor['phone'] ?? 'N/A') }}</P>
                            <P><strong>Email:&nbsp;</strong>{{ safe($neighbor['emailAddress'] ?? 'N/A') }}</P>
                            <P><strong>City:&nbsp;</strong>{{ safe($neighbor['city'] ?? 'N/A') }}</P>
                            <P><strong>State:&nbsp;</strong>{{ safe($neighbor['state'] ?? 'N/A') }}</P>
                            <P><strong>ZIP:&nbsp;</strong>{{ safe($neighbor['zip'] ?? 'N/A') }}</P>
                            <P><strong>Full Address:&nbsp;</strong>{{ safe($neighbor['fullAddress'] ?? 'N/A') }}</P>
                            <hr/>
                        @endforeach
                    @else
                        <p>No neighbors found for this address.</p>
                    @endif
                </div>
            @endforeach
        @else
            <p>No address or neighbor data found.</p>
        @endif
    </section>

    

    {{-- Other Addresses with Neighbors --}}
<section>
    <h2>Other Addresses and Their Neighbors</h2>

    @if(!empty($report['otherAddressWithNeighbors']) && count($report['otherAddressWithNeighbors']) > 0)
        @foreach($report['otherAddressWithNeighbors'] as $address => $neighbors)
            <div class="address-block" style="margin-bottom: 30px;">
                <h3>{{ $loop->iteration.') '.$address }}</h3>

                @if(!empty($neighbors) && count($neighbors) > 0)
                    @foreach($neighbors as $neighbor)
                        <P><strong>Full Name:&nbsp;</strong>{{ safe($neighbor['fullName'] ?? 'N/A') }}</P>
                        <P><strong>Phone:&nbsp;</strong>{{ safe($neighbor['phone'] ?? 'N/A') }}</P>
                        <P><strong>Email:&nbsp;</strong>{{ safe($neighbor['emailAddress'] ?? 'N/A') }}</P>
                        <P><strong>City:&nbsp;</strong>{{ safe($neighbor['city'] ?? 'N/A') }}</P>
                        <P><strong>State:&nbsp;</strong>{{ safe($neighbor['state'] ?? 'N/A') }}</P>
                        <P><strong>ZIP:&nbsp;</strong>{{ safe($neighbor['zip'] ?? 'N/A') }}</P>
                        <P><strong>Full Address:&nbsp;</strong>{{ safe($neighbor['fullAddress'] ?? 'N/A') }}</P>
                        <hr/>
                    @endforeach
                @else
                    <p>No neighbors found for this address.</p>
                @endif
            </div>
        @endforeach
    @else
        <p>No address or neighbor data found.</p>
    @endif
</section>



   {{-- Criminal & Traffic Records V2 --}}
        <section class="mt-4">
            <h2>Criminal & Traffic Records ({{ recordCount($report['criminalRecords']) }})</h2>

            @forelse($report['criminalRecords'] as $record)
                <div class="criminal-record-v2 mb-4 p-4 border rounded">

                    {{-- Full Name --}}
                    @php
                        $name = $record['names'][0] ?? null;
                    @endphp
                    @if($name)
                        <p><strong>Name:</strong> {{ $name['fullName'] ?? '—' }}</p>
                    @endif

                    {{-- Basic Demographics --}}
                    @php
                        $attr = $record['offenderAttributes'][0] ?? null;
                    @endphp
                    @if($attr)
                        <p>
                            <strong>Sex:</strong> {{ $attr['sex'] ?? '—' }}<br>
                            @if(!empty($attr['dob']))
                                <strong>DOB:</strong> {{ $attr['dob'] }}
                            @endif
                        </p>
                    @endif

                    {{-- Address --}}
                    @php
                        $addr = $record['addresses'][0] ?? null;
                    @endphp
                    @if($addr)
                        <p><strong>Address:</strong> {{ $addr['fullAddress'] ?? '—' }}</p>
                    @endif

                    {{-- Case Details --}}
                    @foreach($record['caseDetails'] as $case)
                        <div class="mt-2">
                            <p><strong>Case Number:</strong> {{ $case['caseNumber'] ?? '—' }}</p>
                            <p><strong>Category:</strong> {{ $case['mappedCategory'] ?? $case['rawCategory'] ?? '—' }}</p>
                            <p><strong>Court:</strong> {{ $case['court'] ?? '—' }} ({{ $case['courtCounty'] ?? '' }})</p>
                            @if(!empty($case['fees']))
                                <p><strong>Fees:</strong> {{ $case['fees'] }}</p>
                            @endif
                        </div>
                    @endforeach

                    {{-- Offenses --}}
                    @foreach($record['offenses'] as $offense)
                        <div class="mt-2">
                            <p><strong>Offense:</strong> {{ implode(', ', $offense['offenseDescription'] ?? []) }}</p>
                            @if(!empty($offense['chargesFiledDate']))
                                <p><strong>Charges Filed:</strong> {{ $offense['chargesFiledDate'] }}</p>
                            @endif
                            @if(!empty($offense['disposition']))
                                <p><strong>Disposition:</strong> {{ $offense['disposition'] }}</p>
                            @endif
                            @if(!empty($offense['dispositionDate']))
                                <p><strong>Disposition Date:</strong> {{ $offense['dispositionDate'] }}</p>
                            @endif
                            @if(!empty($offense['classificationCodeDescription']))
                                <p><strong>Classification:</strong> {{ $offense['classificationCodeDescription'] }}</p>
                            @endif
                        </div>
                    @endforeach

                    {{-- Other / Additional Info --}}
                    @foreach($record['others'] ?? [] as $other)
                        <div class="mt-2">
                            <p><strong>Arresting Agency:</strong> {{ $other['columns']['arrestingagency'] ?? '—' }}</p>
                            <p><strong>Plea:</strong> {{ $other['columns']['plea'] ?? '—' }}</p>
                            <p><strong>Status:</strong> {{ $other['columns']['status'] ?? '—' }}</p>
                            @if(!empty($other['columns']['comments']))
                                <p><strong>Comments:</strong> {{ $other['columns']['comments'] }}</p>
                            @endif
                        </div>
                    @endforeach

                </div>
            @empty
                <p><em>No criminal or traffic records found.</em></p>
            @endforelse
        </section>


    {{-- Debt / Liens / Judgments --}}
    <section>
        <h2>Debt, Liens & Judgments</h2>
        @forelse($report['debtV2Records'] ?? [] as $d)
            <div class="card">
                <p>Type: {{ safe($d['debtType']) }} — Filed: {{ safe($d['filingDate']) }}</p>
                <p>
                    Debtors:
                    {{ implode(', ', array_column($d['names'], 'fullName')) }}
                </p>
            </div>
        @empty
            <p>No debt/liens/judgments found.</p>
        @endforelse
    </section>

{{-- ================= FEIN ================= --}}
<section class="mt-4">
    <h2>FEIN </h2>

    @php
        $feinRecords = $report['feinRecords'] ?? [];
    @endphp

    @forelse($feinRecords as $index => $f)
        <div class="record-block">
            <h4>{{ 'Record ' . ($index + 1) . ' of ' . count($feinRecords) }} FEIN Records</h4>

            <h4>Business Name: {{ safe($f['company']['details']['companyName']) }}</h4>
            <p><strong>Description:</strong> {{ safe($f['company']['details']['naicsDescription']) }}</p>
            <p><strong>Record Type:</strong> FEIN</p>
            <p><strong>Type:</strong> {{ safe($f['company']['details']['businessType']) }}</p>
            <p><strong>Status:</strong> Not Specified</p>
            <p><strong>Filing State:</strong> {{ safe($f['miscellaneousDetails']['stateWhereEntityFormed']) }}</p>
            <p><strong>Reg #:</strong> Not Specified</p>
            <p><strong>Inc State:</strong> {{ safe($f['miscellaneousDetails']['stateWhereEntityFormed']) }}</p>

            <h4>FEIN Information</h4>
            <p><strong>EIN #:</strong> {{ safe($f['ein']) ?: 'Not Specified' }}</p>
            <p><strong>Business Type:</strong> {{ safe($f['company']['details']['businessType']) }}</p>
            <p><strong>Legal Name:</strong> {{ safe($f['company']['details']['legalName']) }}</p>
            <p><strong>Trade Name:</strong> {{ safe($f['company']['details']['tradeName']) ?: 'Not Specified' }}</p>
            <p><strong>DBA:</strong> {{ safe($f['company']['details']['dbaName']) ?: 'Not Specified' }}</p>
            <p><strong>FBN:</strong> {{ safe($f['company']['details']['fictitiousName']) ?: 'Not Specified' }}</p>
            <p><strong>FKA:</strong> {{ safe($f['company']['details']['fkaName']) ?: 'Not Specified' }}</p>
            <p><strong>Business Specialty:</strong> {{ safe($f['company']['details']['businessSpeciality']) }}</p>
            <p><strong>URL:</strong> {{ safe($f['company']['details']['url']) ?: 'Not Specified' }}</p>

            <h4>Location Details</h4>
            <p><strong>CBSA:</strong> {{ safe($f['company']['details']['cbsa']) }}</p>
            <p><strong>CBSA Name:</strong> {{ safe($f['company']['details']['cbsaName']) }}</p>
            <p><strong>DMA:</strong> {{ safe($f['company']['details']['dma']) }}</p>
            <p><strong>DMA Name:</strong> {{ safe($f['company']['details']['dmaName']) }}</p>
            <p><strong>NAICS:</strong> {{ safe($f['company']['details']['naics']) }}</p>
            <p><strong>NAICS Description:</strong> {{ safe($f['company']['details']['naicsDescription']) }}</p>
            <p><strong>Company Start Date:</strong> {{ safe($f['company']['details']['companyMonthStarted']) }}/{{ safe($f['company']['details']['companyYearStarted']) }}</p>
            <p><strong>SIC Code:</strong> {{ safe($f['standardIndustrialClassification']['sic6Code']) }}</p>
            <p><strong>SIC Description:</strong> {{ safe($f['standardIndustrialClassification']['sic6Description']) }}</p>

            {{-- Officers / Agents --}}
            <h4>Officers / Agents</h4>
            <p><strong>Registered Agent:</strong> {{ safe($f['company']['contact']['name']['contactFullName']) }}</p>

            {{-- Phones --}}
            <h4>Phones</h4>
            <p>{{ safe($f['company']['details']['phone']) ?: 'Not Specified' }}</p>

            {{-- Addresses --}}
            <h4>Addresses</h4>
            <p><strong>Company Address:</strong> {{ safe($f['company']['address']['fullAddress']) ?: 'Not Specified' }}</p>
            <p><strong>Owner Address:</strong> {{ safe($f['company']['owner']['address']['fullAddress']) ?: 'Not Specified' }}</p>

            <hr>
        </div>
    @empty
        <p>No FEIN records found.</p>
    @endforelse
</section>

   @php
        $propertyV2summary = $report['assessorDeedRecords']['summary']?? [];
        $allAssessorRecords = $report['assessorDeedRecords']['assessorRecords']?? [];
        $allRecorderRecords = $report['assessorDeedRecords']['recorderRecords']?? [];
   @endphp
   
 {{-- =========================
     Assessor Records Section
    ========================= --}}
    <section class="section assessor-records">
        <h2>Assessor Records</h2>
        @forelse($allAssessorRecords as $assessorRecords)
           @php $summary = $propertyV2summary[$loop->index]?? []; @endphp
         
            @forelse($assessorRecords as $record)

                    @php
                        $owners = $record['owners'] ?? [];
                        $mailingAddresses = $record['ownerMailingAddress'] ?? [];
                        $address = $record['address']['fullAddress'] ?? 'N/A';
                        $county = $record['address']['county'] ?? 'N/A';
                        $tax = $record['tax'] ?? [];
                        $structure = $record['structure'] ?? [];
                        $propertySize = $record['propertySize'] ?? [];
                        $purchase = $record['purchaseTransaction'] ?? [];
                        $propertyIdentification = $record['propertyIdentification'] ?? [];
                        $legal = $record['propertyLegal'] ?? [];
                        $location = $record['location'] ?? [];
                    @endphp
                
                    <div class="card">
                        {{-- Header --}}
                        <h3>Assessor Record - {{ safe($address) }}</h3>
                
                        {{-- Current Owners --}}
                        <h4>Current Owners ({{ count($owners) }})</h4>
                        @forelse($owners as $owner)
                            <p>{{ safe($owner['name']['fullName'] ?? 'N/A') }}</p>
                        @empty
                            <p>No owners found.</p>
                        @endforelse
                
                        {{-- Owner Mailing Address --}}
                        <h4>Mailing Address</h4>
                        @forelse($mailingAddresses as $m)
                            <p>{{ safe($m['fullAddress'] ?? 'N/A') }}</p>
                        @empty
                            <p>No mailing address found.</p>
                        @endforelse
                
                        {{-- Site Address --}}
                        <h4>Site Address</h4>
                        <p>{{ safe($address) }}</p>
                        <p>{{ safe($county) }} County</p>
                
                        {{-- Tax Information --}}
                        <h4>Tax Information</h4>
                        <p><strong>Tax Year:</strong> {{ safe($tax['taxYear'] ?? 'N/A') }}</p>
                        <p><strong>Assessed Year:</strong> {{ safe($tax['assessedYear'] ?? 'N/A') }}</p>
                        <p><strong>Assessed Total Value:</strong> {{ safe($tax['assessedTotalValue'] ?? 'N/A') }}</p>
                        <p><strong>Assessed Land Value:</strong> {{ safe($tax['assessedLandValue'] ?? 'N/A') }}</p>
                        <p><strong>Assessed Improvement Value:</strong> {{ safe($tax['assessedImprovementValue'] ?? 'N/A') }}</p>
                        <p><strong>Market Total Value:</strong> {{ safe($tax['marketTotalValue'] ?? 'N/A') }}</p>
                        <p><strong>Tax Amount:</strong> {{ safe($tax['taxAmount'] ?? 'N/A') }}</p>
                
                        {{-- Purchase Transaction --}}
                        <h4>Purchase Transaction</h4>
                        <p><strong>Sale Date:</strong> {{ safe($purchase['saleDate'] ?? 'N/A') }}</p>
                        <p><strong>Recording Date:</strong> {{ safe($purchase['saleRecordingDate'] ?? 'N/A') }}</p>
                        <p><strong>Sale Amount:</strong> {{ safe($purchase['saleAmount'] ?? 'N/A') }}</p>
                        <p><strong>Document Type:</strong> {{ safe($purchase['saleDocumentTypeDescription'] ?? 'N/A') }}</p>
                        <p><strong>Document Number:</strong> {{ safe($purchase['saleRecordedDocumentNumber'] ?? 'N/A') }}</p>
                
                        {{-- Property Identification --}}
                        <h4>Property Identification</h4>
                        <p><strong>FIPS Code:</strong> {{ safe($propertyIdentification['fipsCode'] ?? 'N/A') }}</p>
                        <p><strong>APN:</strong> {{ safe($propertyIdentification['apnUnformatted'] ?? 'N/A') }}</p>
                        <p><strong>Land Use:</strong> {{ safe($propertyIdentification['landUseCodeDescription'] ?? 'N/A') }}</p>
                        <p><strong>Zoning:</strong> {{ safe($propertyIdentification['zoningCodeDescription'] ?? 'N/A') }}</p>
                
                        {{-- Legal Description --}}
                        <h4>Legal Description</h4>
                        <p><strong>Block:</strong> {{ safe($legal['legalBlockNumber'] ?? 'N/A') }}</p>
                        <p><strong>Lot:</strong> {{ safe($legal['legalLotNumber'] ?? 'N/A') }}</p>
                        <p><strong>Subdivision:</strong> {{ safe($legal['subdivisionName'] ?? 'N/A') }}</p>
                        <p><strong>Legal Description:</strong> {{ safe($legal['legalDescription'] ?? 'N/A') }}</p>
                
                        {{-- Location Info --}}
                        <h4>Location Information</h4>
                        <p><strong>Municipality:</strong> {{ safe($location['municipalityName'] ?? 'N/A') }}</p>
                        <p><strong>County:</strong> {{ safe($county) }}</p>
                        <p><strong>School District:</strong> {{ safe($location['schoolDistrict'] ?? 'N/A') }}</p>
                
                        {{-- Structure Information --}}
                        <h4>Structure Information</h4>
                        <p><strong>Year Built:</strong> {{ safe($structure['yearBuilt'] ?? 'N/A') }}</p>
                        <p><strong>Effective Year Built:</strong> {{ safe($structure['effectiveYearBuilt'] ?? 'N/A') }}</p>
                        <p><strong>Building Style:</strong> {{ safe($structure['buildingStyleCodeDescription'] ?? 'N/A') }}</p>
                        <p><strong>Garage:</strong> {{ safe($structure['garageCodeDescription'] ?? 'N/A') }}</p>
                        <p><strong>Stories:</strong> {{ safe($structure['storiesNumber'] ?? 'N/A') }}</p>
                        <p><strong>Units:</strong> {{ safe($structure['numberOfUnits'] ?? 'N/A') }}</p>
                
                        {{-- Property Size --}}
                        <h4>Property Size</h4>
                        <p><strong>Front Footage:</strong> {{ safe($propertySize['frontFootage'] ?? 'N/A') }}</p>
                        <p><strong>Depth Footage:</strong> {{ safe($propertySize['depthFootage'] ?? 'N/A') }}</p>
                        <p><strong>Land Sq. Ft.:</strong> {{ safe($propertySize['landSquareFootage'] ?? 'N/A') }}</p>
                        <p><strong>Acres:</strong> {{ safe($propertySize['acres'] ?? 'N/A') }}</p>
                    </div>
                

            @empty
                    <p>No assessor records found.</p>
            @endforelse
        @empty
           <p>No assessor records found.</p>
        @endforelse
    </section>
    
    
     {{-- =========================
         Deed / Recorder Records Section
    ========================= --}}
    <section class="section recorder-records">
        <h2> Deed Records</h2>
        @forelse($allRecorderRecords as $recorderRecords)
            @php $summary = $propertyV2summary[$loop->index]?? []; @endphp
           
            
            @forelse($recorderRecords as $record)

                    @php
                        // Core sections
                        $address = $record['address'] ?? [];
                        $transaction = $record['transactionSummary']['transactionDetails'] ?? [];
                        $buyers = $record['transactionSummary']['buyers'] ?? [];
                        $sellers = $record['transactionSummary']['sellers'] ?? [];
                        $mortgage = $record['mortgageDetails'] ?? [];
                        $lenders = $mortgage['lenders'] ?? [];
                        $property = $record['propertyIdentification'] ?? [];
            
                        // Extract commonly used data
                        $siteAddress = $address['fullAddress'] ?? 'N/A';
                        $county = $address['county'] ?? 'N/A';
            
                        $buyerName = $buyers[0]['name']['fullName'] ?? 'N/A';
                        $sellerName = $sellers[0]['name']['fullName'] ?? 'N/A';
                        $lenderName = $lenders[0]['name']['fullName'] ?? 'N/A';
                    @endphp
            
                    <div class="card">
                        {{-- Header --}}
                        <h3>Recorder Record #{{ $loop->iteration }} - {{ safe($siteAddress) }}</h3>
            
                        {{-- Document Information --}}
                        <h4>Document Information</h4>
                        <p><strong>Document Type:</strong> {{ safe($transaction['saleDocumentTypeDescription'] ?? 'N/A') }}</p>
                        <p><strong>Recording Date:</strong> {{ safe($transaction['saleRecordningDate'] ?? 'N/A') }}</p>
                        <p><strong>Deed Category:</strong> {{ safe($transaction['deedCategoryTypeDescription'] ?? 'N/A') }}</p>
                        <p><strong>Document Number:</strong> {{ safe($transaction['recordedSaleDocumentNumber'] ?? 'N/A') }}</p>
                        <p><strong>Book / Page:</strong> {{ safe($transaction['recordedSaleDocumentBookAndPage'] ?? 'N/A') }}</p>
            
                        {{-- Grantor / Grantee --}}
                        <h4>Grantor / Grantee</h4>
                        <p><strong>Seller Name (Grantor):</strong> {{ safe($sellerName) }}</p>
                        <p><strong>Buyer Name (Grantee):</strong> {{ safe($buyerName) }}</p>
            
                        {{-- Transaction Details --}}
                        <h4>Transaction Details</h4>
                        <p><strong>Sale Price:</strong> {{ safe($transaction['saleAmount'] ?? 'N/A') }}</p>
                        <p><strong>Sale Date:</strong> {{ safe($transaction['saleDate'] ?? 'N/A') }}</p>
                        <p><strong>Transaction Type:</strong> {{ safe($transaction['transactionTypeDescription'] ?? 'N/A') }}</p>
                        <p><strong>Ownership Transfer %:</strong> {{ safe($transaction['ownershipTransferPercentage'] ?? 'N/A') }}</p>
            
                        {{-- Property Information --}}
                        <h4>Property Information</h4>
                        <p><strong>Full Address:</strong> {{ safe($siteAddress) }}</p>
                        <p><strong>County:</strong> {{ safe($county) }}</p>
                        <p><strong>APN:</strong> {{ safe($property['apnUnformatted'] ?? 'N/A') }}</p>
                        <p><strong>Tax Account Number:</strong> {{ safe($property['taxAccountNumber'] ?? 'N/A') }}</p>
                        <p><strong>Land Use Code:</strong> {{ safe($property['landUseCodeDescription'] ?? 'N/A') }}</p>
                        <p><strong>Zoning Code:</strong> {{ safe($property['zoningCodeDescription'] ?? 'N/A') }}</p>
            
                        {{-- Loan Information --}}
                        <h4>Loan Information</h4>
                        <p><strong>Lender Name:</strong> {{ safe($lenderName) }}</p>
                        <p><strong>Loan Amount:</strong> {{ safe($mortgage['mortgageAmount'] ?? 'N/A') }}</p>
                        <p><strong>Loan Type:</strong> {{ safe($mortgage['mortgageLoanTypeCodeDescription'] ?? 'N/A') }}</p>
                        <p><strong>Loan Term:</strong> {{ safe($mortgage['mortgageTerm'] ?? 'N/A') }} {{ safe($mortgage['mortgageTermCodeDescription'] ?? '') }}</p>
                        <p><strong>Interest Rate:</strong> {{ safe($mortgage['mortgageInterestRate'] ?? 'N/A') }}</p>
                        <p><strong>Mortgage Date:</strong> {{ safe($mortgage['mortgageDate'] ?? 'N/A') }}</p>
                        <p><strong>Recording Date:</strong> {{ safe($mortgage['mortgageRecordingDate'] ?? 'N/A') }}</p>
                        <p><strong>Document Number:</strong> {{ safe($mortgage['mortgageRecordedDocumentNumber'] ?? 'N/A') }}</p>
            
                        {{-- Miscellaneous Info --}}
                        <h4>Additional Info</h4>
                        <p><strong>FIPS Code:</strong> {{ safe($property['fipsCode'] ?? 'N/A') }}</p>
                        <p><strong>Municipal Name:</strong> {{ safe($address['city'] ?? 'N/A') }}</p>
                    </div>
               
            @empty
                <p>No deed/recorder records found.</p>
            @endforelse

            
        @empty
               <p>No deed/recorder records found.</p>
        @endforelse
    </section>
    
    
    
    {{-- Domain Records --}}
    <section class="mt-4">
        <h2>Domain Records</h2>
        @php $i = 1; @endphp
    
        @forelse($report['domainRecords'] ?? [] as $domain)
            <div class="card">
                <h4>{{ $i }} of {{ count($report['domainRecords']) }} Domain Records</h4>
    
                <p><strong>Domain Name:</strong> {{ safe($domain['domainName']) }}</p>
                <p><strong>Registrar:</strong> {{ safe($domain['registrar']) }}</p>
                <p><strong>Domain Status:</strong> {{ safe($domain['domainStatus']) }}</p>
                <p><strong>Created Date:</strong> {{ safe($domain['creationDate']) }}</p>
                <p><strong>Expiration Date:</strong> {{ safe($domain['expirationDate']) }}</p>
                <p><strong>Updated Date:</strong> {{ safe($domain['updatedDate']) }}</p>
                <p><strong>Whois Server:</strong> {{ safe($domain['whoisServer']) }}</p>
                <p><strong>DNSSEC:</strong> {{ safe($domain['dnssec']) }}</p>
                <p><strong>Name Servers:</strong> {{ !empty($domain['nameServers']) ? implode(', ', $domain['nameServers']) : 'Not Specified' }}</p>
    
                {{-- Contacts --}}
                @if(!empty($domain['contacts']))
                    <h4>Contacts</h4>
                    @foreach($domain['contacts'] as $contact)
                        <div class="contact-block" style="margin-bottom: 10px;">
                            <p><strong>Contact Type:</strong> {{ safe($contact['addressTypeDesc'], 'Not Specified') }}</p>
                            <p><strong>Full Name:</strong> {{ trim(safe($contact['firstName']).' '.safe($contact['lastName'])) ?: 'Not Specified' }}</p>
                            <p><strong>Address:</strong> 
                                {{ safe($contact['houseNumber']) }} {{ safe($contact['streetName']) }} {{ safe($contact['streetType']) }}, 
                                {{ safe($contact['city']) }}, {{ safe($contact['state']) }} {{ safe($contact['zip']) }}
                            </p>
                            <p><strong>Country:</strong> {{ safe($contact['country']) }}</p>
    
                            {{-- Emails --}}
                            @if(!empty($contact['emails']))
                                <p><strong>Emails:</strong> {{ implode(', ', $contact['emails']) }}</p>
                            @endif
    
                            {{-- Phones --}}
                            @if(!empty($contact['phones']))
                                <p><strong>Phones:</strong> {{ implode(', ', $contact['phones']) }}</p>
                            @endif
    
                            {{-- Faxes --}}
                            @if(!empty($contact['faxes']))
                                <p><strong>Faxes:</strong> {{ implode(', ', $contact['faxes']) }}</p>
                            @endif
    
                            {{-- Dates --}}
                            @if(!empty($contact['dates']))
                                @foreach($contact['dates'] as $date)
                                    <p>
                                        <strong>Created:</strong> {{ safe($date['creationDate']) }} | 
                                        <strong>Last Updated:</strong> {{ safe($date['lastUpdated']) }}
                                    </p>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @else
                    <p>No contact information found for this domain.</p>
                @endif
            </div>
    
            @php $i++; @endphp
        @empty
            <p>No domain records found.</p>
        @endforelse
    </section>

    

    {{-- Corporate Records --}}
    <section class="mt-4">
        <h2>Corporate Records</h2>
        @php $i = 1; @endphp
    
        @forelse($report['corporateRecords'] ?? [] as $corp)
            <div class="card">
                <h4>{{ $i }} of {{ count($report['corporateRecords']) }} Corporate Records</h4>
    
                <p><strong>Business Name:</strong> {{ safe($corp['businessName']) }}</p>
                <p><strong>EIN #:</strong> {{ safe($corp['taxId'], 'Not Specified') }}</p>
                <p><strong>Type:</strong> {{ safe($corp['corpType'], 'Not Specified') }}</p>
                <p><strong>Record Type:</strong> US Corp</p>
                <p><strong>Status:</strong> {{ safe($corp['corpStatus'], 'Not Specified') }}</p>
                <p><strong>Status Date:</strong> {{ safe($corp['corpStatusDate'], 'Not Specified') }}</p>
                <p><strong>State Code:</strong> {{ safe($corp['state'], 'Not Specified') }}</p>
                <p><strong>Terms:</strong> {{ safe($corp['term'], 'Not Specified') }}</p>
                <p><strong>Filing #:</strong> {{ safe($corp['registryNum'], 'Not Specified') }}</p>
                <p><strong>Filing Date:</strong> {{ safe($corp['filingDate'], 'Not Specified') }}</p>
                <p><strong>Filing Type:</strong> BIENNIAL STATEMENT</p>
                <p><strong>Jurisdiction:</strong> {{ safe($corp['incJurisdiction'], 'Not Specified') }}</p>
                <p><strong>Last Reported:</strong> {{ safe($corp['lastReportDate'], 'Not Specified') }}</p>
    
                {{-- Officers / Agents --}}
                @if(!empty($corp['names']))
                    <h4> Officers / Agents</h4>
                    @foreach($corp['names'] as $officer)
                        <p>
                            <strong>{{ safe($officer['officerType']) }}</strong><br>
                            {{ trim(safe($officer['firstname']).' '.safe($officer['middleName']).' '.safe($officer['lastName'])) }}
                        </p>
                    @endforeach
                @else
                    <p>No officer or agent records found.</p>
                @endif
    
                {{-- Addresses --}}
                @if(!empty($corp['addresses']))
                    <h4> Addresses</h4>
    
                    @php
                        $officeAddresses = collect($corp['addresses'])->where('addressType', 'HQ');
                        $officerAddresses = collect($corp['addresses'])->where('addressType', 'Officer');
                    @endphp
    
                    @if($officeAddresses->count())
                        <h5>Office Address</h5>
                        @foreach($officeAddresses as $addr)
                            <p>{{ safe($addr['addressLine1']) }} {{ safe($addr['addressLine2']) }}{{ safe($addr['city']) ? ', '.safe($addr['city']) : '' }}{{ safe($addr['state']) ? ', '.safe($addr['state']) : '' }} {{ safe($addr['zipCode']) }}</p>
                        @endforeach
                    @endif
    
                    @if($officerAddresses->count())
                        <h5>Officer Address</h5>
                        @foreach($officerAddresses as $addr)
                            <p>{{ safe($addr['addressLine1']) }} {{ safe($addr['addressLine2']) }}{{ safe($addr['city']) ? ', '.safe($addr['city']) : '' }}{{ safe($addr['state']) ? ', '.safe($addr['state']) : '' }} {{ safe($addr['zipCode']) }}</p>
                        @endforeach
                    @endif
                @else
                    <p>No addresses found.</p>
                @endif
            </div>
    
            @php $i++; @endphp
        @empty
            <p>No corporate records found.</p>
        @endforelse
    </section>
    
    {{-- UCC Filings --}}
    <section class="mt-4">
        <h2>UCC Filings</h2>
        @php $i = 1; @endphp
    
        @forelse($report['uccFilings'] as $ucc)
            <h3>Record {{ $i }} of {{ count($report['uccFilings']) }}</h3>
            <p><strong>Filing Number:</strong> {{ safe($ucc['filingNumber'], 'Not Specified') }}</p>
            <p><strong>Filing Date:</strong> {{ safe($ucc['filingDate'], 'Not Specified') }}</p>
            <p><strong>Filing Type:</strong> {{ safe($ucc['filingType'], 'Not Specified') }}</p>
            <p><strong>Status:</strong> {{ safe($ucc['status'], 'Not Specified') }}</p>
            <p><strong>Secured Party:</strong> {{ safe($ucc['securedPartyName'], 'Not Specified') }}</p>
            <p><strong>Debtor:</strong> {{ safe($ucc['debtorName'], 'Not Specified') }}</p>
            <p><strong>Collateral Description:</strong> {{ safe($ucc['collateralDescription'], 'Not Specified') }}</p>
            <p><strong>Filing Jurisdiction:</strong> {{ safe($ucc['jurisdiction'], 'Not Specified') }}</p>
            <p><strong>Amendment Type:</strong> {{ safe($ucc['amendmentType'], 'Not Specified') }}</p>
            <p><strong>Expiration Date:</strong> {{ safe($ucc['expirationDate'], 'Not Specified') }}</p>
            <p><strong>Continuation Date:</strong> {{ safe($ucc['continuationDate'], 'Not Specified') }}</p>
    
            @if(!empty($ucc['parties']))
                <h4>Parties</h4>
                <ul>
                    @foreach($ucc['parties'] as $party)
                        <li>
                            {{ safe($party['name'], 'Unknown') }} — {{ safe($party['role'], 'Role Not Specified') }}
                        </li>
                    @endforeach
                </ul>
            @endif
    
            <hr>
            @php $i++; @endphp
        @empty
            <p>No UCC Filings found.</p>
        @endforelse

    </section>
        
    {{-- DBA / FBN Records --}}
    <section class="mt-4">
        <h2>DBA / FBN Records</h2>
        @php $i = 1; @endphp
    
        @forelse($report['dbaFbnRecords'] as $dba)
            <h3>Record {{ $i }} of {{ count($report['dbaFbnRecords']) }}</h3>
    
            <p><strong>Business Name:</strong> {{ safe($dba['businessName'], 'Not Specified') }}</p>
            <p><strong>Filing Date:</strong> {{ safe($dba['filingDate'], 'Not Specified') }}</p>
            <p><strong>Expiration Date:</strong> {{ safe($dba['expirationDate'], 'Not Specified') }}</p>
    
            @php
                $address = $dba['addresses'][0] ?? null;
                $county = $address['county'] ?? 'Not Specified';
                $businessAddress = $address
                    ? ($address['addressLine1'] ?? '').' '.($address['addressLine2'] ?? '').', '.($address['city'] ?? '').', '.($address['state'] ?? '').' '.($address['zipCode'] ?? '')
                    : 'Not Specified';
            @endphp
    
            <p><strong>County:</strong> {{ safe($county, 'Not Specified') }}</p>
            <p><strong>Status:</strong> {{ safe($dba['corpStatus'], 'Not Specified') }}</p>
            <p><strong>Business Address:</strong> {{ safe($businessAddress, 'Not Specified') }}</p>
    
            @if(!empty($dba['names']))
                <h4>Owners</h4>
                <ul>
                    @foreach($dba['names'] as $owner)
                        <li>
                            {{ safe(trim($owner['firstname'].' '.$owner['middleName'].' '.$owner['lastName']), 'Name Not Specified') }}
                        </li>
                    @endforeach
                </ul>
            @endif
    
            <hr>
            @php $i++; @endphp
        @empty
            <p>No DBA/FBN Records found.</p>
        @endforelse
    </section>

    

{{-- Workplace Records --}}
<section class="mt-4">
    <h2>Workplace Records ({{ recordCount($report['workplaceRecords']) }})</h2>

    @forelse($report['workplaceRecords'] ?? [] as $index => $record)

        @php
            // Prevent undefined index errors
            $currentEmployment = $record['currentEmployment'][0] ?? null;

            $phoneNumbers = array_filter($record['phoneNumbers'] ?? [], function ($num) {
                return ($num['phoneType'] ?? '') === "LandLine/Services";
            });

            $mobilePhoneNumbers = array_filter($record['phoneNumbers'] ?? [], function($num){
                return ($num['phoneType'] ?? '') !== "LandLine/Services";
            });
        @endphp

        <div class="section page-break">

            <h4>
                {{ $loop->iteration }} of {{ count($report['workplaceRecords'] ?? []) }}
                Workplace Records ({{ safe($currentEmployment['employer'] ?? null) }} -
                {{ safe($currentEmployment['jobTitle'] ?? null) }})
            </h4>

            <h4>Basic Information</h4>
            <table>
                <tr><th>Full Name</th><td>{{ safe($record['fullName']) }}</td></tr>
                <tr><th>Professional Titles</th><td>{{ safe($record['professionalTitles']) }}</td></tr>

                <tr><th>Most Recent Employer</th>
                    <td>{{ safe($currentEmployment['employer'] ?? null) }}</td>
                </tr>

                <tr><th>Most Recent Job Title</th>
                    <td>{{ safe($currentEmployment['jobTitle'] ?? null) }}</td>
                </tr>

                <tr><th>Most Recent Employer Address</th>
                    <td>
                        {{ safe($currentEmployment['houseNumber'] ?? '') }}
                        {{ safe($currentEmployment['streetDirection'] ?? '') }}
                        {{ safe($currentEmployment['streetName'] ?? '') }}
                        {{ safe($currentEmployment['streetType'] ?? '') }},
                        {{ safe($currentEmployment['city'] ?? '') }},
                        {{ safe($currentEmployment['state'] ?? '') }}
                        {{ safe($currentEmployment['zip'] ?? '') }}
                    </td>
                </tr>

                <tr><th>Current Address</th>
                    <td>{{ safe($record['addresses'][0]['fullAddress'] ?? null) }}</td>
                </tr>

                <tr><th>Most Recent Start Date</th>
                    <td>{{ safe($currentEmployment['startDate'] ?? null) }}</td>
                </tr>

                <tr><th>Most Recent End Date</th>
                    <td>{{ safe($currentEmployment['endDate'] ?? null) }}</td>
                </tr>

                <tr><th>Most Recent Employer Duration</th>
                    <td>{{ safe($currentEmployment['duration'] ?? null) }}</td>
                </tr>
            </table>

            {{-- Education --}}
            <h4>Education</h4>
            @if(!empty($record['education']))
                <ul>
                    @php $i = 1 @endphp
                    @foreach($record['education'] as $edu)
                        <h4>({{ $i }}) {{ safe($edu["degree"],'N/A') }}</h4>
                        <li><strong>School:&nbsp;</strong>{{ safe($edu["school"],'N/A') }}</li>
                        <li><strong>School Match:&nbsp;</strong>{{ safe($edu["schoolMatch"],'N/A') }}</li>
                        <li><strong>Degree:&nbsp;</strong>{{ safe($edu["degree"],'N/A') }}</li>
                        <li><strong>Degree Match:&nbsp;</strong>{{ safe($edu["degreeMatch"],'N/A') }}</li>
                        <li><strong>Major:&nbsp;</strong>{{ safe($edu["major"],'N/A') }}</li>
                        <li><strong>Start Date:&nbsp;</strong>{{ safe($edu["startDate"],'N/A') }}</li>
                        <li><strong>End Date:&nbsp;</strong>{{ safe($edu["endDate"],'N/A') }}</li>
                        @php $i++ @endphp
                    @endforeach
                </ul>
            @else
                <p>Not Specified</p>
            @endif

            {{-- Addresses --}}
            <h4>Addresses</h4>
            @foreach($record['addresses'] ?? [] as $addr)
                <p>{{ safe($addr['fullAddress']) }}</p>
            @endforeach

            {{-- Employer Addresses --}}
            <h4>Employer Addresses</h4>
            @foreach($record['currentEmployment'] ?? [] as $emp)
                <p>
                    {{ safe($emp['houseNumber'] ?? '') }}
                    {{ safe($emp['streetDirection'] ?? '') }}
                    {{ safe($emp['streetName'] ?? '') }}
                    {{ safe($emp['streetType'] ?? '') }}
                    {{ safe($emp['city'] ?? '') }},
                    {{ safe($emp['state'] ?? '') }} {{ safe($emp['zip'] ?? '') }}
                </p>
            @endforeach

            {{-- Phone Numbers --}}
            <h4>Phone Numbers</h4>
            @forelse($phoneNumbers as $phone)
                <p>{{ safe($phone) }}</p>
            @empty
                <p>Not Specified</p>
            @endforelse

            {{-- Mobile Phone Numbers --}}
            <h4>Mobile Phone Numbers</h4>
            @forelse($mobilePhoneNumbers as $phone)
                <p>{{ safe($phone) }}</p>
            @empty
                <p>Not Specified</p>
            @endforelse


            {{-- Emails --}}
            <h4>Emails</h4>
            @forelse($record['emailAddresses'] ?? [] as $email)
                <p>{{ safe($email) }}</p>
            @empty
                <p>Not Specified</p>
            @endforelse

            {{-- Groups --}}
            <h4>Groups</h4>
            @if(!empty($record['groups']))
                <ul>
                    @foreach($record['groups'] as $group)
                        <li>{{ safe($group) }}</li>
                    @endforeach
                </ul>
            @else
                <p>Not Specified</p>
            @endif

            {{-- Employment History --}}
            <h2>Employment History</h2>
            @foreach($record['workExperience'] ?? [] as $work)
                <div class="card">
                    <h3><strong>{{ safe($work['expCompany']) }}</strong> from {{ safe($work['expStartDate'] ?? null) }} to {{ safe($work['expEndDate'] ?? null) }}</h3>
                    <p>Job Title: {{ safe($work['expJobTitle']) }}</p>
                    <p>Duration: {{ safe($work['expDuration']) }}</p>
                    <p>Company Details: {{ safe($work['expCompanyDetails']) }}</p>
                    <p>Address: {{ safe($work['company']['fullAddress'] ?? null) }}</p>
                    <p>Headquarters: {{ safe($work['company']['companyHeadQuarters'] ?? null) }}</p>
                    <p>Type: {{ safe($work['company']['companyType'] ?? null) }}</p>
                    <p>Industry: {{ safe($work['company']['companyIndustry'] ?? null) }}</p>
                    <p>Status: {{ safe($work['company']['companyStatus'] ?? null) }}</p>
                    <p>Website:
                        @if(!empty($work['company']['companyWebSite']))
                            <a href="{{ safe($work['company']['companyWebSite']) }}">{{ safe($work['company']['companyWebSite']) }}</a>
                        @else
                            Not Specified
                        @endif
                    </p>
                    <p>Founded: {{ safe($work['company']['companyFounded'] ?? null) }}</p>
                    <p>Size: {{ safe($work['company']['companySize'] ?? null) }}</p>
                </div>
            @endforeach

        </div>

    @empty
        <p>No Workplace Records found.</p>
    @endforelse
</section>

    
    {{-- ========================= Bankruptcy Records ========================= --}}
<section>
    <h2>Bankruptcy Records</h2>
    @php $i = 1; @endphp

    @forelse($report['bankruptcyRecords'] ?? [] as $record)
        <div class="card">
            <h4>{{ $i }} of {{ count($report['bankruptcyRecords']) }} Bankruptcy Records</h4>

            <p><strong>Debt Type:</strong> {{ safe($record['debtType']) }}</p>
            <p><strong>Filing Date:</strong> {{ safe($record['filingDate']) }}</p>
            <p><strong>Report Date:</strong> {{ safe($record['reportDate']) }}</p>

            {{-- Names --}}
            @if(!empty($record['names']))
                <h4>Parties</h4>
                @foreach($record['names'] as $name)
                    <p>
                        <strong>{{ ucfirst(safe($name['type'])) }}:</strong>
                        {{ safe($name['businessName'] ?? $name['fullName']) }}
                        @if(!empty($name['address']) && isset($name['address']['fullAddress']))
                            <br><em>{{ safe($name['address']['fullAddress']) }}</em>
                        @endif
                    </p>
                @endforeach
            @endif

            {{-- Courts --}}
            @if(!empty($record['courts']))
                <h4>Courts</h4>
                @foreach($record['courts'] as $court)
                    <p>
                        <strong>{{ safe($court['name']) }}</strong><br>
                        {{ safe(!empty($court['address'])? $court['address']['fullAddress'] : '') }}<br>
                        Phone: {{ safe($court['phone'], 'N/A') }}
                    </p>
                @endforeach
            @endif

            {{-- Case Details --}}
            @if(!empty($record['caseDetails']))
                <h4>Case Details</h4>
                @foreach($record['caseDetails'] as $case)
                    <p>
                        <strong>Case #:</strong> {{ safe($case['caseNumber']) }}<br>
                        <strong>Filing Type:</strong> {{ safe($case['filingType']) }}<br>
                        <strong>Chapter:</strong> {{ safe($case['chapter'], 'N/A') }}<br>
                        <strong>Discharge Date:</strong> {{ safe($case['dischargeDate'], 'N/A') }}<br>
                        <strong>Dismissal Date:</strong> {{ safe($case['dismissalDate'], 'N/A') }}<br>
                        <strong>Closed Date:</strong> {{ safe($case['closedDate'], 'N/A') }}
                    </p>
                @endforeach
            @endif
        </div>

        @php $i++; @endphp
    @empty
        <p>No bankruptcy records found.</p>
    @endforelse
</section>


{{-- ========================= Tax Lien Records ========================= --}}
<section>
    <h2>Tax Lien Records</h2>
    @php $i = 1; @endphp

    @forelse($report['taxLienRecords'] ?? [] as $record)
        <div class="card">
            <h4>{{ $i }} of {{ count($report['taxLienRecords']) }} Tax Lien Records</h4>

            <p><strong>Debt Type:</strong> {{ safe($record['debtType']) }}</p>
            <p><strong>Filing Date:</strong> {{ safe($record['filingDate']) }}</p>
            <p><strong>Report Date:</strong> {{ safe($record['reportDate']) }}</p>

            {{-- Names --}}
            @if(!empty($record['names']))
                <h4>Parties</h4>
                @foreach($record['names'] as $name)
                    <p>
                        <strong>{{ ucfirst(safe($name['type'])) }}:</strong>
                        {{ safe($name['businessName'] ?? $name['fullName']) }}
                         @if(!empty($name['address']) && isset($name['address']['fullAddress']))
                            <br><em>{{ safe($name['address']['fullAddress']) }}</em>
                        @endif
                    </p>
                @endforeach
            @endif

            {{-- Addresses --}}
            @if(!empty($record['addresses']))
                <h4>Addresses</h4>
                @foreach($record['addresses'] as $addr)
                    <p>{{ safe($addr['fullAddress']) }}</p>
                @endforeach
            @endif

            {{-- Case Details --}}
            @if(!empty($record['caseDetails']))
                <h4>Case Details</h4>
                @foreach($record['caseDetails'] as $case)
                    <p>
                        <strong>Case #:</strong> {{ safe($case['caseNumber']) }}<br>
                        <strong>Liability:</strong> {{ safe($case['liability'], 'N/A') }}<br>
                        <strong>Release Date:</strong> {{ safe($case['releaseDate'], 'N/A') }}
                    </p>
                @endforeach
            @endif
        </div>

        @php $i++; @endphp
    @empty
        <p>No tax lien records found.</p>
    @endforelse
</section>


{{-- ========================= Judgment Records ========================= --}}
<section>
    <h2>Judgment Records</h2>
    @php $i = 1; @endphp

    @forelse($report['judgmentRecords'] ?? [] as $record)
        <div class="card">
            <h4>{{ $i }} of {{ count($report['judgmentRecords']) }} Judgment Records</h4>

            <p><strong>Debt Type:</strong> {{ safe($record['debtType']) }}</p>
            <p><strong>Filing Date:</strong> {{ safe($record['filingDate']) }}</p>
            <p><strong>Report Date:</strong> {{ safe($record['reportDate']) }}</p>

            {{-- Names --}}
            @if(!empty($record['names']))
                <h4>Parties</h4>
                @foreach($record['names'] as $name)
                    <p>
                        <strong>{{ ucfirst(safe($name['type'])) }}:</strong>
                        {{ safe($name['businessName'] ?? $name['fullName']) }}
                        @if(!empty($name['address']) && isset($name['address']['fullAddress']))
                            <br><em>{{ safe($name['address']['fullAddress']) }}</em>
                        @endif
                    </p>
                @endforeach
            @endif

            {{-- Courts --}}
            @if(!empty($record['courts']))
                <h4>Courts</h4>
                @foreach($record['courts'] as $court)
                    <p>
                        <strong>{{ safe($court['name']) }}</strong><br>
                        {{ safe(!empty($court['address'])? $court['address']['fullAddress'] : '') }}<br>
                        Phone: {{ safe($court['phone'], 'N/A') }}
                    </p>
                @endforeach
            @endif

            {{-- Case Details --}}
            @if(!empty($record['caseDetails']))
                <h4>Case Details</h4>
                @foreach($record['caseDetails'] as $case)
                    <p>
                        <strong>Case #:</strong> {{ safe($case['caseNumber']) }}<br>
                        <strong>Liability:</strong> {{ safe($case['liability'], 'N/A') }}<br>
                        <strong>Judgment Entered Date:</strong> {{ safe($case['judgmentEnteredDate'], 'N/A') }}<br>
                        <strong>Release Date:</strong> {{ safe($case['releaseDate'], 'N/A') }}
                    </p>
                @endforeach
            @endif
        </div>

        @php $i++; @endphp
    @empty
        <p>No judgment records found.</p>
    @endforelse
</section>

    
  {{-- DEA Licenses --}}
<section class="mt-4">
    <h2>DEA Licenses</h2>
    @php 
        $i = 1; 
        $deaRecordsCount = count($report['deaRecords'] ?? []);
    @endphp

    @forelse($report['deaRecords'] as $dea)
        <div class="card mb-3">
            <h3>Record {{ $i }} of {{ $deaRecordsCount }}</h3>

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

        </div>
        @php $i++; @endphp
    @empty
        <p>No DEA License records found.</p>
    @endforelse
</section>


</div>

</div>

@endsection