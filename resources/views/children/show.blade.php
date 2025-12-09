
@section('title', $child->name)

<x-layouts.app>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <div class="flex items-center space-x-6">
            <div class="w-20 h-20 rounded-full overflow-hidden bg-primary-500 flex items-center justify-center text-white text-2xl font-bold">
                @if($child->profile_photo)
                    <img src="{{ asset('storage/' . $child->profile_photo) }}" 
                         alt="{{ $child->name }}"
                         class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($child->name, 0, 1)) }}
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-neutral-900">{{ $child->name }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="badge badge-{{ $child->gender === 'male' ? 'info' : 'primary' }}">{{ ucfirst($child->gender) }}</span>
                    <span class="text-neutral-600">{{ $child->age }} years old</span>
                    <span class="text-neutral-600">ID: #{{ $child->id }}</span>
                    @if($child->is_active)
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
                    <p class="text-neutral-900">{{ $child->date_of_birth->format('F d, Y') }} ({{ $child->age }} years)</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-neutral-600">Blood Group</label>
                    <p class="text-neutral-900">{{ $child->blood_group ?? 'Not specified' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-neutral-600">Height</label>
                    <p class="text-neutral-900">{{ $child->height_cm ? $child->height_cm . ' cm' : 'Not recorded' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-neutral-600">Weight</label>
                    <p class="text-neutral-900">{{ $child->weight_kg ? $child->weight_kg . ' kg' : 'Not recorded' }}</p>
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
                @if($child->currentRoomAssignment)
                <div>
                    <label class="text-sm font-medium text-neutral-600">Current Room</label>
                    <p class="text-neutral-900">
                        {{ $child->currentRoomAssignment->roomAllocation->facility->name }}<br>
                        <span class="text-sm text-neutral-600">{{ $child->currentRoomAssignment->roomAllocation->room_number }}</span>
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-neutral-600">Room Assignment Date</label>
                    <p class="text-neutral-900">{{ $child->currentRoomAssignment->assigned_date->format('F d, Y') }}</p>
                </div>
                @else
                <div>
                    <label class="text-sm font-medium text-neutral-600">Current Room</label>
                    <p class="text-neutral-600">Not assigned to a room</p>
                </div>
                @endif
                
                @if($child->currentEducation)
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
        
        @if($child->guardian_info)
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
                <button class="py-2 mx-2 border-b-2 font-medium btn btn-primary" onclick="showTab('education')" id="tab-education">Education</button>
                <button class="py-2 mx-2 border-b-2 font-medium btn btn-primary"  onclick="showTab('talents')" id="tab-talents">Talents & Interests</button>
                <button class="py-2 mx-2 border-b-2 font-medium btn btn-primary"  onclick="showTab('milestones')" id="tab-milestones">Milestones</button>
                <button class="py-2 mx-2 border-b-2 font-medium btn btn-primary"  onclick="showTab('documents')" id="tab-documents">Documents</button>
            </nav>
        </div>

        <!-- Education Tab -->
        <div id="content-education" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-neutral-900">Education History</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#educationModal" onclick="openEducationModal()">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add Education
                </button>
            </div>
            
            @if($child->educationHistories->count() > 0)
            <div class="space-y-4">
                @foreach($child->educationHistories as $education)
                <div class="border border-neutral-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium text-neutral-900">{{ $education->school_name }}</h4>
                            <p class="text-sm text-neutral-600">{{ ucfirst($education->education_level) }}</p>
                            <p class="text-sm text-neutral-600">{{ $education->start_date->format('M Y') }} - {{ $education->end_date ? $education->end_date->format('M Y') : 'Present' }}</p>
                        </div>
                        <span class="badge badge-{{ $education->status === 'enrolled' ? 'success' : 'info' }}">
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
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#talentsModal" onclick="openTalentModal()">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add Talent
                </button>
            </div>
            
            @if($child->talentsInterests->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($child->talentsInterests->where('is_active', true) as $talent)
                <div class="border border-neutral-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-medium text-neutral-900">{{ $talent->talent_name }}</h4>
                        <span class="badge badge-info">{{ ucfirst($talent->category) }}</span>
                    </div>
                    <p class="text-sm text-neutral-600 mt-1">Level: {{ ucfirst($talent->level) }}</p>
                    @if($talent->description)
                    <p class="text-sm text-neutral-700 mt-2">{{ $talent->description }}</p>
                    @endif
                    <p class="text-xs text-neutral-500 mt-2">Recorded by: {{ $talent->recordedBy->name }}</p>
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
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#milestoneModal" onclick="openMilestoneModal()">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add Milestone
                </button>
            </div>
            
            @if($child->milestones->count() > 0)
            <div class="space-y-4">
                @foreach($child->milestones->sortByDesc('date_recorded') as $milestone)
                <div class="border border-neutral-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium text-neutral-900">{{ $milestone->title }}</h4>
                            <p class="text-sm text-neutral-600">{{ $milestone->date_recorded->format('F d, Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="badge badge-{{ $milestone->type === 'growth' ? 'success' : ($milestone->type === 'medical' ? 'warning' : 'info') }}">
                                {{ $milestone->type_label }}
                            </span>
                            @if($milestone->requires_attention)
                            <span class="badge badge-danger">Needs Attention</span>
                            @endif
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-neutral-700">{{ $milestone->description }}</p>
                    @if($milestone->formatted_data)
                    <div class="mt-2 p-2 bg-neutral-50 rounded text-sm">
                        @foreach($milestone->formatted_data as $key => $value)
                        <div class="flex justify-between">
                            <span class="text-neutral-600">{{ $key }}:</span>
                            <span class="text-neutral-900">{{ $value }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <p class="text-xs text-neutral-500 mt-2">Recorded by: {{ $milestone->recordedBy->name }}</p>
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
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#documentModal" onclick="openDocumentModal()">
                    <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                    Upload Document
                </button>
            </div>
            
            @if($child->documents->count() > 0)
            <div class="space-y-4">
                @foreach($child->documents as $document)
                <div class="border border-neutral-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-neutral-900">{{ $document->title }}</h4>
                            <p class="text-sm text-neutral-600">{{ $document->type }} • {{ $document->file_size }}</p>
                            <p class="text-sm text-neutral-600">Uploaded by: {{ $document->uploadedBy->name }} on {{ $document->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('documents.download', $document) }}" class="text-blue-600 hover:text-blue-800">
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

    <!-- Modals for Adding Records go Here (Education, Talent,Milestone, Document) -->
    <div class="modal fade" id="educationModal" tabindex="-1" aria-labelledby="educationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">Create New Education Record
                    </div>
                    <div class="modal-body">
                        <form id="educationForm" method="POST" action="{{ route('children.education-record', $child) }}">
                            @csrf
                            Education Records
                            <!-- Form fields for education record -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
            </div>
        </div>

    </div>
    <!--Modal for Talents-->
        <div class="modal fade" id="talentsModal" tabindex="-1" aria-labelledby="talentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">Create New Talent Record
                    </div>
                    <div class="modal-body">
                        <form id="talentForm" method="POST" action="{{ route('children.assign-talent', $child) }}">
                            @csrf
                            Talents/Interest Records
                            <!-- Form fields for education record -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
            </div>
        </div>
    </div>
    <!--modal for Milestone-->
        <div class="modal fade" id="milestoneModal" tabindex="-1" aria-labelledby="milestoneModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">Create New Milestone Record
                    </div>
                    <div class="modal-body">
                        <form id="milestoneForm" method="POST" action="{{ route('children.education-record', $child) }}">
                            @csrf
                            Milestone Records
                            <!-- Form fields for education record -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
            </div>
        </div>

    </div>
    <!--modal for Document-->
        <div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">Create New Document Record
                    </div>
                    <div class="modal-body">
                        <form id="documentForm" method="POST" action="{{ route('children.education-record', $child) }}">
                            @csrf
                            Documentucation Records
                            <!-- Form fields for education record -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
            </div>
        </div>

    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const contents = document.querySelectorAll('[id^="content-"]');
    contents.forEach(content => content.classList.add('hidden'));
    
    // Remove active state from all tabs
    const tabs = document.querySelectorAll('[id^="tab-"]');
    tabs.forEach(tab => {
        tab.classList.remove('border-primary-500', 'text-primary-600');
        tab.classList.add('border-transparent', 'text-neutral-600');
    });
    
    // Show selected tab content
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
    
    // Add active state to selected tab
    document.getElementById(`tab-${tabName}`).classList.add('border-primary-500', 'text-primary-600');
    document.getElementById(`tab-${tabName}`).classList.remove('border-transparent', 'text-neutral-600');
}

function openEducationModal() {
    // Implementation for education modal
    console.log('Open education modal');
}

function openTalentModal() {
    // Implementation for talent modal
    console.log('Open talent modal');
}

function openMilestoneModal() {
    // Implementation for milestone modal
    console.log('Open milestone modal');
}

function openDocumentModal() {
    // Implementation for document modal
    console.log('Open document modal');
}
</script>
</x-layouts.app>