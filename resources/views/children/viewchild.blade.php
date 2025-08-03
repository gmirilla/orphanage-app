<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .pillbutton {
  background-color: #ddd;
  border: none;
  color: black;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  margin: 4px 2px;
  border-radius: 16px;
    }

            .profile-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin: 20px;
        }

        .profile-header img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #97a7db;
        }

        .profile-details h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }

        .details {
            list-style: none;
            padding: 0;
        }

        .details li {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .details li span {
            font-weight: bold;
        }
        
        .sectionhead {
            font-weight: bold;
            color: #375dd7 !important;
        }
        .div-container {
        max-height: 200px;        /* Limit the height */
        overflow-y: auto;         /* Enable vertical scrolling if content exceeds max-height */
        overflow-x: hidden;       /* Optional: hide horizontal scroll */
        padding: 5px;            /* Optional: add spacing inside */
         border: 1px solid #ccc;   /* Optional: visual boundary */
         background-color: #ffffff;   /* Optional: background */
}

</style>

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
    <div class="card-header"><h2>CHILD PROFILE</h2></div>
    <div class="card-body">
        <a href="{{route('register_newchild')}}" class="btn btn-primary">Register New Child</a>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header flex sectionhead" style="gap: 10px"><i class="fa fa-user" style="font-size:1.6em" aria-hidden="true"></i><h5>Basic Information</h5></div>
    <div class="card-body row gx-5">
        <div class="profile-header col-5">

            @if (!empty($child->profilephoto))
           <img src="{{Request::root().('/storage/'.$child->profilephoto)}}" alt="" style="width: 250px; height: 250px;">
           @else
              <img id="uploadedImage" src="{{Request::root().('/storage/nophoto.png')}}" alt="No Photo Uploaded" style="width: 250px; height: 250px;">
           @endif
        </div>


        <div class="profile-details col-5">
           <ul class="details medical-record mb-3 p-3 border rounded bg-light">
                <li><span>Name:</span> {{$child->getFullname()}}</li>
                <li><span>Age:</span><span class="pillbutton"> {{$child->getAge()}}</span></li>
                <li><span>Date of Birth:</span> {{$child->dateofbirth}}</li>
                <li><span>Gender:</span> {{$child->gender}}</li>
                <li><span>Admission Date:</span> {{$child->admissiondate}}</li>
                <li><span>Allergies:</span> {{$child->getAllergystring()}}</li>
                <li><span>Hobbies:</span> {{$child->getHobbiesstring()}} <button class="btn btn-success ml-5" data-bs-toggle="modal" data-bs-target="#addHobbyModal">Add</button></li>
                <li><span>Special Needs:</span> {{$child->getspecialneedsstring()}} <button class="btn btn-success ml-5" data-bs-toggle="modal" data-bs-target="#addSneedsModal">Add</button></li>
                <li><span>Notes:</span><a href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-message="{{$child->note}}">{{$child->note}}</a><button class="btn btn-success ml-5">Add Note</button></li>
            </ul>
        </div>


    </div>
</div>
<div class="row bd-highlight gx-5">
<div class="card mt-3 col-5">
    <div class="card-header flex sectionhead" style="gap: 10px"><i class="fa fa-graduation-cap" style="font-size:1.6em" aria-hidden="true"></i><h5>Education Information</h5></div>
<div class="card-body">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">Add Record</button>
    <div class="profile-details col-auto card mt-3 div-container">
        @forelse ($child->getEducation() as $edurecord)
            <ul class="details education-record mb-3 p-3 border rounded bg-light">
                <a href="{{ route('delinfo', ['child_id' => $child->id, 'info' => 'EDU', 'id' => $edurecord->id]) }}">
                <i class="fa fa-trash text-danger text-end" style="font-size:1.6em" aria-hidden="true"></i></a>
                <li><span>Academic Year:  </span> {{$edurecord->academicyear}} </li>
                <li><span>School Name:  </span> {{$edurecord->schoolname}}</li>
                <li><span>Current Class/Grade:  </span> {{$edurecord->grade}}</li>
                <li><span>Academic Notes:  </span>  <a href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-message="{{$edurecord->academicnote}}"> {{$edurecord->academicnote}}</a></li>
            </ul>
        @empty
            <ul>
                <li>No Education Records on File</li>
            </ul>
        @endforelse
    </div>
</div>
</div>
<div class="card mt-3 ml-5 col-5">
    <div class="card-header flex sectionhead" style="gap: 10px"><i class="fa fa-stethoscope" style="font-size:1.6em" aria-hidden="true"></i><h5>Medical Information</h5></div>
    <div class="card-body">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicalModal">Add Record</button>
        <div class="profile-details col-auto card mt-3 px-2 div-container " >
             @forelse ($child->getMedical() as $medrecord )
            <ul class="details medical-record mb-3 p-3 border rounded bg-light">
                <a href="{{ route('delinfo', ['child_id' => $child->id, 'info' => 'MED', 'id' => $medrecord->id]) }}">
                <i class="fa fa-trash text-danger text-end" style="font-size:1.6em" aria-hidden="true"></i></a>
                <li><span>Medication:</span> {{$medrecord->allergy}}</li>
                <li><span>Medication:</span> {{$medrecord->medication}}</li>
                <li><span>Doctor Name:</span> {{$medrecord->doctorname}}</li>
                <li><span>Doctor's Contact:</span> {{$medrecord->doctorcontact}}</li>
                <li><span>Medical Notes</span><a href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-message="{{$medrecord->medicalnote}}">
                      {{$medrecord->medicalnote}}</a></li>  
            </ul>               
                @empty
                <ul>

                 <li>No Medical Records on File</li>   
                @endforelse
            </ul>
        </div>

    </div>
</div>
<div class="card mt-3 ml-5 col-5">
    <div class="card-header flex sectionhead" style="gap: 10px"><i class="fa fa-history" style="font-size:1.6em" aria-hidden="true"></i><h5>Background Information</h5></div>
    <div class="card-body">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBackgroundModal">
    Add Record
</button>
        <div class="profile-details col-auto card mt-3 px-2 div-container ">       
                @forelse ($child->getBackground() as $background )
               <ul class="details medical-record mb-3 p-3 border rounded bg-light">
                <a href="{{ route('delinfo', ['child_id' => $child->id, 'info' => 'BKG', 'id' => $background->id]) }}">
                <i class="fa fa-trash text-danger text-end" style="font-size:1.6em" aria-hidden="true"></i></a>
                <li><span>Name of Previous Gaurdian:</span> {{$background->pguardianname}}</li>
                <li><span>Contact of Previous Guardian:</span> {{$background->pguardiancontact}}</li>
                <li><span>Admission Notes</span><a href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-message="{{$background->admissionreason}}">
                     {{$background->admissionreason}}</a></li>  
                </ul>               
                @empty
                <ul>
                 <li>No Records on File</li>   
                @endforelse
            </ul>
        </div>
    </div>
</div>
<div class="card mt-3 ml-5 col-5">
    <div class="card-header flex sectionhead" style="gap: 10px"><i class="fa fa-home" style="font-size:1.6em" aria-hidden="true"></i><h5>Accomodation Information</h5></div>
    <div class="card-body">
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccommodationModal">
    Add Record
</button>

        <div class="profile-details col-auto card mt-3 px-2 div-container ">
            
                @forelse ($child->getAccomodation() as $accrecord )
               <ul class="details medical-record mb-3 p-3 border rounded bg-light">
                                <a href="{{ route('delinfo', ['child_id' => $child->id, 'info' => 'ACC', 'id' => $background->id]) }}">
                <i class="fa fa-trash text-danger text-end" style="font-size:1.6em" aria-hidden="true"></i></a>
                <li><span>Assigned Staff:</span> {{$accrecord->staff_id}}</li>
                <li><span>Assigned Dormroom:</span> {{$accrecord->dormroom}}</li>
                <li><span>Accomodation Notes</span><a href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-message="{{$accrecord->accomodationnotes}}">
                     {{$accrecord->accomodationnotes}}</a></li>     
                </ul>            
                @empty
                <ul>
                 <li>No Accomodation Records on File</li>   
                </ul>
                @endforelse
        </div>

    </div>
</div>
</div>

<!--Add Education Record Modal -->
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
                <!-- form goes here -->
        <form action="{{route('addeduinfo')}}" method="post">
            @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addRecordLabel">Add Education Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
          <div class="mb-3">
            <label for="academicYear" class="form-label">Academic Year</label>
            <input type="text" class="form-control" id="academicYear" name="academicyear" placeholder="e.g., 2024">
          </div>
          <div class="mb-3">
            <label for="schoolName" class="form-label">School Name</label>
            <input type="text" class="form-control" name="schoolname" id="schoolName" placeholder="School Name">
          </div>
          <div class="mb-3">
            <label for="grade" class="form-label">Grade/Class</label>
            <input type="text" class="form-control" name="grade" id="grade" placeholder="e.g., Primary 5">
          </div>
          <div class="mb-3">
            <label for="academicNote" class="form-label">Academic Notes</label>
            <textarea class="form-control"  name="academicnote" id="academicNote" rows="3" placeholder="Notes or comments..."></textarea>
          </div>
        
      </div>
      <div class="modal-footer">
        <input type="text" name="child_id" value={{$child->id}} hidden>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" name="addeducationrecord">Save Record</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Medical Record Modal -->
<div class="modal fade" id="addMedicalModal" tabindex="-1" aria-labelledby="addMedicalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{route('addmedinfo')}}" method="post">
            @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addMedicalLabel">Add Medical Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        
          <div class="mb-3">
            <label for="allergy" class="form-label">Allergy</label>
            <input type="text" class="form-control" id="allergy" name="allergy" placeholder="E.g. Nuts, Penicillin">
          </div>

          <div class="mb-3">
            <label for="medication" class="form-label">Medication</label>
            <input type="text" class="form-control" id="medication" name="medication" placeholder="E.g. Ibuprofen, Amoxicillin">
          </div>

          <div class="mb-3">
            <label for="doctorName" class="form-label">Doctor's Name</label>
            <input type="text" class="form-control" id="doctorName" name="doctorname">
          </div>

          <div class="mb-3">
            <label for="doctorContact" class="form-label">Doctor's Contact</label>
            <input type="text" class="form-control" id="doctorContact" name="doctorcontact">
          </div>

          <div class="mb-3">
            <label for="medicalNote" class="form-label">Medical Notes</label>
            <textarea class="form-control" id="medicalNote"  name="medicalnote" rows="3" placeholder="Any additional health notes..."></textarea>
          </div>
        
      </div>

      <div class="modal-footer">
        <input type="text" name="child_id" value={{$child->id}} hidden>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" name="addmedicalrecord">Save Record</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Background Record Modal -->
<div class="modal fade" id="addBackgroundModal" tabindex="-1" aria-labelledby="addBackgroundLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{route('addbckinfo')}}" method="POST">
            @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addBackgroundLabel">Add Background Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        
          <div class="mb-3">
            <label for="pguardianname" class="form-label">Previous Guardian Name</label>
            <input type="text" class="form-control" id="pguardianname" name="pguardianname" placeholder="Full name">
          </div>

          <div class="mb-3">
            <label for="pguardiancontact" class="form-label">Previous Guardian Contact</label>
            <input type="text" class="form-control" id="pguardiancontact" name="pguardiancontact" placeholder="Phone number or email">
          </div>

          <div class="mb-3">
            <label for="admissionreason" class="form-label">Admission Notes</label>
            <textarea class="form-control" id="admissionreason" name="admissionreason" rows="3" placeholder="Reason for admission..."></textarea>
          </div>
        
      </div>

      <div class="modal-footer">
        <input type="text" name="child_id" value={{$child->id}} hidden>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" name="addbackgroundrecord">Save Record</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Accommodation Record Modal -->
<div class="modal fade" id="addAccommodationModal" tabindex="-1" aria-labelledby="addAccommodationLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
     <form action="{{route('addaccoinfo')}}" method="post">
            @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addAccommodationLabel">Add Accommodation Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="staffId" class="form-label">Assigned Staff</label>
            <input type="text" class="form-control" id="staffId" name="staff_id" placeholder="Enter staff ID or name">
          </div>

          <div class="mb-3">
            <label for="dormroom" class="form-label">Dormroom</label>
            <input type="text" class="form-control" id="dormroom" name="dormroom" placeholder="e.g. B2, West Wing">
          </div>

          <div class="mb-3">
            <label for="accommodationNotes" class="form-label">Accommodation Notes</label>
            <textarea class="form-control" id="accommodationNotes" name="accommodationnotes" rows="3" placeholder="Any observations or remarks"></textarea>
          </div>
      </div>

      <div class="modal-footer">
        <input type="text" name="child_id" value={{$child->id}} hidden>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" name="addaccomodationrecord">Save Record</button>
      </div>
     </form>
    </div>
  </div>
</div>

<!-- Hobby Record Modal -->
<div class="modal fade" id="addHobbyModal" tabindex="-1" aria-labelledby="addHobbyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{route('adddevinfo')}}" method="POST">
            @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addHobbyLabel">Add Hobby</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">      
          <div class="mb-3">
            <label for="hobby" class="form-label">Hobby</label>
            <input type="text" class="form-control" id="hobby" name="hobbies" placeholder="Enter Name of Hobby">
          </div>      
      </div>

      <div class="modal-footer">
        <input type="text" name="child_id" value={{$child->id}} hidden>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" name="addhobby">Save Record</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Special Needs Record Modal -->
<div class="modal fade" id="addSneedsModal" tabindex="-1" aria-labelledby="addSneedsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{route('adddevinfo')}}" method="POST">
            @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addHobbyLabel">Add Special Needs</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">      
          <div class="mb-3">
            <label for="sneeds" class="form-label">Special Needs</label>
            <input type="text" class="form-control" id="sneeds" name="specialneeds" placeholder="Enter Name of Special Needs">
          </div>      
      </div>
      <div class="modal-footer">
        <input type="text" name="child_id" value={{$child->id}} hidden>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" name="addhobby">Save Record</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Dynamic Modal -->
<div class="modal fade" id="dynamicModal" tabindex="-1" aria-labelledby="dynamicModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dynamicModalLabel">Message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalMessage">
        <!-- Message goes here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</div>
<script>
  const modal = document.getElementById('dynamicModal');
  modal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const message = button.getAttribute('data-message');
    const modalBody = modal.querySelector('#modalMessage');
    modalBody.textContent = message;
  });
</script>

</x-layouts.app>