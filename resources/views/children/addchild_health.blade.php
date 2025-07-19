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
    <div class="card-header mb-3"><h5>REGISTER NEW CHILD : {{$child->getFullname()}}<i> (Health Info )</i></h5></div>
    <div class="card-body mb-3">
    </div>
    <div class="card-body responsive">
        <form action="{{route('addeduinfo')}}" method="post" enctype="multipart/form-data">
            @csrf

        <div class="row mb-3">
            <div class="col-2">
                <label for="allergies" class="form-label">Known Allergies</label>
                <textarea name="allergies" id="" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="col-2">
                <label for="medication" class="form-label">Current Medication(s)</label>
                <textarea name="medication" id="" cols="30" rows="10" class="form-control"></textarea>
            </div>

        </div>
     
        <div class="row mt-3 ">
            <div class="col-auto">
                <input type="text" name="child_id" value="{{$child->id}}" hidden>
                <button type="submit" class="btn btn-primary">Submit</button>
                </div>
        </div>
        </form>

    
    </div>
</div>
</x-layouts.app>