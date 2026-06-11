@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h3 class="mb-3">Company Directory</h3>
    <a href="/companies">Table</a> OR <a href="/company_card">Card Format</a>
    <button id="viewAllCompany" class="btn btn-sm mb-2 btn-secondary">View All Company</button>
    <button class="btn btn-sm btn-success mb-2" id="bulkAssignBtn">Assign Agent</button>

    <input type="text"
           id="companySearch"
           class="form-control mb-4"
           placeholder="Search Company or Contact Person...">

    <div id="summary" class="mb-3 text-muted"></div>

    <div class="row" id="companyList"></div>
    <div id="paginationContainer" class="mt-3"></div>
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

<select id="psc_id" name="psc_id" class="form-control">
    @foreach($users as $user)
        <option value="{{$user->emp_id}}">
            {{$user->first_name}} {{$user->last_name}}
        </option>
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


 <!-- Modal to update the Status of Attendees -->
 <div class="modal fade" id="statusModal">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">You are Updating the status of</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="participant_id">
         <p id="CompanyName" class="bg bg-success text-white p-2"></p>   
        <div class="mb-3">
            <label>Select Activity</label>
            <select class="form-control" id="lead_status">
                     <option value="">-- Select Category --</option>
                    @foreach($lead_agent_status as $lead_status)
                        <option value="{{ $lead_status->id }}" data-description="{{ $lead_status->description }}">
                            {{ $lead_status->lead_status }}
                        </option>
                    @endforeach
            </select>
        </div>

        <div class="mb-3" id="signedProposalFileWrapper" style="display:none;">
            <label>Upload Signed Proposal</label>
            <input type="file" class="form-control" id="signed_proposal_file">
        </div>

        <div class="mb-3" id="customerCodeWrapper" style="display:none;">
            <label>Customer Code</label>
            <input type="text" class="form-control" id="customer_code">
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


 <!-- Modal to update Address Modal -->
 <div class="modal fade" id="AddressModal">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Updating Address Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="company_id">
         <p id="company_name" class="bg bg-success text-white p-2"></p>   
    

        <div class="mb-3">
            <label>Input Addres</label>
            <textarea class="form-control" id="Address"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="saveAddress">
            Save
        </button>
      </div>

    </div>
  </div>
</div>
<!-- Ending of Modal Update Address Modal -->


<!-- Modal to update Contact Person Details Modal -->
 <div class="modal fade" id="ContactPersonUpdateModal">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Update Contact Details Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="p_id">
         <p id="contact_company_name" class="bg bg-success text-white p-2"></p>   
    

        <div class="mb-3">
            <label>Fullname</label>
            <input type="text" class="form-control" id="participant_name">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="text" class="form-control" id="participant_email">
        </div>

        <div class="mb-3">
            <label>Contact#</label>
            <input type="text" class="form-control" id="participant_contact">
        </div>

        


      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="SaveContactUpdates">
            Save
        </button>
      </div>

    </div>
  </div>
</div>
<!-- Ending update Contact Person Details Modal -->

<style>
.participant_checkbox{
    appearance: none;
    -webkit-appearance: none;
    width:18px;
    height:18px;
    border:2px solid red;
    border-radius:3px;
    background-color:white;
    cursor:pointer;
}
.participant_checkbox:checked{
    background-color:red;
}
.img-thumbnail{
    object-fit: cover;
}
</style>

<script>

$('#lead_status').on('change', function () {
    let selected_lead_status = $(this).val();
console.log(selected_lead_status);
    if (selected_lead_status == 9) {
        $('#signedProposalFileWrapper').show();
        $('#signed_proposal_file').prop('required', true);
    } 
    else {
        $('#signedProposalFileWrapper').hide();
        $('#signed_proposal_file').prop('required', false);
        $('#signed_proposal_file').val('');
    }

    if (selected_lead_status == 10) {
        $('#customerCodeWrapper').show();
        $('#customer_code').prop('required', true);
    } 
    else {
        $('#customerCodeWrapper').hide();
        $('#customer_code').prop('required', false);
        $('#customer_code').val('');
    }

    
});



