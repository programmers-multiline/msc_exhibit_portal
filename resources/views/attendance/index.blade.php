@extends('layouts.app')

@section('content')
<style>
    /* Pinapaliit ang font ng buong DataTables wrapper */
    .dataTables_wrapper {
        font-size: 0.8rem !important; /* Mga 12px-13px */
    }
    
    /* Pinapaliit ang font at padding ng mga cells (th at td) */
    #AttendanceTbl th, 
    #AttendanceTbl td {
        font-size: 0.78rem !important; 
        padding: 5px 8px !important; /* Mas manipis na taas at babang espasyo */
    }

    /* Pinapaliit din ang text sa loob ng mga buttons (Excel, PDF) */
    .dt-buttons .btn {
        font-size: 0.75rem !important;
        padding: 4px 8px !important;
    }

    /* Pinapaliit ang search input box at pagination info */
    .dataTables_filter input,
    .dataTables_info,
    .dataTables_paginate {
        font-size: 0.75rem !important;
    }
</style>


<div class="card-box mb-30 p-3 ml-15">

    <h4 class="mb-3">Attendance</h4>

   <div class="row p-2">

   <div class="buttonDiv w-25">
     @if (in_array(auth()->user()->position_id, [13, 237]))
    <button class="btn btn-sm btn-success mb-2" id="bulkAssignBtn">Assign PSC</button>
    @endif

        </div>
      
        <div class="searchingDiv w-50">
                <form>
                <label for="start">Start Date:</label>
                <input type="date" id="start" name="startDate">

                <label for="end">End Date:</label>
                <input type="date" id="end" name="endDate">
                <button class="btn btn-outline-secondary mb-2">Filter</button>
                </form>

                <script>
                const startDateInput = document.getElementById('start');
                const endDateInput = document.getElementById('end');

                // When start date changes, end date cannot be earlier than start date
                startDateInput.addEventListener('change', function() {
                    if (this.value) {
                    endDateInput.min = this.value;
                    }
                });

                // When end date changes, start date cannot be later than end date
                endDateInput.addEventListener('change', function() {
                    if (this.value) {
                    startDateInput.max = this.value;
                    }
                });
                </script>
            </div>
            <!-- Ending of SearchingDiv -->
   </div>

<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <!-- Wrapper para sa responsive table para hindi masira ang layout -->
        <div class="table-responsive">
            <table id="AttendanceTbl" class="table table-striped table-hover align-middle nowrap w-100" style="margin-top: 15px !important;">
                <thead class="table-dark text-uppercase fs-3 tracking-wider">
                    <tr>
                        <!-- Checkbox column kung gagamitin mo ang bulk assign function mo kanina -->
                        <th class="text-center" style="width: 40px;">
                            PSC Name
                        </th>
                        <th>Exhibit Name</th>
                        <th>Company</th>
                        <th>Contact Name</th>
                        <th>Email Address</th>
                        <th>Contact #</th>
                        <th>Date </th>
                        <th>Time</th>
                        <th>Assisted By</th>
                        <th class="text-center" style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody class="fs-6 text-secondary">
                    <!-- Kusang pupunuin ng Yajra DataTables Ajax -->
                </tbody>
            </table>
        </div>
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

<!-- MOdal for Assigning of PSC -->
 <div class="modal fade" id="assignModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Assign PSC</h5>
</div>

<div class="modal-body">
<select id="psc_id" class="form-control">
 @foreach($users as $user)
    @php
        $fullName   = $user->first_name . ' ' . $user->last_name;
        $currentUrl = request()->fullUrl();
    @endphp

          @if(request()->is('*Attendance*'))
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

    // Get selected participants
    $('.participant_checkbox:checked').each(function(){
        selected.push($(this).val());
    });

    let psc_id = $('#psc_id').val();

  //  alert(psc_id)

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
        url: "/Attendance/bulk-assign",
        type: "POST",
        data:{
            _token  : $('meta[name="csrf-token"]').attr('content'),
            attendee: selected,
            psc_id  : psc_id
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

    $('#AttendanceTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('Attendance.index') }}",

        columns: [
            { data: 'checkbox', name: 'checkbox', orderable:false, searchable:false },
            { data: 'exhibit_name', name: 'attendance.exhibit_name' },
            { data: 'company_name', name: 'company_list.company_name' },
            { data: 'contact_name', name: 'attendance.name' },
            { data: 'contact_email', name: 'attendance.email' },
            { data: 'phone',name: 'attendance.phone' },
            { data: 'date', name: 'attendance.date' },
            { data: 'time', name: 'attendance.time' },
            { data: 'Entry_by', name: 'users.name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
      
        ]
    });

});//Ending of Document Ready



</script>
@endsection