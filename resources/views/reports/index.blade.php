@extends('layouts.app')

@section('content')

<<div class="card-box mb-30 p-4 shadow-sm">
    <!-- Main Header -->
    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
        <h4 class="h5 text-dark font-weight-bold mb-0">
            <i class="fas fa-chart-bar text-secondary mr-2"></i> Exhibit Attendance Report
        </h4>
    </div>

    <!-- Main Overview Table -->
    <div class="table-responsive mb-15">
        <table class="table table-hover table-striped text-center align-middle border">
            <thead class="bg-light text-secondary font-weight-bold">
                <tr>
                    <th class="text-left pl-3">Year</th>
                    <th style="border-top: 3px solid #A52A2A;">WorldBex</th>
                    <th style="border-top: 3px solid #6495ED;">PHILCONSTRUCT</th>
                    <th style="border-top: 3px solid #A9A9A9;">PHA</th>
                    <th class="bg-dark text-white">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $row)
                <tr>
                    <td class="text-left pl-3 font-weight-bold text-secondary">{{ $row->year_per_participant }}</td>
                    <td class="text-danger font-weight-bold">{{ $row->worldbex }}</td>
                    <td class="text-primary font-weight-bold">{{ $row->philconstruct }}</td>
                    <td class="text-muted font-weight-bold">{{ $row->pha }}</td>
                    <td class="table-dark font-weight-bold">{{ $row->total_leads }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- WORLDBEX SECTION -->
    <div class="mb-15">
        <div class="d-flex align-items-center mb-3">
            <span class="badge badge-pill mr-2" style="background-color: #A52A2A; width: 12px; height: 12px;">&nbsp;</span>
            <h5 class="h6 font-weight-bold mb-0" style="color: #A52A2A;">WorldBex Breakdown</h5>
        </div>
        <div class="table-responsive border rounded">
            <table class="table table-hover text-center align-middle mb-0">
                <thead class="text-white" style="background-color: #A52A2A;">
                    <tr>
                        <th>Year</th>
                        <th>Attendance</th>
                        <th>New Leads</th>
                        <th>Active Leads</th>
                        <th>Converted</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports_per_WorldBex as $row)
                    <tr>
                        <td class="font-weight-bold text-secondary">{{ $row->year_per_exhibit }}</td>
                        <td>{{ $row->worldbex_attendees }}</td>
                        <td><span class="badge badge-warning text-dark">{{ $row->New_Lead }}</span></td>
                        <td><span class="badge badge-info">{{ $row->Active_Leads }}</span></td>
                        <td><span class="badge badge-success">{{ $row->Converted }}</span></td>
                        <td class="font-weight-bold text-dark table-light">{{ $row->total_leads }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- PHILCONSTRUCT SECTION -->
    <div class="mb-15">
        <div class="d-flex align-items-center mb-3">
            <span class="badge badge-pill mr-2" style="background-color: #6495ED; width: 12px; height: 12px;">&nbsp;</span>
            <h5 class="h6 font-weight-bold mb-0" style="color: #6495ED;">PhilConstruct Breakdown</h5>
        </div>
        <div class="table-responsive border rounded">
            <table class="table table-hover text-center align-middle mb-0">
                <thead class="text-white" style="background-color: #6495ED;">
                    <tr>
                        <th>Year</th>
                        <th>Attendance</th>
                        <th>New Leads</th>
                        <th>Active Leads</th>
                        <th>Converted</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports_per_PhilConstruct as $row)
                    <tr>
                        <td class="font-weight-bold text-secondary">{{ $row->year_per_exhibit }}</td>
                        <td>{{ $row->PhilConstruct_attendees }}</td>
                        <td><span class="badge badge-warning text-dark">{{ $row->New_Lead }}</span></td>
                        <td><span class="badge badge-info">{{ $row->Active_Leads }}</span></td>
                        <td><span class="badge badge-success">{{ $row->Converted }}</span></td>
                        <td class="font-weight-bold text-dark table-light">{{ $row->total_leads }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- PHA SECTION -->
    <div class="mb-3">
        <div class="d-flex align-items-center mb-3">
            <span class="badge badge-pill mr-2" style="background-color: #A9A9A9; width: 12px; height: 12px;">&nbsp;</span>
            <h5 class="h6 font-weight-bold mb-0" style="color: #A9A9A9;">PHA Breakdown</h5>
        </div>
        <div class="table-responsive border rounded">
            <table class="table table-hover text-center align-middle mb-0">
                <thead class="text-white" style="background-color: #A9A9A9;">
                    <tr>
                        <th>Year</th>
                        <th>Attendance</th>
                        <th>New Leads</th>
                        <th>Active Leads</th>
                        <th>Converted</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports_per_PHA as $row)
                    <tr>
                        <td class="font-weight-bold text-secondary">{{ $row->year_per_exhibit }}</td>
                        <td>{{ $row->PHA_attendees }}</td>
                        <td><span class="badge badge-warning text-dark">{{ $row->New_Lead }}</span></td>
                        <td><span class="badge badge-info">{{ $row->Active_Leads }}</span></td>
                        <td><span class="badge badge-success">{{ $row->Converted }}</span></td>
                        <td class="font-weight-bold text-dark table-light">{{ $row->total_leads }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>











@endsection


@section('scripts')

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
$('#confirmAssign').click(function(){

    let selected = [];

    $('.participant_checkbox:checked').each(function(){
        selected.push($(this).val());
    });

    let psc_id = $('#psc_id').val();

    $.ajax({
        url: '/participants/bulk-assign',
        type: 'POST',
        data:{
            _token: "{{ csrf_token() }}",
            participants: selected,
            psc_id: psc_id
        },
        success:function(){

            $('#assignModal').modal('hide');

            $('#ParticipantTbl').DataTable().ajax.reload(null,false);

        }
    });

});


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
            { data: 'participant_name', name: 'participant_name' },
            { data: 'participant_email', name: 'participant_email' },
            { data: 'participant_company', name: 'participant_company' },
            { data: 'participant_position', name: 'participant_position' },
            { data: 'participant_contact', name: 'participant_contact' },
            { data: 'participant_source', name: 'participant_source' },
            { data: 'participant_address', name: 'participant_address' },
            { data: 'day_num', name: 'day_num' },
            { data: 'participant_remarks', name: 'participant_remarks' },
            { data: 'entry_by', name: 'entry_by' },
          /*   { data: 'agent_company', name: 'agent_company' },
            { data: 'sales_manager', name: 'sales_manager' }, */
            { data: 'action', name: 'action', orderable:false, searchable:false },
                
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

    alert(psc_id)

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