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
    <div class="card-header mb-3"><h5>REGISTER NEW CHILD : {{$child->getFullname()}}<i> (Education & Development )</i></h5></div>
    <div class="card-body mb-3">
    </div>
    <div class="card-body responsive">
        <form action="{{route('addeduinfo')}}" method="post" enctype="multipart/form-data">
            @csrf

        <div class="row mb-3">
            <div class="col-2">
                <label for="schoolname" class="form-label">Current School Name</label>
                <input type="text" name='schoolname' id='schoolname' placeholder="Enter Child's current School name" class="form-control">
            </div>
  
            <div class="col-2">
                <label for="grade" class="form-label">Current Class/Grade</label>
                <input type="text" name='grade' id='grade' placeholder="Enter Child's current Class" class="form-control">
            </div>
            <div class="col-2">
                <label for="academicyear" class="form-label">Year</label>
                <input type="number" name='academicyear' id='academicyear'  placeholder="Enter Year" class="form-control">
            </div>


        </div>
        <div class="row mb-3">
            <div class="col-2">
                <label for="specialneed" class="form-label">Special Needs or Disabilities</label>
                <input type="text" required   name='specialneeds' id='specialneeds' placeholder="Enter Any Special Needs or Disabilities  " class="form-control">
            </div>
            <div class="col-2">
                <label for="hobby" class="form-label">Hobbies/Interest</label>
                <input type="text" name='hobbies' id='hobby' placeholder="Enter a Hobby or Interest" class="form-control">
            </div>
        </div>
        <div class="row">
            
            <div class="col-6">
                <label for="academicnote" class="form-label">Academic Performance Notes</label>
                 <textarea name="academicnote" id="note" cols="30" rows="5" placeholder="Enter any notes or remarks" class="form-control"></textarea>
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