@extends('layouts.app')

@section('content')

<div class="card-box mb-30 p-3 ml-15">

    <h4 class="mb-3">Participants</h4>
   <!--  <button class="btn btn-sm btn-primary mb-2" data-toggle="modal" data-target="#addParticipantModal">Add Participant</button> -->
   
@if(auth()->user()->position_id == 13)
    
    <button class="btn btn-sm btn-success mb-2" id="bulkAssignBtn">Assign PSC</button>
@else
    
     <button class="btn btn-sm btn-secondary mb-2" style="cursor:not-allowed;" disabled>Assign PSC</button>
@endif

   <!--  <button class="btn btn-sm btn-success mb-2" data-toggle="modal" data-target="#importModal">Import Excel</button> -->

<table id="ParticipantTbl" class="table table-bordered table-responsive nowrap w-100">
    <thead>
        <tr>
            <th colspan="10" class="text-center">Participants Details</th>
            <th colspan="1" class="text-center">Agent Details</th>
        </tr>

        <tr>
          
            <th>#</th>
            <th>Exhibit Name</th>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
           <!--  <th>Position</th> -->
            <th>Contact#</th>
            <!-- <th>Source</th> -->
            <th>Address</th>
            <th>Date</th>
            <th>Remarks</th>
            <th>Assisted by</th>
            <!-- <th>Action</th> -->
        </tr>
    </thead>
</table>

</div>

<!-- Modal for Import function -->
 <!-- Modal -->
<div class="modal fade" id="importModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Import Participants</h5>
      </div>

      <div class="modal-body">
        <form id="importForm" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" class="form-control" required>
            <br>
            <button type="submit" class="btn btn-primary">
                Upload & Import
            </button>
        </form>

        <div id="importResult" class="mt-3"></div>
      </div>

    </div>
  </div>
</div>
<!-- Ending of Modal for Import function -->



