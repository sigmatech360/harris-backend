


    @forelse($records as $index => $record)
        @php
            $owners = $record['currentOwners'] ?? [];
            $siteAddress = $summary['fullAddress'] ?? ($record['siteAddress']['fullAddress'] ?? 'N/A');
            $county = $summary['county'] ?? ($record['siteAddress']['county'] ?? 'N/A');
            $assessedValue = $record['assessedValue'] ?? 'N/A';
            $purchasePrice = $record['mostRecentPurchasePrice'] ?? 'N/A';
            $purchaseDate = $record['mostRecentTransactionDate'] ?? 'N/A';
        @endphp

        <div class="card">
            {{-- Header --}}
            <h3>Assessor Record for {{ safe($record['taxYear'] ?? 'N/A') }} - {{ safe($siteAddress) }}</h3>

            {{-- Current Owners --}}
            <h4>Current Owners ({{ count($owners) }})</h4>
            @forelse($owners as $owner)
                <p>{{ safe($owner['name'] ?? 'N/A') }}</p>
            @empty
                <p>No owners found.</p>
            @endforelse

            {{-- Site Address --}}
            <h4>Site Address</h4>
            <p>{{ safe($siteAddress) }}</p>
            <p>{{ safe($county) }} County</p>

            {{-- Assessed Value --}}
            <h4>Assessed Value</h4>
            <p><strong>Assessed Value:</strong> {{ safe($assessedValue) }}</p>
            <p><strong>Most Recent Purchase Price:</strong> {{ safe($purchasePrice) }}</p>
            <p><strong>Most Recent Transaction Date:</strong> {{ safe($purchaseDate) }}</p>

            {{-- Location Info --}}
            <h4>Location Information</h4>
            <p><strong>County:</strong> {{ safe($record['county'] ?? 'N/A') }}</p>
            <p><strong>Legal Description:</strong> {{ safe($record['legalDescription'] ?? 'Not Specified') }}</p>
            <p><strong>Subdivision:</strong> {{ safe($record['subdivision'] ?? 'Not Specified') }}</p>

            {{-- Assessor Info --}}
            <h4> Assessor Information</h4>
            <p><strong>Arms Length Flag DFS:</strong> {{ safe($record['armsLengthFlagDFS'] ?? 'Not Specified') }}</p>
            <p><strong>Document Type:</strong> {{ safe($record['documentType'] ?? 'Not Specified') }}</p>
            <p><strong>FIPS Municipal Code:</strong> {{ safe($record['fipsMunicipalCode'] ?? 'Not Specified') }}</p>
            <p><strong>Municipal Name:</strong> {{ safe($record['municipalName'] ?? 'Not Specified') }}</p>
            <p><strong>Municipal Use Code:</strong> {{ safe($record['municipalUseCode'] ?? 'Not Specified') }}</p>
            <p><strong>Transfer Date:</strong> {{ safe($record['transferDate'] ?? 'Not Specified') }}</p>
            <p><strong>Transfer Type:</strong> {{ safe($record['transferType'] ?? 'Not Specified') }}</p>

            {{-- Financial Info --}}
            <h4> Financial Information</h4>
            <p><strong>Tax Year:</strong> {{ safe($record['taxYear'] ?? 'N/A') }}</p>
            <p><strong>Assessed Improvement Value:</strong> {{ safe($record['assessedImprovementValue'] ?? 'Not Specified') }}</p>
            <p><strong>Assessed Land Value:</strong> {{ safe($record['assessedLandValue'] ?? 'Not Specified') }}</p>
            <p><strong>Assessed Value:</strong> {{ safe($record['assessedValue'] ?? 'Not Specified') }}</p>
            <p><strong>Full Cash Value:</strong> {{ safe($record['fullCashValue'] ?? 'Not Specified') }}</p>

            {{-- Structural Info --}}
            <h4> Structural Information</h4>
            <p><strong>Year Built:</strong> {{ safe($record['yearBuilt'] ?? 'Not Specified') }}</p>
            <p><strong>Structure Code:</strong> {{ safe($record['structureCode'] ?? 'Not Specified') }}</p>
            <p><strong># of Units:</strong> {{ safe($record['numberOfUnits'] ?? '0') }}</p>
            <p><strong># of Stories:</strong> {{ safe($record['numberOfStories'] ?? '0') }}</p>
            <p><strong>Lot Depth:</strong> {{ safe($record['lotDepth'] ?? 'N/A') }}</p>
            <p><strong>Lot Width:</strong> {{ safe($record['lotWidth'] ?? 'N/A') }}</p>
            <p><strong>Total Sq Ft:</strong> {{ safe($record['totalSquareFeet'] ?? 'N/A') }}</p>
        </div>
    @empty
        <p>No assessor records found.</p>
    @endforelse

