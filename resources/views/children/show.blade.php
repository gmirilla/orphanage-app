@section('title', $child->name)

<x-layouts.app>
    <div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="container>
        <!-- Header -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <div class="flex items-center space-x-6">
                <div
                    class="w-20 h-20 rounded-full overflow-hidden bg-primary-500 flex items-center justify-center text-white text-2xl font-bold">
                    @if ($child->profile_photo)
                        <img src="{{ asset('storage/' . $child->profile_photo) }}" alt="{{ $child->name }}"
                            class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($child->name, 0, 1)) }}
                    @endif
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-neutral-900">{{ $child->name }}</h1>
                    <div class="flex items-center space-x-4 mt-2">
                        <span
                            class="badge badge-{{ $child->gender === 'male' ? 'info' : 'primary' }}">{{ ucfirst($child->gender) }}</span>
                        <span class="text-neutral-600">{{ $child->age }} years old</span>
                        <span class="text-neutral-600">ID: #{{ $child->id }}</span>
                        @if ($child->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('children.edit', $child) }}" class="btn btn-secondary">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('children.profile', $child) }}" class="btn btn-primary">
                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                        Profile Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Key Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Personal Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Date of Birth</label>
                        <p class="text-neutral-900">{{ $child->date_of_birth->format('F d, Y') }} ({{ $child->age }}
                            years)</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Blood Group</label>
                        <p class="text-neutral-900">{{ $child->blood_group ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Height</label>
                        <p class="text-neutral-900">
                            {{ $child->height_cm ? $child->height_cm . ' cm' : 'Not recorded' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Weight</label>
                        <p class="text-neutral-900">
                            {{ $child->weight_kg ? $child->weight_kg . ' kg' : 'Not recorded' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Special Needs</label>
                        <p class="text-neutral-900">{{ $child->special_needs ?? 'None' }}</p>
                    </div>
                </div>
            </div>

            <!-- Admission Information -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Admission Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Admission Date</label>
                        <p class="text-neutral-900">{{ $child->admission_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Admission Source</label>
                        <p class="text-neutral-900">{{ ucfirst(str_replace('_', ' ', $child->admission_source)) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Guardianship Status</label>
                        <p class="text-neutral-900">{{ $child->guardianship_status ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Admitted By</label>
                        <p class="text-neutral-900">{{ $child->admittedBy->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-neutral-600">Length of Stay</label>
                        <p class="text-neutral-900">{{ $child->admission_date->diffForHumans(null, true) }}</p>
                    </div>
                </div>
            </div>

            <!-- Current Status -->
            <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Current Status</h3>
                <div class="space-y-3">
                    @if ($child->currentRoomAssignment)
                        <div>
                            <label class="text-sm font-medium text-neutral-600">Current Room</label>
                            <p class="text-neutral-900">
                                {{ $child->currentRoomAssignment->roomAllocation->facility->name }}<br>
                                <span
                                    class="text-sm text-neutral-600">{{ $child->currentRoomAssignment->roomAllocation->room_number }}</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-neutral-600">Room Assignment Date</label>
                            <p class="text-neutral-900">
                                {{ $child->currentRoomAssignment->assigned_date->format('F d, Y') }}</p>
                        </div>
                    @else
                        <div>
                            <label class="text-sm font-medium text-neutral-600">Current Room</label>
                            <p class="text-neutral-600">Not assigned to a room</p>
                        </div>
                    @endif

                    @if ($child->currentEducation)
                        <div>
                            <label class="text-sm font-medium text-neutral-600">Current Education</label>
                            <p class="text-neutral-900">{{ $child->currentEducation->school_name }}</p>
                            <p class="text-sm text-neutral-600">{{ $child->currentEducation->education_level }}</p>
                        </div>
                    @else
                        <div>
                            <label class="text-sm font-medium text-neutral-600">Current Education</label>
                            <p class="text-neutral-600">No active education record</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Background Summary -->
        <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Background Summary</h3>
            <div class="prose max-w-none">
                <p class="text-neutral-700 leading-relaxed">{{ $child->background_summary }}</p>
            </div>

            @if ($child->guardian_info)
                <div class="mt-4 p-4 bg-neutral-50 rounded-lg">
                    <h4 class="font-medium text-neutral-900 mb-2">Guardian Information</h4>
                    <p class="text-neutral-700">{{ $child->guardian_info }}</p>
                </div>
            @endif
        </div>

        <!-- Tabs Content -->
        <div class="bg-white rounded-lg shadow-md border border-neutral-100">
            <div class="border-b border-neutral-200">
                <nav class="flex space-x-8 px-6">
                    <button class="py-2 mx-2 border-b-2 font-medium btn btn-primary" onclick="showTab('education')"
                        id="tab-education">Education</button>
                    <button class="py-2 mx-2 border-b-2 font-medium btn btn-primary" onclick="showTab('talents')"
                        id="tab-talents">Talents & Interests</button>
                    <button class="py-2 mx-2 border-b-2 font-medium btn btn-primary" onclick="showTab('milestones')"
                        id="tab-milestones">Milestones</button>
                    <button class="py-2 mx-2 border-b-2 font-medium btn btn-primary" onclick="showTab('documents')"
                        id="tab-documents">Documents</button>
                </nav>
            </div>

            <!-- Education Tab -->
            <div id="content-education" class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Education History</h3>
                    <button class="btn btn-primary btn-sm" onclick="openModal('educationModal')">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Add Education
                    </button>
                </div>


            @if ($child->educationHistories->count() > 0)
                <div class="space-y-4">
                    @foreach ($child->educationHistories as $education)
                        <div class="border border-neutral-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-neutral-900">{{ $education->school_name }}</h4>
                                    <p class="text-sm text-neutral-600">{{ ucfirst($education->education_level) }}
                                    </p>
                                    <p class="text-sm text-neutral-600">
                                        {{ $education->start_date->format('M Y') }} -
                                        {{ $education->end_date ? $education->end_date->format('M Y') : 'Present' }}
                                    </p>
                                </div>
                                <span
                                    class="badge badge-{{ $education->status === 'enrolled' ? 'success' : 'info' }}">
                                    {{ ucfirst($education->status) }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-neutral-700">{{ $education->academic_progress }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-neutral-600 text-center py-8">No education records found.</p>
            @endif
        </div>

        <!-- Talents Tab -->
        <div id="content-talents" class="p-6 hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-neutral-900">Talents & Interests</h3>
                <button class="btn btn-primary btn-sm" onclick="openModal('talentsModal')">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add Talent
                </button>
            </div>

            @if ($child->talentsInterests->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($child->talentsInterests->where('is_active', true) as $talent)
                        <div class="border border-neutral-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <h4 class="font-medium text-neutral-900">{{ $talent->talent_name }}</h4>
                                <span class="badge badge-info">{{ ucfirst($talent->category) }}</span>
                            </div>
                            <p class="text-sm text-neutral-600 mt-1">Level: {{ ucfirst($talent->level) }}</p>
                            @if ($talent->description)
                                <p class="text-sm text-neutral-700 mt-2">{{ $talent->description }}</p>
                            @endif
                            <p class="text-xs text-neutral-500 mt-2">Recorded by: {{ $talent->recordedBy->name }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-neutral-600 text-center py-8">No talents or interests recorded.</p>
            @endif
        </div>

        <!-- Milestones Tab -->
        <div id="content-milestones" class="p-6 hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-neutral-900">Development Milestones</h3>
                <button class="btn btn-primary btn-sm" onclick="openModal('milestoneModal')">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add Milestone
                </button>
            </div>

            @if ($child->milestones->count() > 0)
                <div class="space-y-4">
                    @foreach ($child->milestones->sortByDesc('date_recorded') as $milestone)
                        <div class="border border-neutral-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-neutral-900">{{ $milestone->title }}</h4>
                                    <p class="text-sm text-neutral-600">
                                      <b> Date Recorded :</b> {{ $milestone->date_recorded->format('F d, Y') }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="badge bg-{{ $milestone->type == 'growth' ? 'success' : ($milestone->type == 'medical' ? 'warning' : 'info') }}">
                                        {{ $milestone->type }}
                                    </span>
                                    @if ($milestone->requires_attention)
                                        <span class="badge badge-danger">Needs Attention</span>
                                    @endif
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-neutral-700"> <b> Description : </b>  {{ $milestone->description }}</p>
                            @if ($milestone->formatted_data)
                                <div class="mt-2 p-2 bg-neutral-50 rounded text-sm">
                                    @foreach ($milestone->formatted_data as $key => $value)
                                        <div class="flex justify-between">
                                            <span class="text-neutral-600">{{ $key }}:</span>
                                            <span class="text-neutral-900">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <p class="text-xs text-neutral-500 mt-2">Recorded by:
                                {{ $milestone->recordedBy->name }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-neutral-600 text-center py-8">No milestones recorded.</p>
            @endif
        </div>

        <!-- Documents Tab -->
        <div id="content-documents" class="p-6 hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-neutral-900">Documents</h3>
                <button class="btn btn-primary btn-sm" onclick="openModal('documentModal')">
                    <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                    Upload Document
                </button>
            </div>

            @if ($child->documents->count() > 0)
                <div class="space-y-4">
                    @foreach ($child->documents as $document)
                        <div class="border border-neutral-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-neutral-900">{{ $document->title }}</h4>
                                    <p class="text-sm text-neutral-600">{{ $document->type }} •
                                        {{ $document->file_size }}</p>
                                    <p class="text-sm text-neutral-600">Uploaded by:
                                        {{ $document->uploadedBy->name }} on
                                        {{ $document->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('documents.download', $document) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-neutral-600 text-center py-8">No documents uploaded.</p>
            @endif
        </div>
    </div>

    </div>{{-- end tab contents --}}

    {{-- ── Education Modal ── --}}
    <div id="educationModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-zinc-100">
                <h3 class="text-lg font-semibold text-zinc-900">Add Education Record</h3>
                <button onclick="closeModal('educationModal')" class="text-zinc-400 hover:text-zinc-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form method="POST" action="{{ route('children.education-record', $child) }}">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="form-label">Name of School</label>
                        <input type="text" name="school_name" class="form-input" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Education Level</label>
                            <select name="education_level" class="form-input" required>
                                <option value="primary">Primary</option>
                                <option value="secondary">Secondary</option>
                                <option value="tertiary">Tertiary</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Grade / Class</label>
                            <input type="text" name="grade" class="form-input" placeholder="e.g. JSS 2">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Academic Progress Note</label>
                        <input type="text" name="academic_progress" class="form-input" required placeholder="Brief note on progress">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input" required>
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

    {{-- ── Talents Modal ── --}}
    <div id="talentsModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-zinc-100">
                <h3 class="text-lg font-semibold text-zinc-900">Add Talent / Interest</h3>
                <button onclick="closeModal('talentsModal')" class="text-zinc-400 hover:text-zinc-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form method="POST" action="{{ route('children.assign-talent', $child) }}">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="form-label">Talent / Interest Name</label>
                        <input type="text" name="talent_name" class="form-input" required placeholder="e.g. Football, Piano">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Category</label>
                            <select name="category" class="form-input">
                                <option value="art">Art</option>
                                <option value="music">Music</option>
                                <option value="sports">Sports</option>
                                <option value="academics">Academics</option>
                                <option value="technical">Technical</option>
                                <option value="social">Social</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Proficiency Level</label>
                            <select name="level" class="form-input">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-input" placeholder="Short remarks">
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
                    <button type="button" onclick="closeModal('talentsModal')" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Talent</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Milestone Modal ── --}}
    <div id="milestoneModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-zinc-100">
                <h3 class="text-lg font-semibold text-zinc-900">Add Development Milestone</h3>
                <button onclick="closeModal('milestoneModal')" class="text-zinc-400 hover:text-zinc-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form method="POST" action="{{ route('children.addmilestone', $child) }}">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Type</label>
                        <select name="type" class="form-input" required>
                            <option value="growth">Growth</option>
                            <option value="developmental">Developmental</option>
                            <option value="medical">Medical</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-input" required>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-6 border-t border-zinc-100">
                    <button type="button" onclick="closeModal('milestoneModal')" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Milestone</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Document Modal ── --}}
    <div id="documentModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-zinc-100">
                <h3 class="text-lg font-semibold text-zinc-900">Upload Document</h3>
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
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        // Close any modal by clicking backdrop
        document.querySelectorAll('[id$="Modal"]').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) closeModal(this.id);
            });
        });

        function showTab(tabName) {
            document.querySelectorAll('[id^="content-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.classList.remove('border-[#324b45]', 'text-[#324b45]');
                el.classList.add('border-transparent', 'text-neutral-600');
            });
            document.getElementById('content-' + tabName).classList.remove('hidden');
            document.getElementById('tab-' + tabName).classList.add('border-[#324b45]', 'text-[#324b45]');
            document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-neutral-600');
        }

        const today = new Date().toISOString().split('T')[0];
        const endDateEl = document.getElementById('end_date');
        if (endDateEl) endDateEl.setAttribute('max', today);
    </script>

</x-layouts.app>