//Ginamit ko ito para ma auto fill yung text area
document.getElementById('lead_status').addEventListener('change', function() {
    let selectedOption = this.options[this.selectedIndex];
    let description = selectedOption.getAttribute('data-description');

    document.getElementById('description').value = description ?? '';
});


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
            _token      : "{{ csrf_token() }}",
            participants: selected,
            psc_id      : psc_id
        },
        success:function(){

                 Swal.fire({
                icon             : 'success',
                title            : 'Saved!',
                text             : 'Successfully Assigned Agent.',
                timer            : 2000,
                showConfirmButton: false,
                toast            : true,
                position         : 'top-center'
                 });
            $('#assignModal').modal('hide');
            loadCompanies();

        }
    });

});


$(document).on('click','.btnAddContact', function(){

    let companyId = $(this).data('id');

    window.location.href ="http://127.0.0.1:8000/participant/create?company_id=" + companyId;

});


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



//Use to view Photo in carousel design
$(document).on('click','.viewImages', function(){
    let participant_id = $(this).data('participant-id');
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


let companiesData = []; // store AJAX response globally
let viewAllMode = false;

// Load Companies
function loadCompanies(url = "/company_card/list?page=1") {
    $.ajax({
        url: url,
        type: "GET",
        data: {
            search: $('#companySearch').val(),
            view_all: viewAllMode ? 1 : 0
        },
        beforeSend: function(){
            $('#companyList').html('<div class="text-center w-100">Loading...</div>');
        },
        success: function(response){
            companiesData = response.data;
            let html = '';

            response.data.forEach(company => {
                let participants = company.participants ?? [];
                let contactHtml = '';

                participants.forEach((c, index) => {
                    let imagesHtml = '';

                    if(c.images && c.images.length > 0){
                        let img = c.images[0];
                        imagesHtml = `
                            <img src="/storage/participants/${img.image_name}" 
                                 alt="${c.participant_name}"
                                 class="img-thumbnail participant-img viewImages mt-2"
                                 style="width:70px;height:70px;object-fit:cover;border-radius:8px;"
                                 data-participant-id="${img.participant_id}">
                        `;
                    } else {
                        imagesHtml = `<div class="text-muted small mt-2">No Images</div>`;
                    }

                    contactHtml += `
                        <div class="border rounded p-3 mb-3 bg-light">
                            <small class="text-muted fw-bold">Contact Person ${index + 1}</small>

                            <div class="fw-bold fs-6 mt-1">
                                ${c.participant_name ?? ''}
                            </div>

                            <div class="small mt-2">
                                📞 ${c.participant_contact ?? '-'}
                            </div>

                            <div class="small">
                                📧 ${c.participant_email ?? '-'}
                            </div>

                            <div class="small">
                                📍 ${c.participant_address ?? '-'}
                            </div>

                            ${imagesHtml}

                            <div class="small text-right">
                                 <button
                                    type="button"
                                    id="UpdateContact"
                                    class="btn btn-sm btn-secondary btn-edit-contact"
                                    data-companyname="${company.company_name}"
                                    data-id="${c.id}"
                                    data-name="${c.participant_name ?? ''}"
                                    data-contact="${c.participant_contact ?? ''}"
                                    data-email="${c.participant_email ?? ''}"
                                    data-address="${c.participant_address ?? ''}">
                                    ✏️ Edit
                                </button>
                            </div>
                        </div>
                    `;
                });

                let latestUpdateHtml = '';

                if(company.latest_updates && company.latest_updates.length > 0){
                    console.log(company.latest_updates[0]);
                    company.latest_updates.forEach(update => {
                        latestUpdateHtml += `
                            <div class="border-start border-4 border-warning ps-3 py-2 bg-light rounded-end mb-2">
                                <span class="badge bg-success mb-2">
                                    ${update.lead_status}
                                </span>

                                <div class="small text-muted">
                                    ${update.update_date}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    latestUpdateHtml = `
                        <div class="text-muted small">
                            No updates yet.
                        </div>
                    `;
                }

                let agentHtml = '';

                if(company.assigned_agent){
                    agentHtml = `
                                <div class="d-flex align-items-center gap-3 border rounded-3 p-3 bg-success bg-opacity-10">
                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                        style="width:45px;height:45px;">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div>
                                        <div class="fw-bold">
                                            ${company.assigned_agent.psc_name ?? 'N/A'}
                                        </div>

                                        <small class="text-muted">
                                            ID: ${company.assigned_agent.psc_emp_id ?? 'N/A'}
                                        </small>
                                    </div>
                                </div>
                            `;
                } else {
                    agentHtml = `
                        <div class="text-muted small">
                            No Agent Assigned
                        </div>
                    `;
                }

                //For Progress Bar
                let progressPercent = 0;
let progressColor = 'bg-secondary';
let progressLabel = 'No Status';

if (company.latest_updates && company.latest_updates.length > 0) {
    const latest = company.latest_updates[0];
    const statusId = parseInt(latest.status);

    const progressMap = {
        1: { percent: 10, color: 'bg-secondary' , label: 'New Lead' },
        2: { percent: 15, color: 'bg-secondary' , label: 'Uncontacted' },
        3: { percent: 25, color: 'bg-info'      , label: 'Contacted' },
        4: { percent: 40, color: 'bg-primary'   , label: 'Interested' },
        5: { percent: 50, color: 'bg-warning'   , label: 'Follow-up Needed' },
        6: { percent: 60, color: 'bg-warning'   , label: 'For RFQ' },
        7: { percent: 75, color: 'bg-primary'   , label: 'Received Proposal' },
        8: { percent: 85, color: 'bg-primary'   , label: 'Revised Proposal' },
        9: { percent: 95, color: 'bg-success'   , label: 'Signed Proposal' },
        10:{ percent:100, color: 'bg-success'   , label: 'Converted' },
        11:{ percent:100, color: 'bg-danger'    , label: 'Closed / Lost' },
        12:{ percent:100, color: 'bg-success'   , label: 'Existing Client' }
    };

    if (progressMap[statusId]) {
        progressPercent = progressMap[statusId].percent;
        progressColor   = progressMap[statusId].color;
        progressLabel   = progressMap[statusId].label;
    }
}

                //Ending For Progress Bar

                html += `
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden crm-card h-100">

                            <div class="card-body">

                                <!-- COMPANY HEADER -->
                                <div class="p-3 text-white rounded-4 mb-3" style="background: linear-gradient(135deg,#0d6efd,#6610f2);">
                                    <h5 class="fw-bold text-white mb-1">
                                        ${company.company_name ?? ''}
                                    </h5>

                                    <div class="small text-light">
                                        #${company.id}
                                        <i class="far fa-building ms-1"></i>
                                    </div>

                                    
                                    <div class="small text-white-50 fst-italic mt-1">
                                        ${company.address ?? 'No Address'}
                                        <i class="fas fa-edit text-secondary ms-2"
                                           style="cursor:pointer;"
                                           title="Update Address"
                                           id="UpdateAddressModal"
                                           data-c_id="${company.id}"
                                           data-company_name="${company.company_name}">
                                        </i>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                             <div class="mb-4">
                                <small class="text-muted fw-bold">Lead Pipeline</small>

                                <div class="progress mt-2" style="height:10px;">
                                    <div class="progress-bar ${progressColor}"
                                        role="progressbar"
                                        style="width:${progressPercent}%">
                                    </div>
                                </div>

                                <small class="fw-bold text-muted">
                                    ${progressLabel} (${progressPercent}%)
                                </small>
                            </div>
                                



                                <!-- ASSIGN CHECKBOX -->
                                <div class="mb-4">
                                    <input type="checkbox"
                                           class="participant_checkbox"
                                           value="${company.id}">
                                    <small class="text-danger fst-italic">
                                        Check to Assign Agent
                                    </small>
                                </div>

                                <!-- CONTACT PERSONS -->
                                <div class="mb-4">
                                    <div class="bg-primary text-white rounded px-3 py-2 mb-3">
                                        Company Contacts
                                    </div>
                                    <div class="scrollable_div" style="height: 300px; overflow-y: auto;">
                                    ${contactHtml}
                                    </div>  <!-- Ending of Scrollable Div -->
                                </div>

                                <!-- AGENT DETAILS -->
                                <div class="mb-4">
                                    <div class="bg-success text-white rounded px-3 py-2 mb-3">
                                        Agent Details
                                    </div>

                                    ${agentHtml}
                                </div>

                                <!-- LATEST UPDATE -->
                                <div class="mb-4">
                                    <div class="bg-warning text-dark rounded px-3 py-2 mb-3">
                                        Latest Agent Update
                                    </div>

                                    ${latestUpdateHtml}
                                </div>

                                <!-- ACTION BUTTONS -->
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-secondary w-50 btnUpdateStatus"
                                            data-id="${company.id}"
                                            data-cname="${company.company_name ?? ''}">
                                        Update Status
                                    </button>

                                    <button class="btn btn-sm btn-warning w-50 btnAddContact"
                                            data-id="${company.id}">
                                        Add Contact
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                `;
            });

            $('#companyList').html(html);
            buildPagination(response);

            $('#summary').html(`
                Showing ${response.from} - ${response.to}
                of ${response.total} companies
            `);
        }
    });
}

// Pagination builder
function buildPagination(response){
    let pagination = '';
    if(response.last_page > 1){
        pagination += `<nav><ul class="pagination justify-content-center">`;
        if(response.prev_page_url){
            pagination += `<li class="page-item">
                <a class="page-link pagination-link" href="#" data-url="${response.prev_page_url}">Previous</a>
            </li>`;
        }
        for(let i=1; i<=response.last_page; i++){
            if(i >= response.current_page - 2 && i <= response.current_page + 2){
                pagination += `<li class="page-item ${i == response.current_page ? 'active' : ''}">
                    <a class="page-link pagination-link" href="#" data-url="/company_card/list?page=${i}">${i}</a>
                </li>`;
            }
        }
        if(response.next_page_url){
            pagination += `<li class="page-item">
                <a class="page-link pagination-link" href="#" data-url="${response.next_page_url}">Next</a>
            </li>`;
        }
        pagination += `</ul></nav>`;
    }
    $('#paginationContainer').html(pagination);
}

// Document ready
$(document).ready(function(){
    loadCompanies();

    let searchTimeout;
    $('#companySearch').on('keyup', function(){
        viewAllMode = false;
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadCompanies("/company_card/list?page=1");
        }, 400);
    });
});

// Pagination click
$(document).on('click', '.pagination-link', function(e){
    e.preventDefault();
    let url = $(this).data('url');
    if(viewAllMode){
        loadCompanies(url + "&view_all=1");
    }else{
        loadCompanies(url + "&search=" + $('#companySearch').val());
    }
});


//Use to update status of attendees
$('#saveStatus').click(function(){

    var id            = $('#participant_id').val();
    var lead_status   = $('#lead_status').val();
    var description   = $('#description').val();
    var files         = $('#signed_proposal_file')[0].files;
    var customer_code = $('#customer_code').val();

    // Required only if status = 9
    if (lead_status == 9 && files.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'File Required',
            text: 'Please upload the signed proposal file.'
        });
        return;
    }

     if (lead_status == 10 && !customer_code) {
            Swal.fire({
                icon: 'warning',
                title: 'Customer Code Required',
                text: 'Please input the customer code'
            });
            return;
        }

    let formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('status', lead_status);
    formData.append('description', description);
    formData.append('customer_code', customer_code);

    // Attach files only if exists
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }

    $.ajax({
        url: "/participants/update-status/" + id,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){

            $('#participant_id').val('');
            $('#lead_status').val('').trigger('change');
            $('#description').val('');
            $('#customer_code').val('');
            $('#signed_proposal_file').val('');
            $('#signedProposalFileWrapper').hide();
            $('#customerCodeWrapper').hide();


            $('#statusModal').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Successfully Updated the lead status.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-center'
            });

            loadCompanies();
        }
    });

});

//Use to open the modal only
$(document).on('click', '.btnUpdateStatus', function(){

    var id          = $(this).data('id');
    var cname       = $(this).data('cname');
    var lead_status = $(this).data('lead_status');

    $('#CompanyName').text(cname);
     $('#participant_id').val(id);
    $('#lead_status').val(lead_status);

    
    $('#statusModal').modal('show');
});

//Use to open the Modal and display details about company
$(document).on('click', '#UpdateAddressModal', function(){

    var c_id         = $(this).data('c_id');
    var company_name = $(this).data('company_name');
    var status       = $(this).data('status');

    //alert(company_name)

    $('#company_name').text(company_name);
     $('#company_id').val(c_id);
    $('#status').val(status);

    
    $('#AddressModal').modal('show');
});



//Use to open the Modal Updating Contact Person Form
//By Clicking the Edit BUtton per Contact
$(document).on('click', '#UpdateContact', function(){

    var p_id        = $(this).data('id');
    var companyname = $(this).data('companyname');
    var name        = $(this).data('name');
    var email       = $(this).data('email');
    var contact     = $(this).data('contact');

    console.log(companyname);
    //alert(company_name)

    $('#contact_company_name').text(companyname);
    $('#p_id').val(p_id);
    $('#participant_name').val(name);
    $('#participant_email').val(email);
    $('#participant_contact').val(contact);

    
    $('#ContactPersonUpdateModal').modal('show');
});


//Use to update CompanyAddress
$('#saveAddress').click(function(){

    let company_id = $('#company_id').val();
    let address    = $('#Address').val();

   console.log(address)

    $.ajax({
        url: '/companies/update-address',
        type: 'POST',
        data: {
            _token    : '{{ csrf_token() }}',
            company_id: company_id,
            address   : address
        },
        success: function(response){
            $('#CompanyTbl').DataTable().ajax.reload(null, false);
           // alert('Address updated!');
            Swal.fire({
                icon             : 'success',
                title            : 'Saved!',
                text             : 'Company Address has been updated!',
                timer            : 2000,
                showConfirmButton: false,
                toast            : true,
                position         : 'top-center'
                 });
            $('#AddressModal').modal('hide');
            $('#Address').val('');
          //  loadCompanies();
          //$('#CompanyTbl').DataTable().ajax.reload();
          loadCompanies();
        }
    });

});


//Use to update Contact Person Details
$('#SaveContactUpdates').click(function(){

    let p_id                = $('#p_id').val();
    let participant_name    = $('#participant_name').val();
    let participant_email   = $('#participant_email').val();
    let participant_contact = $('#participant_contact').val();

    $.ajax({
        url: '/companies/update-contactdetails',
        type: 'POST',
        data: {
            _token             : '{{ csrf_token() }}',
            p_id               : p_id,
            participant_name   : participant_name,
            participant_email  : participant_email,
            participant_contact: participant_contact
   
        },
        success: function(response){
          //  $('#CompanyTbl').DataTable().ajax.reload(null, false);
           // alert('Address updated!');
            Swal.fire({
                icon             : 'success',
                title            : 'Saved!',
                text             : 'Contact Details has been updated!',
                timer            : 2000,
                showConfirmButton: false,
                toast            : true,
                position         : 'top-center'
                 });
            $('#ContactPersonUpdateModal').modal('hide');
            $('#participant_name').val('');
            $('#participant_email').val('');
            $('#participant_contact').val('');
          //  loadCompanies();
          //$('#CompanyTbl').DataTable().ajax.reload();
          loadCompanies();
        }
    });

});

</script>

<style>
    .card:hover{
    transform: translateY(-3px);
    transition: .2s ease;
}

.participant-img:hover{
    transform: scale(1.05);
    transition: .2s ease;
}

.crm-card{
    transition: all .25s ease;
}

.crm-card:hover{
    transform: translateY(-6px);
    box-shadow: 0 1rem 2rem rgba(0,0,0,.15)!important;
}

.participant-img:hover{
    transform: scale(1.08);
    transition: .2s ease;
}
    </style>

@endsection