<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
    <div class="card-header mb-3"><h5>LIST OF CHILDREN</h5></div>
    <div class="card-body mb-3">
        <a href="{{route('register_newchild')}}" class="btn btn-primary">Register New Child</a>
    </div>
    <div class="card-body responsive">
        <table class="table table-striped table-hover" id="listofchildren">
            <thead>
                <th>S/No</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Action(s)</th>
            </thead>
            <tbody>
                @forelse ($children as $child )
                    @php
                        $counter++;
                    @endphp

                    <tr>
                        <td>{{$counter}}</td>
                        <td>{{$child->getFullname()}}</td>
                        <td>{{$child->gender}}</td>
                        <td>{{$child->getAge()}}</td>
                        <td></td>
                    </tr>
   
                @empty
                    <tr>
                        <td>#</td>
                        <td>No Children registered on system</td>
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
    $('#listofchildren').DataTable({
        dom: 'Bfrtip',
        pageLength: 200,
        order: [[1, 'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'ListofChildren',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });
});

</script>
</x-layouts.app>