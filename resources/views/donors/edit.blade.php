<x-layouts.app>
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-neutral-900">Edit Donor</h2>
        <p class="text-sm text-neutral-600">Update donor information</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-6 shadow-md border border-neutral-100">
        <form method="POST" action="{{ route('donors.update', $donor) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $donor->name) }}" class="form-input w-full" required>
                </div>
                <div>
                    <label class="form-label">Donor Type <span class="text-red-500">*</span></label>
                    <select name="donor_type" class="form-input w-full" required>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('donor_type', $donor->donor_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $donor->email) }}" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $donor->phone) }}" class="form-input w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="2" class="form-input w-full">{{ old('address', $donor->address) }}</textarea>
                </div>
                <div>
                    <label class="form-label">Tax ID</label>
                    <input type="text" name="tax_id" value="{{ old('tax_id', $donor->tax_id) }}" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input w-full">
                        <option value="active" {{ old('status', $donor->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="preferred" {{ old('status', $donor->status) === 'preferred' ? 'selected' : '' }}>Preferred</option>
                        <option value="inactive" {{ old('status', $donor->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Preferences / Notes</label>
                    <textarea name="preferences" rows="3" class="form-input w-full">{{ old('preferences', $donor->preferences) }}</textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('donors.show', $donor) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Donor</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
