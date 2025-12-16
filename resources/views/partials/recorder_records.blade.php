

    @forelse($records as $index => $record)
        @php
            $owners = $record['currentOwners'] ?? [];
            $siteAddress = $summary['fullAddress'] ?? ($record['siteAddress']['fullAddress'] ?? 'N/A');
            $county = $summary['county'] ?? ($record['siteAddress']['county'] ?? 'N/A');
        @endphp

        <div class="card">
            {{-- Header --}}
            <h3>Recorder Record #{{ $index + 1 }} - {{ safe($siteAddress) }}</h3>

            {{-- Document Information --}}
            <h4> Document Information</h4>
            <p><strong>Document Type:</strong> {{ safe($record['documentType'] ?? 'N/A') }}</p>
            <p><strong>Document Number:</strong> {{ safe($record['documentNumber'] ?? 'N/A') }}</p>
            <p><strong>Recording Date:</strong> {{ safe($record['recordingDate'] ?? 'N/A') }}</p>
            <p><strong>Book / Page:</strong> {{ safe($record['bookPage'] ?? 'N/A') }}</p>
            <p><strong>Instrument Number:</strong> {{ safe($record['instrumentNumber'] ?? 'N/A') }}</p>
            <p><strong>Transfer Type:</strong> {{ safe($record['transferType'] ?? 'N/A') }}</p>
            <p><strong>Transaction Type:</strong> {{ safe($record['transactionType'] ?? 'N/A') }}</p>

            {{-- Grantor / Grantee --}}
            <h4>Grantor / Grantee</h4>
            <p><strong>Grantor:</strong> {{ safe($record['grantor'] ?? 'N/A') }}</p>
            <p><strong>Grantee:</strong> {{ safe($record['grantee'] ?? 'N/A') }}</p>
            <p><strong>Seller Name:</strong> {{ safe($record['sellerName'] ?? 'N/A') }}</p>
            <p><strong>Buyer Name:</strong> {{ safe($record['buyerName'] ?? 'N/A') }}</p>

            {{-- Transaction Details --}}
            <h4>Transaction Details</h4>
            <p><strong>Sale Price:</strong> {{ safe($record['salePrice'] ?? 'N/A') }}</p>
            <p><strong>Transfer Amount:</strong> {{ safe($record['transferAmount'] ?? 'N/A') }}</p>
            <p><strong>Transfer Date:</strong> {{ safe($record['transferDate'] ?? 'N/A') }}</p>
            <p><strong>Arms Length:</strong> {{ safe($record['armsLengthFlagDFS'] ?? 'N/A') }}</p>

            {{-- Property Information --}}
            <h4>Property Information</h4>
            <p><strong>Full Address:</strong> {{ safe($siteAddress) }}</p>
            <p><strong>County:</strong> {{ safe($county) }}</p>
            <p><strong>APN:</strong> {{ safe($record['apn'] ?? 'N/A') }}</p>
            <p><strong>Legal Description:</strong> {{ safe($record['legalDescription'] ?? 'N/A') }}</p>
            <p><strong>Subdivision:</strong> {{ safe($record['subdivision'] ?? 'N/A') }}</p>
            <p><strong>Lot:</strong> {{ safe($record['lot'] ?? 'N/A') }}</p>
            <p><strong>Block:</strong> {{ safe($record['block'] ?? 'N/A') }}</p>

            {{-- Loan Information --}}
            <h4> Loan Information</h4>
            <p><strong>Lender Name:</strong> {{ safe($record['lenderName'] ?? 'N/A') }}</p>
            <p><strong>Lender Type:</strong> {{ safe($record['lenderType'] ?? 'N/A') }}</p>
            <p><strong>Loan Amount:</strong> {{ safe($record['loanAmount'] ?? 'N/A') }}</p>
            <p><strong>Loan Type:</strong> {{ safe($record['loanType'] ?? 'N/A') }}</p>
            <p><strong>Loan Term:</strong> {{ safe($record['loanTerm'] ?? 'N/A') }}</p>
            <p><strong>Interest Rate:</strong> {{ safe($record['interestRate'] ?? 'N/A') }}</p>
            <p><strong>Mortgage Date:</strong> {{ safe($record['mortgageDate'] ?? 'N/A') }}</p>

            {{-- Assessor Link --}}
            <h4>Linked Assessor Record</h4>
            <p><strong>Tax Year:</strong> {{ safe($record['taxYear'] ?? 'N/A') }}</p>
            <p><strong>Parcel Number:</strong> {{ safe($record['parcelNumber'] ?? 'N/A') }}</p>
            <p><strong>Assessed Value:</strong> {{ safe($record['assessedValue'] ?? 'N/A') }}</p>

            {{-- Miscellaneous Info --}}
            <h4>Additional Info</h4>
            <p><strong>Property Use Code:</strong> {{ safe($record['propertyUseCode'] ?? 'N/A') }}</p>
            <p><strong>FIPS Code:</strong> {{ safe($record['fipsMunicipalCode'] ?? 'N/A') }}</p>
            <p><strong>Municipal Name:</strong> {{ safe($record['municipalName'] ?? 'N/A') }}</p>
            <p><strong>Recording County:</strong> {{ safe($record['recordingCounty'] ?? 'N/A') }}</p>
        </div>
    @empty
        <p>No deed/recorder records found.</p>
    @endforelse

