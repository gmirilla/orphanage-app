
@section('title', 'Add New Room')

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
    <h2>Create New Room</h2>

    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf

        <!-- Room Details -->
        <div class="mb-3">
            <label for="name" class="form-label" required>Facility Name</label>
            <input type="text" name="description" id="facilityname" class="form-control" value="{{ old('description', $facility->description ?? '') }}" disabled >
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Facility Type -->
        <div class="mb-3">
            <label for="type" class="form-label">Facility Type</label>
            <input type="text" name="type" id ="type" class="form-control" value="{{$facility->type}}" disabled>

            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

 
        
        <!-- Room Number -->

        <div class="mb-3">
            <label for="room_number" class="form-label">Room Number</label>
            <input type="text" name="room_number" id="room_number" class="form-control" value="{{ old('room_number') }}" placeholder="Enter Room Number or Description">
        </div>
                <!-- Bed Count-->
        <div class="mb-3">
            <label for="bed_count" class="form-label">Bed Count</label>
            <input type="number" name="bed_count" id="bed_count" class="form-control" value="{{ old('bed_count') }}" placeholder="How many Beds available in Room">
        </div>
                        <!-- Is Room Active-->
        <div class="mb-3">
            <label for="is_active" class="form-label">Is Active</label>
            <select name="is_active" id="is_active" class="form-control form-select">
                <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>No</option>
            </select>
            <input type="number" id="facilityid" name="facilityid" value="{{ $facility->id }}" hidden>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Room</button>
    </form>
</div>

</x-layouts.app>