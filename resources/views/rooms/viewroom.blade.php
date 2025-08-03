<x-layouts.app>
    <div class="card">
    <div class="card-header">
        <h4>Room List</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Classification</th>
                    <th>Capacity</th>
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
                    <td>{{ ucfirst($room->status) }}</td>
                    <td>{{ $room->roomnotes }}</td>
                    <td>{{ $room->staffincharge }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</x-layouts.app>