<!-- Add Participant Modal -->
<div class="modal fade" id="addParticipantModal" tabindex="-1">
  <div class="modal-dialog modal-l">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Add Participant</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <form id="participantForm" enctype="multipart/form-data">
        @csrf

        <div class="modal-body">

        

       <div class="row">

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Exhibit Name</label>
                         <select name="exhibit_name" id="exhibit_name" class="form-control">
                            <option value="">Select</option>
                            <option value="PhilConstruct">PhilConstruct</option>
                            <option value="WorldBex" selected>WorldBex</option>
                            <option value="PHA">PHA</option>
                        </select>
                        <span id="error_field_exhibit_name" style="display:none; color:red;">Required Field</span>
                    </div>
                </div><!-- Ending of  col-sm-6-->


                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Date of Event</label>
                        <input type="date" name="day_num" id="day_num" value="{{ date('Y-m-d') }}"  class="form-control">
                        <span id="error_field_day_num" style="display:none; color:red;">Required Field</span>
                     </div>
                </div><!-- Ending of  col-sm-6-->


                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Agent Name</label>
                        <select name="entry_by" id="entry_by" class="form-control">
                            <option value="">Select</option>
                            <option value="Allan De Leon">Allan De Leon</option>
                            <option value="Rizzabeth Dizon">Rizzabeth Dizon</option>
                        </select>
                        <span id="error_field_entry_by" style="display:none; color:red;">Required Field</span>
                    </div>
                </div><!-- Ending of  col-sm-6-->
                
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Sales Manager</label>
                        <select name="sales_manager" id="sales_manager" class="form-control">
                            <option value="">Select</option>
                            <option value="Louie Valenzuela">Louie Valenzuela</option>
                            <option value="Beth Depasupil">Beth Depasupil</option>
                            <option value="Mark Jabat">Mark Jabat</option>
                            <option value="Jason Pascual">Jason Pascual</option>
                            <option value="Andrea Ungco">Andrea Ungco</option>
                        </select>
                        <span id="error_field_sales_manager" style="display:none; color:red;">Required Field</span>
                    </div>
                 </div><!-- Ending of  col-sm-6-->
            
                 <div class="col-sm-12 mb-2 text-warning bg bg-dark p-2">
               <!--   <hr class="w-100 mb-2">    -->
                 Participant Details
                 <!-- <hr class="w-100 mb-2">    -->
                </div><!-- Ending of  col-sm-12-->


            <div class="col-sm-6">
                 <div class="form-group">
                <label>Name of Partcipant</label>
                <input type="text" name="participant_name" id="participant_name" class="form-control">
                <span id="error_field_participant_name" style="display:none; color:red;">Required Field</span>
            </div>

            </div><!-- Ending of  col-sm-6-->


             <div class="col-sm-6">
                <div class="form-group">
                    <label>Email of Partcipant</label>
                    <input type="text" name="participant_email"  id="participant_email" class="form-control">
                    <span id="error_field_participant_email" style="display:none; color:red;">Required Field</span>
                </div>

            </div><!-- Ending of  col-sm-6-->



             <div class="col-sm-6">
              <div class="form-group">
                <label>Position</label>
                <input type="text" name="participant_position" id="participant_position" class="form-control">
                <span id="error_field_participant_position" style="display:none; color:red;">Required Field</span>
             </div>

            </div><!-- Ending of  col-sm-6-->


             <div class="col-sm-6">
                <div class="form-group">
                <label>Contact#</label>
                <input type="text" name="participant_contact" id="participant_contact" class="form-control">
                <span id="error_field_participant_contact" style="display:none; color:red;">Required Field</span>
             </div>

            </div><!-- Ending of  col-sm-6-->




            <div class="col-sm-6">
               <div class="form-group">
                <label>Company/School of Partcipant</label>
                <input type="text" name="participant_company" id="participant_company" class="form-control">
                <span id="error_field_participant_company" style="display:none; color:red;">Required Field</span>
             </div>

            </div><!-- Ending of  col-sm-6-->


             <div class="col-sm-6">
                <div class="form-group">
                <label>Address of Partcipant</label>
                <input type="text" name="participant_address" id="participant_address" class="form-control">
                <span id="error_field_participant_address" style="display:none; color:red;">Required Field</span>
             </div>
            </div><!-- Ending of  col-sm-6-->

             <div class="col-sm-6">
                <div class="form-group">
                    <label>Inquiry of Partcipant</label>
                    <input type="text" name="participant_remarks" id="participant_remarks" class="form-control">
                    <span id="error_field_participant_remarks" style="display:none; color:red;">Required Field</span>
                </div>
            </div><!-- Ending of  col-sm-6-->


             <div class="col-sm-6">
                <div class="form-group">
                <label>Upload Photo</label>
                <input type="file" name="participant_photo[]" id="participantPhoto" class="form-control" multiple>
                <span id="error_field_participant_photo" style="display:none; color:red;">Required Field</span>
             </div>
            </div><!-- Ending of  col-sm-6-->


             <div class="col-sm-12">
                    <div class="form-group">
                        <label>Source</label>
                        <select name="participant_source" id="participant_source" class="form-control">
                            <option value="">Select</option>
                            <option value="Contact Info">Contact Info</option>
                            <option value="Viber">Viber</option>
                            <option value="Messenger">Messenger</option>
                            <option value="Calling Card">Calling Card</option>
                            <option value="Customer Inquiry Form">Customer Inquiry Form</option>
                            <option value="Contacts w/ Inquiry">Contacts w/ Inquiry</option>
                            
                        </select>
                        <span id="error_field_participant_source" style="display:none; color:red;">Required Field</span>
                    </div>
                 </div><!-- Ending of  col-sm-6-->

  
        </div><!-- Ending of  row-->

        
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="saveParticipant">Save</button>
        </div>

      </form>

    </div>
  </div>
</div>


<!-- For Carousel Modal -->
<div class="modal fade" id="imageCarouselModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-body">

                <div id="participantCarousel" class="carousel slide" data-ride="carousel">

                    <div class="carousel-inner" id="carouselImages">
                        <!-- Images will be inserted here via JS -->
                    </div>

                    <a class="carousel-control-prev" href="#participantCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>

                    <a class="carousel-control-next" href="#participantCarousel" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>

                </div>

            </div>

        </div>
    </div>
