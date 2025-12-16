{{-- Corporate Affiliations --}}
<section class="mt-8">
    <h2>Corporate Affiliations ({{ recordCount($report['corporateAffiliations']) }})</h2>

    @forelse($report['corporateAffiliations'] as $idx => $aff)
        <div class="affiliation-record mb-4 p-3 border rounded">
            {{-- Header --}}
            <h3>{{ $idx + 1 }} Corporate Affiliation Record{{ count($report['corporateAffiliations']) > 1 ? 's' : '' }} found:</h3>

            {{-- Company & Status --}}
            <p>
                <strong>{{ $aff['companyName'] ?? '—' }}</strong>
                @if(!empty($aff['status']))
                    – <em>{{ $aff['status'] }}</em>
                @endif
            </p>

            {{-- Principal / Business ID --}}
            <p>
                {{ $aff['principalName'] ?? '—' }}
                @if(!empty($aff['businessId']))
                    Business ID: {{ $aff['businessId'] }}
                @endif
            </p>

            {{-- Jurisdiction & Corp Info --}}
            <p>
                @if(!empty($aff['state'])) State: {{ $aff['state'] }} @endif
                @if(!empty($aff['corporationNumber']))
                    Corporation Number: {{ $aff['corporationNumber'] }}
                @endif
            </p>

            {{-- Dates & Type --}}
            <p>
                @if(!empty($aff['filingDate']))
                    Filing Date: {{ $aff['filingDate'] }}
                @endif
                @if(!empty($aff['recordDate']))
                    Record Date: {{ $aff['recordDate'] }}
                @endif
            </p>
            @if(!empty($aff['recordType']))
                <p>Record Type: {{ $aff['recordType'] }}</p>
            @endif
        </div>
    @empty
        <p><em>No corporate affiliations found.</em></p>
    @endforelse
</section>