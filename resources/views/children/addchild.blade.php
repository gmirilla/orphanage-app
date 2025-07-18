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
    <div class="card-header mb-3"><h5>REGISTER NEW CHILD : <i> (Basic Info)</i></h5></div>
    <div class="card-body mb-3">
    </div>
    <div class="card-body responsive">
        <form action="{{route('addbasicinfo')}}" method="post" enctype="multipart/form-data">
            @csrf

        <div class="row mb-3">
            <div class="col-2">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" name='fname' id='fname' required placeholder="Enter Child's First Name" class="form-control">
            </div>
            <div class="col-2">
                <label for="mname" class="form-label">Middle Name</label>
                <input type="text" name='mname' id='mname' placeholder="Enter Child's Middle Name or initials" class="form-control">
            </div>
            <div class="col-2">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" required   name='lname' id='lname' placeholder="Enter Child's Last Name" class="form-control">
            </div>

        </div>
        <div class="row mb-3">
            <div class="col-2">
                <label for="dateofbirth" class="form-label">Date of Birth</label>
                <input type="date" name='dateofbirth' id='dateofbirth' placeholder="Date of Birth" class="form-control">
            </div>
            <div class="col-2">
                <label for="gender"  class="form-label">Gender</label>
                <select name="gender" required   id="gender" class="form-select">
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                </select>
            </div>
            <div class="col-2">
                <label for="birthplace"  class="form-label">Place of Birth</label>
                <input type="text" required name='birthplace' id='birthplace' placeholder="Place of Birth" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2">
                <label for="nationalityid" class="form-label">Nationality</label>
                <select name="nationalityid"  required id="nationalityid" class="form-select">
                    @forelse ($countries as $country )
                        <option value="{{$country->id}}">{{$country->name}}</option>
                    @empty
                        <option value="0">No Countries on System</option>
                    @endforelse
                </select>
            </div>
            <div class="col-2">
                <label for="identificationtype"   class="form-label">Identification Type</label>
                <select name="identificationtype" required id="identificationtype" class="form-select">
                    <option value="Birth Certificate">Birth Certificate</option>
                    <option value="National ID">National ID</option>
                    <option value="None">None</option>
                </select>
            </div>
            <div class="col-2">
                <label for="identificationno" class="form-label">Identification Number</label>
                <input type="text" name='identificationno' id='identificationno' placeholder="Identification number" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                <label for="admissiondate" class="form-label">Admission Date</label>
                <input type="date" name='admissiondate' id='admissiondate' placeholder="Admission Date" class="form-control">
            </div>

            <div class="col-2">
                <label for="referalsource" class="form-label">Referal Source</label>
                <select name="referalsource" id="referalsource" class="form-select">
                    <option value="Hospital">Hospital</option>
                    <option value="Social Services">Social Services</option>
                    <option value="Law Enforcement">Law Enforcement</option>
                    <option value="Educational Institution">Educational Institution</option>
                    <option value="Religous Institution">Religous Institution</option>
                    <option value="Emergency Services">Emergency Services</option>
                    <option value="Community Leaders">Community Leaders</option>
                </select>
            </div>

            <div class="col-2">
                   <label for="profilepicture" class="form-label">Upload Picture</label>
                    <input type="file" name="profilepicture" accept="image/*" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <label for="note" class="form-label">Remark(s)</label>
                 <textarea name="note" id="note" cols="30" rows="5" placeholder="Enter any notes or remarks" class="form-control"></textarea>
            </div>
        </div>
        <div class="row mt-3 ">
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Submit</button>
                </div>
        </div>
        </form>

    
    </div>
</div>
</x-layouts.app>