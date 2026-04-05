<x-layouts.app>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-neutral-900">{{ $child->name }}</h2>
            <p class="text-sm text-neutral-600">Full Profile</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('children.edit', $child) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('children.show', $child) }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left: Core Info -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                @if($child->profile_photo)
                    <img src="{{ Storage::url($child->profile_photo) }}" alt="{{ $child->name }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-4">
                @else
                    <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold mx-auto mb-4">
                        {{ strtoupper(substr($child->name, 0, 1)) }}
                    </div>
                @endif
                <h3 class="text-lg font-semibold text-center text-neutral-900">{{ $child->name }}</h3>
                <p class="text-sm text-center text-neutral-500">Age {{ $child->age }} &bull; {{ ucfirst($child->gender) }}</p>

                <dl class="mt-4 space-y-2 text-sm">
                    <div><dt class="text-neutral-500">Date of Birth</dt><dd>{{ $child->date_of_birth->format('M d, Y') }}</dd></div>
                    <div><dt class="text-neutral-500">Blood Group</dt><dd>{{ $child->blood_group ?? '—' }}</dd></div>
                    <div><dt class="text-neutral-500">Height</dt><dd>{{ $child->height_cm ? $child->height_cm . ' cm' : '—' }}</dd></div>
                    <div><dt class="text-neutral-500">Weight</dt><dd>{{ $child->weight_kg ? $child->weight_kg . ' kg' : '—' }}</dd></div>
                    <div><dt class="text-neutral-500">Special Needs</dt><dd>{{ $child->special_needs ?? '—' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="font-semibold text-neutral-900 mb-3">Admission Info</h3>
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-neutral-500">Admitted</dt><dd>{{ $child->admission_date->format('M d, Y') }}</dd></div>
                    <div><dt class="text-neutral-500">Source</dt><dd>{{ $child->admission_source }}</dd></div>
                    <div><dt class="text-neutral-500">Admitted By</dt><dd>{{ $child->admittedBy->name ?? '—' }}</dd></div>
                    <div><dt class="text-neutral-500">Guardianship</dt><dd>{{ $child->guardianship_status ?? '—' }}</dd></div>
                    <div><dt class="text-neutral-500">Room</dt><dd>{{ $child->currentRoomAssignment?->roomAllocation?->room_number ?? 'Unassigned' }}</dd></div>
                </dl>
            </div>
        </div>

        <!-- Right: Details -->
        <div class="md:col-span-2 space-y-4">
            <!-- Background -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="font-semibold text-neutral-900 mb-2">Background Summary</h3>
                <p class="text-sm text-neutral-700">{{ $child->background_summary }}</p>
            </div>

            <!-- Education -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="font-semibold text-neutral-900 mb-3">Education History</h3>
                @if($child->educationHistories->count())
                <table class="data-table w-full text-sm">
                    <thead><tr><th>School</th><th>Level</th><th>Status</th><th>Period</th></tr></thead>
                    <tbody>
                        @foreach($child->educationHistories as $edu)
                        <tr>
                            <td>{{ $edu->school_name }}</td>
                            <td>{{ ucfirst($edu->education_level) }}</td>
                            <td><span class="badge {{ $edu->status === 'enrolled' ? 'badge-success' : 'badge-secondary' }}">{{ ucfirst($edu->status) }}</span></td>
                            <td>{{ $edu->start_date?->format('M Y') }} — {{ $edu->end_date?->format('M Y') ?? 'Present' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-sm text-neutral-500">No education records.</p>
                @endif
            </div>

            <!-- Talents -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="font-semibold text-neutral-900 mb-3">Talents & Interests</h3>
                @if($child->activeTalents->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($child->activeTalents as $talent)
                    <span class="badge badge-primary">{{ $talent->talent_name }} ({{ ucfirst($talent->level) }})</span>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-neutral-500">No talents recorded.</p>
                @endif
            </div>

            <!-- Milestones -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="font-semibold text-neutral-900 mb-3">Recent Milestones</h3>
                @if($child->recentMilestones->count())
                <div class="space-y-3">
                    @foreach($child->recentMilestones as $milestone)
                    <div class="border-l-4 border-blue-400 pl-3">
                        <p class="font-medium text-sm text-neutral-900">{{ $milestone->title }}</p>
                        <p class="text-xs text-neutral-500">{{ ucfirst($milestone->type) }} &bull; {{ $milestone->date_recorded?->format('M d, Y') }}</p>
                        <p class="text-sm text-neutral-700 mt-1">{{ $milestone->description }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-neutral-500">No milestones recorded.</p>
                @endif
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
