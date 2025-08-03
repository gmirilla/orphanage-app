<x-layouts.app>
<div class="container mt-5">
    <div class="card shadow-sm mx-auto">
        <div class="card-body">
            <img src="{{ asset('storage/' . $staff->profilephoto) }}" class="rounded-circle mb-3" alt="Profile Photo" width="120" height="120">
            <h4 class="card-title mb-2">{{ $staff->getFullname() }}</h4>
            <p class="text-muted"><strong>Age:</strong> {{ $staff->getAge() }}</p>
            <p><strong>ID Type:</strong> {{ $staff->identificationtype }}</p>
            <p><strong>ID No:</strong> {{ $staff->identificationno }}</p>
            <p><strong>Status:</strong> {{ $staff->active ? 'Active' : 'Inactive' }}</p>
            <p><strong>Notes:</strong> {{ $staff->staffnotes ?? 'None' }}</p>
        </div>
    </div>
</div>

</x-layouts.app>
