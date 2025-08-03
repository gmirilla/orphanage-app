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
            <div class="col-auto">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" name='fname' id='fname' required placeholder="Enter Child's First Name" class="form-control">
            </div>
            <div class="col-auto">
                <label for="mname" class="form-label">Middle Name</label>
                <input type="text" name='mname' id='mname' placeholder="Enter Child's Middle Name or initials" class="form-control">
            </div>
            <div class="col-auto">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" required   name='lname' id='lname' placeholder="Enter Child's Last Name" class="form-control">
            </div>
            <div class="col-auto">
                <label for="dateofbirth" class="form-label">Date of Birth</label>
                <input type="date" name='dateofbirth' id='dateofbirth' placeholder="Date of Birth" class="form-control">
            </div>
            <div class="col-auto">
                <label for="gender"  class="form-label">Gender</label>
                <select name="gender" required   id="gender" class="form-select">
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                </select>
            </div>
            <div class="col-auto">
                <label for="birthplace"  class="form-label">Place of Birth</label>
                <input type="text" required name='birthplace' id='birthplace' placeholder="Place of Birth" class="form-control">
            </div>
        </div>
            <div class="col-auto">
                <label for="nationalityid" class="form-label">Nationality</label>
                <select name="nationalityid"  required id="nationalityid" class="form-select">
                    @forelse ($countries as $country )
                        <option value="{{$country->id}}">{{$country->name}}</option>
                    @empty
                        <option value="0">No Countries on System</option>
                    @endforelse
                </select>
            </div>
            <div class="col-auto">
                <label for="identificationtype"   class="form-label">Identification Type</label>
                <select name="identificationtype" required id="identificationtype" class="form-select">
                    <option value="Birth Certificate">Birth Certificate</option>
                    <option value="National ID">National ID</option>
                    <option value="None">None</option>
                </select>
            </div>
            <div class="col-auto">
                <label for="identificationno" class="form-label">Identification Number</label>
                <input type="text" name='identificationno' id='identificationno' placeholder="Identification number" class="form-control">
            </div>
            <div class="col-auto">
                <label for="admissiondate" class="form-label">Admission Date</label>
                <input type="date" name='admissiondate' id='admissiondate' placeholder="Admission Date" class="form-control">
            </div>

            <div class="col-auto">
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

            <div class="col-auto">
                   <label for="profilepicture" class="form-label">Upload Picture</label>
                    <input type="file" name="profilepicture" accept="image/*" class="form-control">
            </div>
            <div class="col-auto">
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
<script>
  $(document).ready(function () {
    $('.table').DataTable({
      dom: 'Bfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf']
    });
  });
</script>
<script>
  document.querySelector('input[name="profilepicture"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(evt) {
        const preview = document.createElement('img');
        preview.src = evt.target.result;
        preview.style.maxWidth = '150px';
        e.target.parentNode.appendChild(preview);
      };
      reader.readAsDataURL(file);
    }
  });
</script>


</x-layouts.app>