</div>
<!-- Ending of Carousel Modal -->

<!-- Modal to update the Status of Attendees -->
 <div class="modal fade" id="statusModal">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Update Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="participant_id">
        
        <div class="mb-3">
            <label>Select Activity</label>
            <select class="form-control" id="status">
                <option value="">Select</option>
                <option value="Phone Call">1st Phone Call</option>
                <option value="Product Presentation">Product Presentation</option>
                <option value="RFQ Creation">RFQ Creation</option>
                <option value="Submitted Proposal">Submitted Proposal</option>
                <option value="For Revision of Proposal">For Revision of Proposal</option>
                <option value="Received Signed Proposal">Received Signed Proposal</option>
                <option value="Received Signed Contract">Received Signed Contract</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea class="form-control" id="description"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="saveStatus">
            Save
        </button>
      </div>

    </div>
  </div>
</div>

<!-- Ending of Modal Update Status Attendees -->


<!-- MOdal for Assigning of PSC -->
 <div class="modal fade" id="assignModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Assign PSC</h5>
</div>

<div class="modal-body">

<!-- <select id="psc_id" class="form-control">
<option value="">Select PSC</option>
<option value="1">PSC 1</option>
<option value="2">PSC 2</option>
</select> -->
<select id="psc_id" class="form-control">
 @foreach($users as $user)
    @php
        $fullName   = $user->first_name . ' ' . $user->last_name;
        $currentUrl = request()->fullUrl();
    @endphp
          @if(str_contains($currentUrl, 'participants'))
          <option value="{{$user->emp_id}}">
            {{$fullName}}
        </option>
        
    @else
        <option value="{{$fullName}}">
            {{$fullName}}
        </option>
    @endif
    @endforeach
</select>
</div>

<div class="modal-footer">
<button class="btn btn-primary" id="confirmAssign">
Save Assignment
</button>
</div>

</div>
</div>
</div>
 <!-- Ending of Modal Assigning of PSC -->

@endsection


@section('scripts')
<!-- DataTable CSS -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"> -->
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>  -->


<!-- @if ($errors->any())
<script>
    $(document).ready(function(){
        $('#addParticipantModal').modal('show');
    });
</script>
@endif -->



<!-- Jquery for Import function -->
 <script>
$('#importForm').submit(function(e){
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: "/participants/import",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response){
            //alert(response.count + " entries imported.");

              $('#importModal').modal('hide');
             Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Successfully Imported.' + response.count + ' Entries',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-center'
            });

            // Reload only DataTable
            $('#ParticipantTbl').DataTable().ajax.reload(null,false);
        }
    });
});
</script>
 <!-- Ending of Jquery for Import function -->

<script>
//Use to multiple select participants for assigning of PSC
$('#bulkAssignBtn').click(function(){

    let selected = [];

    $('.participant_checkbox:checked').each(function(){
        selected.push($(this).val());
    });

    if(selected.length === 0){
            Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please select at least one participant',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false,
            backdrop: true
                });

        return;
    }

    $('#assignModal').modal('show');

});
//
//Use to Save Assigned PSC



//Use to update status of attendees
$('#saveStatus').click(function(){

    var id          = $('#participant_id').val();
    var status      = $('#status').val();
    var description = $('#description').val();

    $.ajax({
        url: "/participants/update-status/" + id,
        type: "POST",
        data: {
            _token     : "{{ csrf_token() }}",
            status     : status,
            description: description

        },
        success: function(response){

            $('#statusModal').modal('hide');
             Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Successfully Updated the Status.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-center'
            });

            // Reload only DataTable
            $('#participantsTable').DataTable().ajax.reload(null,false);
            

        }
    });

});


//Use to open modal for updating of Attendees Status
$(document).on('click', '.btnUpdateStatus', function(){

    var id     = $(this).data('id');
    var status = $(this).data('status');

    $('#participant_id').val(id);
    $('#status').val(status);

    $('#statusModal').modal('show');
});


