
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
<div class="card col-8">
    <div class="card-header mb-3"><h5>STAFF REGISTRATION:</h5></div>
    <div class="card-body mb-3">
    </div>
    <div class="card-body responsive row">
      <form action="{{route('savestaff')}}" method="post" enctype="multipart/form-data">
        @csrf

  <div class="form-group col-auto mb-3">
    <label for="fname" class="form-label">First Name</label>
    <input type="text" id="fname" name="fname" required class="form-control"/>
  </div>

  <div class="form-group col-auto mb-3">
    <label for="mname" class="form-label">Middle Name</label>
    <input type="text" id="mname" name="mname" class="form-control"/>
  </div>

  <div class="form-group col-auto mb-3">
    <label for="lname" class="form-label">Last Name</label>
    <input type="text" id="lname" name="lname" required class="form-control" />
  </div>
  <div class="form-group col-auto mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" id="email" name="email" required class="form-control" />
  </div>

  <div class="form-group col-auto mb-3">
    <label for="identificationtype" class="form-label">Identification Type</label>
    <select id="identificationtype" name="identificationtype" required class="form-select">
      <option value="">--Select--</option>
      <option value="passport">Passport</option>
      <option value="nationalID">National ID</option>
      <option value="driverLicense">Driver’s License</option>
    </select>
  </div>

  <div class="form-group col-auto mb-3">
    <label for="identificationno" class="form-label">Identification Number</label>
    <input type="text" id="identificationno" name="identificationno" required class="form-control" />
  </div>

  <div class="form-group col-auto mb-3">
    <label for="profilephoto" class="form-label">Profile Photo</label>
    <input type="file" id="profilephoto" name="profilephoto" accept="image/*"  class="form-control"/>
  </div>

  <div class="form-group col-auto mb-3">
    <label for="staffnotes" class="form-label">Staff Notes</label>
    <textarea id="staffnotes" name="staffnotes" rows="4" placeholder="Add any comments or observations..." class="form-control"></textarea>
  </div>

  <div class="form-group col-auto mb-3">
    <label for="active" class="form-label">Active</label>
    <select id="active" name="active" required class="form-select">
      <option value="true">Yes</option>
      <option value="false">No</option>
    </select>
  </div>

<div>
  <button type="submit" class="btn btn-primary">Submit</button></div>
</form>   
    </div>
</div>
</x-layouts.app>