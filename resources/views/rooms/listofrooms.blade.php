<x-layouts.app>
    @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
    <div class="card">
    <div class="card-header">
        <h4>Room List</h4>
    </div>
    <div class="card-body mb-3">
        <a href="{{route('register_newstaff')}}" class="btn btn-primary">Register New Room</a>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Classification</th>
                    <th>Capacity</th>
                    <th>No of Occupants</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Staff In Charge</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                <tr>
                    <td>{{ $room->roomnumber }}</td>
                    <td>{{ $room->roomtype }}</td>
                    <td>{{ $room->roomclassification }}</td>
                    <td>{{ $room->capacity }}</td>
                    <td>
                        <span class="badge bg-{{ $room->getoccupancyBadge()}}">{{ count($room->getOccupant()) }}</span>
                    </td>
                    <td>
    @php
        $badgeClass = match($room->status) {
            'available' => 'success',
            'occupied' => 'warning',
            'maintenance' => 'danger',
            default => 'secondary',
        };
    @endphp
    <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($room->status) }}</span>
</td>
                    <td>{{ $room->roomnotes }}</td>
                    <td>{{ $room->staffincharge }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</x-layouts.app>