//Use to view Photo in carousel design
$(document).on('click','.viewImages', function(){

    let participant_id = $(this).data('id');

    $.get("/participants/images/"+participant_id, function(images){

        let html = '';

        $.each(images, function(i,img){

            html += `
            <div class="carousel-item ${i === 0 ? 'active' : ''}">
                <img src="/storage/participants/${img.image_name}" class="d-block w-100">
            </div>`;
        });

        $('#carouselImages').html(html);

        $('#imageCarouselModal').modal('show');

    });

});


$(document).ready(function(){

    $('#ParticipantTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/participants",

        columns: [
            { data: 'checkbox', name: 'checkbox', orderable:false, searchable:false },
            { data: 'exhibit_name', name: 'exhibit_name' },
            { data: 'participant_photo', name: 'participant_photo' },
            {
                data: 'name_position',
                name: 'name_position',
                render: function(data){
                    return data;
                }
            },
            { data: 'participant_email', name: 'participant_email' },
            { data: 'company_name', name: 'company_name' },
            { data: 'participant_contact', name: 'participant_contact' },
            { data: 'participant_address', name: 'participant_address' },
            { data: 'day_num', name: 'day_num' },
            { data: 'participant_remarks', name: 'participant_remarks' },
            { data: 'entry_by_name', name: 'entry_by_name' },
      
        ]
    });



 $('#saveParticipant').click(function(){

    var result = 1;

    let exhibit_name         = $('#exhibit_name').val();
    let day_num              = $('#day_num').val();
    let entry_by             = $('#entry_by').val();
    let sales_manager        = $('#sales_manager').val();
    let participant_name     = $('#participant_name').val();
    let participant_email    = $('#participant_email').val();
    let participant_position = $('#participant_position').val();
    let participant_contact  = $('#participant_contact').val();
    let participant_company  = $('#participant_company').val();
    let participant_address  = $('#participant_address').val();
    let participant_remarks  = $('#participant_remarks').val();
    let files                = $('#participantPhoto')[0].files;
    let participant_source   = $('#participant_source').val();
   

   
    
    

    if(!exhibit_name || exhibit_name.trim() === ''){
        $('#error_field_exhibit_name').show();
        return;
    }

    if(!day_num || day_num.trim() === ''){
        $('#error_field_day_num').show();
        return;
    }

    if(!entry_by || entry_by.trim() === ''){
        $('#error_field_entry_by').show();
        return;
    }

    if(!sales_manager || sales_manager.trim() === ''){
        $('#error_field_sales_manager').show();
        return;
    }

    if(!participant_name || participant_name.trim() === ''){
        $('#error_field_participant_name').show();
        return;
    }

    if(!participant_email || participant_email.trim() === ''){
        $('#error_field_participant_email').show();
        return;
    }

    if(!participant_position || participant_position.trim() === ''){
        $('#error_field_participant_position').show();
        return;
    }

    if(!participant_contact || participant_contact.trim() === ''){
        $('#error_field_participant_contact').show();
        return;
    }

     if(!participant_company || participant_company.trim() === ''){
        $('#error_field_participant_company').show();
        return;
    }

     if(!participant_address || participant_address.trim() === ''){
        $('#error_field_participant_address').show();
        return;
    }

     if(!participant_remarks || participant_remarks.trim() === ''){
        $('#error_field_participant_remarks').show();
        return;
    }

    if (files.length === 0) {
       // alert("Please upload at least one photo.");
        $('#error_field_participant_photo').show();
        return; // ⛔ stop operation
    }

     if(!participant_source|| participant_source.trim() === ''){
        $('#error_field_participant_source').show();
        return;
    }

    // ⭐ IMPORTANT — Use FormData for file upload
    let formData = new FormData($('#participantForm')[0]);

    $.ajax({
        url: "/participants/attendee",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success:function(response){

            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Participant added successfully.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-center'
            });

            $('#addParticipantModal').modal('hide');
            //location.reload();
            $('#ParticipantTbl').DataTable().ajax.reload(null, false);
        },

        error:function(xhr){

            if(xhr.status === 422){

                let errors = xhr.responseJSON.errors;

                $.each(errors, function(key, value){
                    alert(value[0]);
                });

            }

        }

    });

});


