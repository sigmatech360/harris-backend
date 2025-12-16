{{-- Voter Registrations --}}
<section class="mt-8">
    <h2>Voter Registrations ({{ recordCount($report['voterRegistrations']) }})</h2>

    @forelse($report['voterRegistrations'] as $idx => $voter)
        <div class="voter-record mb-4 p-3 border rounded">
            <h3>{{ $idx + 1 }}. {{ $voter['regDate'] ?? '—' }}</h3>
            <ul class="list-inside list-disc ml-4">
                <li><strong>Reg State:</strong> {{ $voter['regState'] ?? '—' }}</li>
                <li><strong>Party:</strong> {{ $voter['party'] ?? '—' }}</li>
                <li><strong>Status:</strong> {{ $voter['status'] ?? '—' }}</li>
                @if(!empty($voter['lastVote']))
                    <li><strong>Last Vote:</strong> {{ $voter['lastVote'] }}</li>
                @endif
            </ul>

            <h4 class="mt-2">Name &amp; Details</h4>
            <p>{{ $voter['name'] ?? '—' }}</p>
            <p>
                {{ $voter['dob'] ? "DOB: {$voter['dob']}" : '' }}
                @if(!empty($voter['ssn'])) – SSN: {{ $voter['ssn'] }} @endif
            </p>

            <h5 class="mt-2">Contact &amp; Address</h5>
            <p>{{ $voter['address'] ?? '—' }}</p>
            @if(!empty($voter['phone']))
                <p><strong>Phone:</strong> {{ $voter['phone'] }}</p>
            @endif
            @if(!empty($voter['misc']))
                <p><em>{{ $voter['misc'] }}</em></p>
            @endif
        </div>
    @empty
        <p><em>No voter registrations found.</em></p>
    @endforelse
</section>