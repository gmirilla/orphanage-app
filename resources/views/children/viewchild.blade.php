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
    <div class="card-header mb-3"><h5>PROFILE: {{$child->getFullname()}}</h5></div>
    <div class="card-body mb-3">
        <button class="btn btn-primary">Register New Child</button>
    </div>
    <div class="card-body responsive">
        <div class="col-auto">
                
            @if (!empty($child->profilephoto))
           <img src="{{Request::root().('/storage/'.$child->profilephoto)}}" alt="" style="width: 250px; height: 250px;">
           @else
              <img id="uploadedImage" src="{{Request::root().('/storage/nophoto.png')}}" alt="No Photo Uploaded" style="width: 250px; height: 250px;">
           @endif

        </div>
    
    </div>
</div>
</x-layouts.app>