
@section('title', 'Add New Facility')

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
<div class="container">
    <h2>Create New Facility</h2>

    <form action="{{ route('facilities.store') }}" method="POST">
        @csrf

        <!-- Facility Name -->
        <div class="mb-3">
            <label for="name" class="form-label" required>Facility Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Facility Type -->
        <div class="mb-3">
            <label for="type" class="form-label">Facility Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">-- Select Type --</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Assigned Admin -->
        <div class="mb-3">
            <label for="admin_id" class="form-label">Assign Admin</label>
            <select name="admin_id" id="admin_id" class="form-select" required>
                <option value="">-- Select Admin --</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                        {{ $admin->name }}
                    </option>
                @endforeach
            </select>
            @error('admin_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Location -->
        <div class="mb-3">
            <label for="description" class="form-label">Description/Location</label>
            <input type="text" name="description" id="description" class="form-control" value="{{ old('description') }}">
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Capacity -->
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="form-control" value="{{ old('capacity') }}">
            @error('capacity')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Facility</button>
    </form>
</div>

</x-layouts.app>