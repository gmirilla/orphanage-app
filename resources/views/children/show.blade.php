<x-layouts.app>
@php
    $genderColor = match($child->gender) {
        'male'   => 'bg-blue-100 text-blue-700',
        'female' => 'bg-pink-100 text-pink-700',
        default  => 'bg-neutral-100 text-neutral-600',
    };
@endphp

<div class="space-y-6">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Hero Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-neutral-100 overflow-hidden">
        <div class="h-2 {{ $child->is_active ? 'bg-[#324b45]' : 'bg-neutral-300' }}"></div>
        <div class="p-6 flex flex-col sm:flex-row sm:items-center gap-5">
            <div class="w-20 h-20 rounded-2xl overflow-hidden bg-[#324b45]/10 flex items-center justify-center text-[#324b45] font-bold text-2xl shrink-0">
                @if($child->profile_photo)
                    <img src="{{ asset('storage/' . $child->profile_photo) }}" alt="{{ $child->name }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($child->name, 0, 2)) }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-neutral-900 truncate">{{ $child->name }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $genderColor }}">{{ ucfirst($child->gender) }}</span>
                    <span class="text-xs text-neutral-500">{{ $child->age }} years old</span>
                    <span class="text-xs text-neutral-400">· ID #{{ $child->id }}</span>
                    @if($child->is_active)
                        <span class="text-xs font-medium text-green-700 bg-green-100 px-2.5 py-0.5 rounded-full">Active</span>
                    @else
                        <span class="text-xs font-medium text-neutral-500 bg-neutral-100 px-2.5 py-0.5 rounded-full">Inactive</span>
                    @endif
                </div>
                @if($child->currentRoomAssignment)
                    <p class="text-xs text-neutral-500 mt-1.5 flex items-center gap-1">
                        <i data-lucide="door-open" class="w-3.5 h-3.5"></i>
                        {{ $child->currentRoomAssignment->roomAllocation->facility->name }} — Room {{ $child->currentRoomAssignment->roomAllocation->room_number }}
                    </p>
                @endif
            </div>
            <div class="flex gap-2 shrink-0">
                @if(auth()->user()->canAccessChildren())
                <a href="{{ route('children.edit', $child) }}" class="btn btn-secondary">
                    <i data-lucide="pencil" class="w-4 h-4 mr-1 inline-block"></i> Edit
                </a>
                @endif
                <a href="{{ route('children.profile', $child) }}" class="btn btn-primary">
                    <i data-lucide="file-text" class="w-4 h-4 mr-1 inline-block"></i> Profile
                </a>
                <a href="{{ route('children.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Personal --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-4">Personal</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs text-neutral-400">Date of Birth</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ $child->date_of_birth->format('d M Y') }} ({{ $child->age }} yrs)</dd>
                </div>
                <div>
                    <dt class="text-xs text-neutral-400">Blood Group</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ $child->blood_group ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-neutral-400">Height / Weight</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">
                        {{ $child->height_cm ? $child->height_cm . ' cm' : '—' }} /
                        {{ $child->weight_kg ? $child->weight_kg . ' kg' : '—' }}
                    </dd>
                </div>
                @if($child->special_needs)
                <div>
                    <dt class="text-xs text-neutral-400">Special Needs</dt>
                    <dd class="text-sm text-neutral-700 mt-0.5">{{ $child->special_needs }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Admission --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-4">Admission</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs text-neutral-400">Admission Date</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ $child->admission_date->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-neutral-400">Length of Stay</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ $child->admission_date->diffForHumans(null, true) }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-neutral-400">Source</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ ucfirst(str_replace('_', ' ', $child->admission_source)) }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-neutral-400">Guardianship</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ ucfirst(str_replace('_', ' ', $child->guardianship_status ?? '—')) }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-neutral-400">Admitted By</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ $child->admittedBy->name ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Current Status --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-4">Current Status</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs text-neutral-400">Room</dt>
                    @if($child->currentRoomAssignment)
                        <dd class="text-sm font-medium text-neutral-900 mt-0.5">
                            {{ $child->currentRoomAssignment->roomAllocation->facility->name }},
                            Room {{ $child->currentRoomAssignment->roomAllocation->room_number }}
                        </dd>
                        <dd class="text-xs text-neutral-400">Since {{ $child->currentRoomAssignment->assigned_date->format('d M Y') }}</dd>
                    @else
                        <dd class="text-sm text-neutral-400 mt-0.5">Not assigned</dd>
                    @endif
                </div>
                <div>
                    <dt class="text-xs text-neutral-400">Education</dt>
                    @if($child->currentEducation)
                        <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ $child->currentEducation->school_name }}</dd>
                        <dd class="text-xs text-neutral-400">{{ ucfirst($child->currentEducation->education_level) }}</dd>
                    @else
                        <dd class="text-sm text-neutral-400 mt-0.5">No active record</dd>
                    @endif
                </div>
                <div>
                    <dt class="text-xs text-neutral-400">Milestones</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ $child->milestones->count() }} recorded</dd>
                </div>
                <div>
                    <dt class="text-xs text-neutral-400">Talents</dt>
                    <dd class="text-sm font-medium text-neutral-900 mt-0.5">{{ $child->talentsInterests->where('is_active', true)->count() }} active</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Background Summary --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-100">
        <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Background Summary</h3>
        <p class="text-sm text-neutral-700 leading-relaxed">{{ $child->background_summary }}</p>
        @if($child->guardian_info)
            <div class="mt-4 p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1.5">Guardian Information</p>
                <p class="text-sm text-neutral-700">{{ $child->guardian_info }}</p>
            </div>
        @endif
    </div>

    {{-- Tabbed Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-neutral-100">
        <div class="border-b border-neutral-200 px-5">
            <nav class="-mb-px flex gap-1 overflow-x-auto">
                @foreach(['education' => 'Education', 'talents' => 'Talents & Interests', 'milestones' => 'Milestones', 'documents' => 'Documents'] as $tabKey => $tabLabel)
                <button onclick="showTab('{{ $tabKey }}')" id="tab-{{ $tabKey }}"
                        class="tab-btn shrink-0 py-3 px-4 text-sm font-medium border-b-2 border-transparent text-neutral-500 hover:text-neutral-700 transition-colors whitespace-nowrap">
                    {{ $tabLabel }}
                </button>
                @endforeach
            </nav>
        </div>

        {{-- Education Tab --}}
        <div id="content-education" class="p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-neutral-900">Education History</h3>
                <button class="btn btn-primary btn-sm" onclick="openModal('educationModal')">
                    <i data-lucide="plus" class="w-3.5 h-3.5 mr-1 inline-block"></i> Add
                </button>
            </div>
            @if($child->educationHistories->count())
                <div class="space-y-3">
                    @foreach($child->educationHistories as $edu)
                    <div class="p-4 rounded-lg border border-neutral-100 bg-neutral-50">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900">{{ $edu->school_name }}</p>
                                <p class="text-xs text-neutral-500 mt-0.5">
                                    {{ ucfirst($edu->education_level) }} ·
                                    {{ $edu->start_date->format('M Y') }} –
                                    {{ $edu->end_date ? $edu->end_date->format('M Y') : 'Present' }}
                                </p>
                                @if($edu->academic_progress)
                                    <p class="text-xs text-neutral-600 mt-1">{{ $edu->academic_progress }}</p>
                                @endif
                            </div>
                            <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full
                                {{ $edu->status === 'enrolled' ? 'bg-green-100 text-green-700' : ($edu->status === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-neutral-100 text-neutral-600') }}">
                                {{ ucfirst($edu->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center text-neutral-400">
                    <i data-lucide="book-open" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                    <p class="text-sm">No education records found.</p>
                </div>
            @endif
        </div>

        {{-- Talents Tab --}}
        <div id="content-talents" class="p-5 hidden">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-neutral-900">Talents & Interests</h3>
                <button class="btn btn-primary btn-sm" onclick="openModal('talentsModal')">
                    <i data-lucide="plus" class="w-3.5 h-3.5 mr-1 inline-block"></i> Add
                </button>
            </div>
            @if($child->talentsInterests->where('is_active', true)->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($child->talentsInterests->where('is_active', true) as $talent)
                    <div class="p-4 rounded-lg border border-neutral-100 bg-neutral-50">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-sm font-medium text-neutral-900">{{ $talent->talent_name }}</p>
                            <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full bg-[#324b45]/10 text-[#324b45]">{{ ucfirst($talent->category) }}</span>
                        </div>
                        <p class="text-xs text-neutral-500">Level: {{ ucfirst($talent->level) }}</p>
                        @if($talent->description)
                            <p class="text-xs text-neutral-600 mt-1">{{ $talent->description }}</p>
                        @endif
                        <p class="text-xs text-neutral-400 mt-1.5">By {{ $talent->recordedBy->name }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center text-neutral-400">
                    <i data-lucide="sparkles" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                    <p class="text-sm">No talents or interests recorded.</p>
                </div>
            @endif
        </div>

        {{-- Milestones Tab --}}
        <div id="content-milestones" class="p-5 hidden">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-neutral-900">Development Milestones</h3>
                <button class="btn btn-primary btn-sm" onclick="openModal('milestoneModal')">
                    <i data-lucide="plus" class="w-3.5 h-3.5 mr-1 inline-block"></i> Add
                </button>
            </div>
            @if($child->milestones->count())
                <div class="space-y-3">
                    @foreach($child->milestones->sortByDesc('date_recorded') as $ms)
                    @php
                        $msColor = match($ms->type) {
                            'growth'      => 'bg-green-100 text-green-700',
                            'medical'     => 'bg-rose-100 text-rose-700',
                            'achievement' => 'bg-blue-100 text-blue-700',
                            default       => 'bg-amber-100 text-amber-700',
                        };
                    @endphp
                    <div class="p-4 rounded-lg border border-neutral-100 bg-neutral-50">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900">{{ $ms->title }}</p>
                                <p class="text-xs text-neutral-500 mt-0.5">
                                    {{ $ms->date_recorded->format('d M Y') }} · By {{ $ms->recordedBy->name }}
                                </p>
                                <p class="text-xs text-neutral-600 mt-1">{{ $ms->description }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1 shrink-0">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $msColor }}">{{ ucfirst($ms->type) }}</span>
                                @if($ms->requires_attention)
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 text-red-700">Needs Attention</span>
                                @endif
                            </div>
                        </div>
                        @if($ms->formatted_data)
                            <div class="mt-2 p-2 bg-white rounded border border-neutral-200 text-xs grid grid-cols-2 gap-x-4 gap-y-1">
                                @foreach($ms->formatted_data as $k => $v)
                                    <span class="text-neutral-500">{{ $k }}:</span>
                                    <span class="text-neutral-900 font-medium">{{ $v }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center text-neutral-400">
                    <i data-lucide="flag" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                    <p class="text-sm">No milestones recorded.</p>
                </div>
            @endif
        </div>

        {{-- Documents Tab --}}
        <div id="content-documents" class="p-5 hidden">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-neutral-900">Documents</h3>
                <button class="btn btn-primary btn-sm" onclick="openModal('documentModal')">
                    <i data-lucide="upload" class="w-3.5 h-3.5 mr-1 inline-block"></i> Upload
                </button>
            </div>
            @if($child->documents->count())
                <div class="space-y-2">
                    @foreach($child->documents as $doc)
                    <div class="flex items-center gap-3 p-3 rounded-lg border border-neutral-100 bg-neutral-50">
                        <i data-lucide="file" class="w-5 h-5 text-neutral-400 shrink-0"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-neutral-900 truncate">{{ $doc->title }}</p>
                            <p class="text-xs text-neutral-500">{{ $doc->type }} · {{ $doc->file_size }} · By {{ $doc->uploadedBy->name }}</p>
                        </div>
                        <a href="{{ route('documents.download', $doc) }}" class="btn btn-secondary btn-sm shrink-0">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center text-neutral-400">
                    <i data-lucide="folder-open" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                    <p class="text-sm">No documents uploaded.</p>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- Education Modal --}}
<div id="educationModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-zinc-100">
            <h3 class="text-base font-semibold text-zinc-900">Add Education Record</h3>
            <button onclick="closeModal('educationModal')" class="text-zinc-400 hover:text-zinc-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="{{ route('children.education-record', $child) }}">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">School Name <span class="text-red-500">*</span></label>
                    <input type="text" name="school_name" class="form-input w-full" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Level <span class="text-red-500">*</span></label>
                        <select name="education_level" class="form-input w-full" required>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="tertiary">Tertiary</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Grade / Class</label>
                        <input type="text" name="grade" class="form-input w-full" placeholder="e.g. JSS 2">
                    </div>
                </div>
                <div>
                    <label class="form-label">Academic Progress Note</label>
                    <input type="text" name="academic_progress" class="form-input w-full" placeholder="Brief note">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-input w-full">
                    </div>
                </div>
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-input w-full" required>
                        <option value="enrolled">Enrolled</option>
                        <option value="completed">Completed</option>
                        <option value="dropped">Dropped</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
                <button type="button" onclick="closeModal('educationModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Record</button>
            </div>
        </form>
    </div>
</div>

{{-- Talents Modal --}}
<div id="talentsModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-zinc-100">
            <h3 class="text-base font-semibold text-zinc-900">Add Talent / Interest</h3>
            <button onclick="closeModal('talentsModal')" class="text-zinc-400 hover:text-zinc-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="{{ route('children.assign-talent', $child) }}">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Talent / Interest Name <span class="text-red-500">*</span></label>
                    <input type="text" name="talent_name" class="form-input w-full" required placeholder="e.g. Football, Piano">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input w-full">
                            <option value="art">Art</option>
                            <option value="music">Music</option>
                            <option value="sports">Sports</option>
                            <option value="academics">Academics</option>
                            <option value="technical">Technical</option>
                            <option value="social">Social</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Level</label>
                        <select name="level" class="form-input w-full">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-input w-full" placeholder="Short remarks">
                </div>
            </div>
            <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
                <button type="button" onclick="closeModal('talentsModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Talent</button>
            </div>
        </form>
    </div>
</div>

{{-- Milestone Modal --}}
<div id="milestoneModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-zinc-100">
            <h3 class="text-base font-semibold text-zinc-900">Add Development Milestone</h3>
            <button onclick="closeModal('milestoneModal')" class="text-zinc-400 hover:text-zinc-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="{{ route('children.addmilestone', $child) }}">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Type <span class="text-red-500">*</span></label>
                    <select name="type" class="form-input w-full" required>
                        <option value="growth">Growth</option>
                        <option value="developmental">Developmental</option>
                        <option value="medical">Medical</option>
                        <option value="achievement">Achievement</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" class="form-input w-full" required></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
                <button type="button" onclick="closeModal('milestoneModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Milestone</button>
            </div>
        </form>
    </div>
</div>

{{-- Document Modal --}}
<div id="documentModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b border-zinc-100">
            <h3 class="text-base font-semibold text-zinc-900">Upload Document</h3>
            <button onclick="closeModal('documentModal')" class="text-zinc-400 hover:text-zinc-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-6">
            <p class="text-sm text-zinc-500">To upload documents for this child, use the <a href="{{ route('documents.create') }}" class="text-[#324b45] underline">Documents section</a>.</p>
        </div>
        <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
            <button type="button" onclick="closeModal('documentModal')" class="btn btn-secondary">Close</button>
            <a href="{{ route('documents.create') }}" class="btn btn-primary">Go to Documents</a>
        </div>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
document.querySelectorAll('[id$="Modal"]').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
});

function showTab(name) {
    document.querySelectorAll('[id^="content-"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-[#324b45]', 'text-[#324b45]');
        btn.classList.add('border-transparent', 'text-neutral-500');
    });
    document.getElementById('content-' + name).classList.remove('hidden');
    const active = document.getElementById('tab-' + name);
    active.classList.add('border-[#324b45]', 'text-[#324b45]');
    active.classList.remove('border-transparent', 'text-neutral-500');
}

document.addEventListener('DOMContentLoaded', () => {
    showTab('education');
    const endDateEl = document.getElementById('end_date');
    if (endDateEl) endDateEl.setAttribute('max', new Date().toISOString().split('T')[0]);
});
</script>
</x-layouts.app>