$('#entry_by').on('change', function(){

    let entry_by = $('#entry_by').val().trim();

    if(entry_by !== ""){
        $('#error_field_entry_by').hide();
    }

});

$('#exhibit_name').on('change', function(){

    let exhibit_name = $('#exhibit_name').val().trim();

    if(exhibit_name !== ""){
        $('#error_field_exhibit_name').hide();
    }

});

$('#day_num').on('change', function(){

    let day_num = $('#day_num').val().trim();

    if(day_num !== ""){
        $('#error_field_day_num').hide();
    }

});

$('#sales_manager').on('change', function(){

    let sales_manager = $('#sales_manager').val().trim();

    if(sales_manager !== ""){
        $('#error_field_sales_manager').hide();
    }

});


$('#participant_name').on('keyup', function() {

        let participant_name = $(this).val().trim();

        if (participant_name !== "") {
            $('#error_field_participant_name').hide();
        } else {
            $('#error_field_participant_name').show();
        }

    });


    $('#participant_email').on('keyup', function() {

        let participant_email = $(this).val().trim();

        if (participant_email !== "") {
            $('#error_field_participant_email').hide();
        } else {
            $('#error_field_participant_email').show();
        }

    });

     $('#participant_position').on('keyup', function() {

        let participant_position = $(this).val().trim();

        if (participant_position !== "") {
            $('#error_field_participant_position').hide();
        } else {
            $('#error_field_participant_position').show();
        }

    });

    $('#participant_contact').on('keyup', function() {

        let participant_contact = $(this).val().trim();

        if (participant_contact !== "") {
            $('#error_field_participant_contact').hide();
        } else {
            $('#error_field_participant_contact').show();
        }

    });


    $('#participant_company').on('keyup', function() {

        let participant_company = $(this).val().trim();

        if (participant_company !== "") {
            $('#error_field_participant_company').hide();
        } else {
            $('#error_field_participant_company').show();
        }

    });

    $('#participant_address').on('keyup', function() {

        let participant_address = $(this).val().trim();

        if (participant_address !== "") {
            $('#error_field_participant_address').hide();
        } else {
            $('#error_field_participant_address').show();
        }

    });

     $('#participant_remarks').on('change', function() {

        let participant_remarks = $(this).val().trim();

        if (participant_remarks !== "") {
            $('#error_field_participant_remarks').hide();
        } else {
            $('#error_field_participant_remarks').show();
        }

    });


    $('#participantPhoto').on('change', function() {

    let files = this.files; // ✅ correct way

    if (files.length === 0) {
        // ❌ No file selected
        $('#error_field_participant_photo').show();
    } else {
        // ✅ With file
        $('#error_field_participant_photo').hide();
    }

});


 $('#participant_source').on('change', function() {

        let participant_source = $(this).val().trim();

        if (participant_source !== "") {
            $('#error_field_participant_source').hide();
        } else {
            $('#error_field_participant_source').show();
        }

    });


});//Ending of Document Ready

$('#confirmAssign').click(function(){

    let selected = [];

    // Get selected participants
    $('.participant_checkbox:checked').each(function(){
        selected.push($(this).val());
    });

    let psc_id = $('#psc_id').val();

    //alert(psc_id)

    if(selected.length === 0){
        alert("Please select participants");
        return;
    }

    if(psc_id === ""){
        alert("Please select PSC");
        return;
    }

    // AJAX Save
    $.ajax({
        url: "/participants/bulk-assign",
        type: "POST",
        data:{
            _token      : $('meta[name="csrf-token"]').attr('content'),
            participants: selected,
            psc_id      : psc_id
        },
        success:function(response){


            $('#assignModal').modal('hide');

            // Reload Table
            $('#ParticipantTbl').DataTable().ajax.reload(null,false);

            //alert("Successfully Assigned!");
             Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Successfully Assigned PSC.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-center'
            });

        },
        error:function(){
            alert("Error saving assignment");
        }
    });

});


</script>
@endsection