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
@php
    $counter=0;
@endphp
<div class="card">
    <div class="card-header mb-3"><h5>LIST OF STAFF</h5></div>
    <div class="card-body mb-3">
        <a href="{{route('register_newstaff')}}" class="btn btn-primary">New Staff Member</a>
    </div>
    <div class="card-body responsive">
        <table class="table table-striped table-hover" id="listofstaff">
            <thead>
                <th>S/No</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Active</th>
                <th>Action(s)</th>
            </thead>
            <tbody>
                @forelse ($staffs as $staff )
                    @php
                        $counter++;
                    @endphp

                    <tr onclick="window.location='{{ route('view_staff',['id' => $staff->id])}}'" style="cursor: pointer;">                   
                        <td>{{$counter}}</td>
                        <td>{{$staff->getFullname()}}</td>
                        <td>{{$staff->gender}}</td>
                        <td>{{$staff->getAge()}}</td>
                        <td>{{$staff->active}}</td>
                        <td></td>
                    </tr>
   
                @empty
                    <tr>
                        <td>#</td>
                        <td>No staff registered on system</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    
                @endforelse
            </tbody>
        </table>

    </div>
</div>
<script>
$(document).ready(function() {
    $('#listofstaff').DataTable({
        dom: 'Bfrtip',
        pageLength: 200,
        order: [[1, 'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Listofstaff',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });
});

</script>
</x-layouts.app>