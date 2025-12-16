{{-- Imposters --}}
<section class="mt-8">
    <h2>Imposters (Individuals Who Share SSN) ({{ recordCount($report['imposters']) }})</h2>

    @forelse($report['imposters'] as $idx => $imp)
        <div class="imposter-record mb-4 p-3 border rounded">
            <p>
                <strong>{{ $imp['fullName'] ?? '—' }}</strong>
                @if(!empty($imp['dob']))
                    &nbsp;– {{ $imp['dob'] }} (Age {{ $imp['age'] ?? '—' }})
                @endif
            </p>

            @if(!empty($imp['ssn']))
                <p>
                    SSN: {{ $imp['ssn'] }}
                    @if(!empty($imp['issuedState']) || !empty($imp['issuedBetween']))
                        &nbsp;issued in
                        {{ $imp['issuedState'] ?? '—' }}
                        @if(!empty($imp['issuedBetween']))
                            between {{ $imp['issuedBetween'] }}
                        @endif
                    @endif
                </p>
            @endif
        </div>
    @empty
        <p><em>No imposters found.</em></p>
    @endforelse
</section>
