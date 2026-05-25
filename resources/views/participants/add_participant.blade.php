@extends('layouts.app')

@section('content')

<div class="container mt-4">

<h3 class="mb-4">Add Participant</h3>

<form id="participantForm" enctype="multipart/form-data">

@csrf
<div id="" style=" padding:8px; border-style: solid; border-width: 1px; border-color: orange; margin-bottom:8px;">
    <div class="participant-type-card shadow-sm">
    
    <div class="mb-2">
        <small class="text-muted fw-semibold">
            Select Participant Type
        </small>
    </div>

    <div class="d-flex flex-wrap gap-2">

        <button type="button" class="btn participant-btn company-btn" id="companybtn" value="You are under Company">
            <i class="bi bi-building"></i>
            <span>Company</span>
        </button>

        <button type="button" class="btn participant-btn student-btn" id="studentbtn">
            <i class="bi bi-mortarboard"></i>
            <span>Student</span>
        </button>

        <button type="button" class="btn participant-btn freelancer-btn" id="freelancerbtn">
            <i class="bi bi-person-workspace"></i>
            <span>Freelancer</span>
        </button>

    </div>
</div>
</div>

<div id="WelcomeDiv" style="padding:8px; border-style: solid; border-width: 1px; border-color: orange; margin-bottom:8px;  display:none;"><span id="WelcomeText"></span></div>

<div id="CompanyNotExist" style=" padding:8px; border-style: solid; border-width: 1px; border-color: orange; margin-bottom:8px; display:none;">
<input type="checkbox" id="NoCompany" class="mb-3  big-checkbox" style="font-size:18px;"> <span class="text-danger">Check if Company Name is not existing</span>
<input type="text" name="company_id_new"  id="company_id_new" class="form-control mb-3"  style="display:none;"   placeholder="Input Company Name">
<button class="btn btn-success" id="btnNewCompany" style="display:none;">Save New Company</button>
</div>


<div id="CompanyDetailsDiv" style=" padding:8px; border-style: solid; border-width: 1px; border-color: orange; margin-bottom:8px; display:none;">

<input type="hidden" name="participant_type" id="participantType">
<!-- depende sa selection ng Type -->

<div  style="background-color: orange; width:100%; padding:4px; font-weight:500; color:black;">Company Details</div>



<div class="mt-2">
<span style="font-size:12px; font-weight:500;">Company Name</span>

        
        <select name="company_id" id="company_id" class="form-control select2 mb-3" style="font-size:10px; width:100%">
        <option value="">Select Company</option>

        @foreach($companies as $company)
        <option value="{{$company->id}}" data-company_name="{{$company->company_name}}"
            {{ 
            (isset($selected_company_id) && $selected_company_id == $company->id) 
            || 
            (isset($company_id_new) && $company_id_new == $company->company_name) 
            ? 'selected' : '' 
            }}  
        >
        {{ $company->company_name }}
        </option>
       
        @endforeach
        </select>
</div>

<div class="mt-2">
<span style="font-size:12px; font-weight:500;">Company Address</span>
@php
    $selectedAddress = '';
@endphp

@foreach($companies as $company)
    @if($company['id'] == $selected_company_id)
        @php
            $selectedAddress    = $company['address'];
            $city_province_code = $company['city_province_code'];
        @endphp
        @break
    @endif
@endforeach

<input type="text" name="address" id="address" class="form-control mb-3" placeholder="Complete Company Address" value="{{ $selectedAddress }}" style="font-size:12px; width:100%">
</div>

<!-- <div class="mt-2">
<span style="font-size:12px; font-weight:500;">Company City/Province</span>
<select id="city_province" name="city_province" class="form-control select2 mb-3" style="font-size:12px; width:100%">
    <option value="">Select City/Province</option>

    @foreach($addresses as $address)
        <option value="{{ $address->cor_code }}"
             {{ isset($city_province_code) && $city_province_code == $address->cor_code ? 'selected' : '' }}>
            {{ $address->address_name }}
        </option>
    @endforeach
</select>
</div> -->

<div class="mt-2">
<!-- span style="font-size:12px; font-weight:500;">Company Category</span>
@php
    $selectedCompany = collect($companies)->firstWhere('id', request('company_id'));
@endphp -->

<!-- <select name="level_type" id="level_type" class="form-control select2 mb-3" style="font-size:12px; width:100%">
    <option value="">Select Level</option>

    <option value="High"
        {{ ($selectedCompany['level_type'] ?? '') == 'High' ? 'selected' : '' }}>
        High
    </option>

    <option value="Mid"
        {{ ($selectedCompany['level_type'] ?? '') == 'Mid' ? 'selected' : '' }}>
        Mid
    </option>

    <option value="Low"
        {{ ($selectedCompany['level_type'] ?? '') == 'Low' ? 'selected' : '' }}>
        Low
    </option>
</select> -->

</div>

</div>
<!-- Ending of Company Details -->
<br>

<div id="ContactPersonDetailsDiv" style=" padding:8px; border-style: solid; border-width: 1px; border-color: orange; display:none;">

<div  style="background-color: orange; width:100%; padding:6px; font-weight:500; color:black;"><span id="ContactPerson"></span>&nbsp; Details</div>
<hr>


<!-- <div id="OtherFields"> -->

<div class="mt-2" id="contact_person_div" >
<span style="font-size:12px; font-weight:500;">Select Contact Person</span>
<select id="contact_person" name="contact_person" class="form-control select2 mb-3"  style="font-size:12px; width:100%">
    <option value="">Select Contact</option>
</select>
</div>

<div class="mt-2">
<span style="font-size:12px; font-weight:500;">Contact Fullname</span>
<input type="text" name="participant_name" id="participant_name" class="form-control mb-3" placeholder="Participant Name">
</div>


<div class="mt-2">
<span style="font-size:12px; font-weight:500;">Contact Email Address</span>
<input type="email" id="email" name="email" class="form-control mb-3" placeholder="Email" >
</div>

<div class="mt-2">
<span style="font-size:12px; font-weight:500;">Contact Number</span>
<input type="text" id="contact" name="contact" class="form-control mb-3" maxlength="11" placeholder="Contact">
</div>



<!-- <div class="mt-2">
    <span style="font-size:12px; font-weight:500;">Contact Job Designation</span>
<input type="text" name="participant_position" class="form-control mb-3" placeholder="Participant Postion">
</div> -->

<!-- <div class="mt-2">
    <span style="font-size:12px; font-weight:500;">Exhibit Name</span>
 <select name="exhibit_name" id="exhibit_name" class="form-control mb-3" readonly>
                            <option value="">Select</option>
                            <option value="WorldBex" selected>WorldBex</option>
                            <option value="PHA">PHA</option> 
                            <option value="PhilConstruct">PhilConstruct</option> 
 </select>
</div> -->
<!-- 
<div class="mt-2">
    <span style="font-size:12px; font-weight:500;">Contact Inquiry</span>
<input type="text" name="participant_remarks" class="form-control mb-3" placeholder="Participant Inquiry">
</div> -->



<!-- <div class="mt-2">
    <span style="font-size:12px; font-weight:500;">Upload Photo of Contact Person</span>
 <input type="file" name="participant_photo[]" id="participantPhoto" class="form-control mb-3" multiple>
</div> -->

<div class="mt-2">
    <label for="participantPhoto" class="form-label d-block" style="font-size:12px; font-weight:500;">
        Upload Photo of Contact Person
    </label>

    <label for="participantPhoto" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
        <i class="bi bi-image"></i> Choose Images
    </label>

    <input 
        type="file" 
        name="participant_photo[]" 
        id="participantPhoto" 
        class="d-none" 
        multiple
        accept="image/*"
    >
</div>


<div class="mt-2 text-right ">
<button class="btn btn-success" id="saveBtn" style=" width:20%; margin-top:20px;margin-bottom:20px;">
Save
</button>
</div>

</form>

<!-- </div> -->

</div>
<script>


$('#companybtn').click(function(e){
    $('#CompanyNotExist').show(); 
    $('#CompanyDetailsDiv').show();  
    $('#WelcomeDiv').show(); 
    $('#WelcomeText').text('You are under Company'); 
    $('#ContactPerson').text('Contact Person'); 
    
    $('#ContactPersonDetailsDiv').show(); 

     $('#contact_person_div').show(); 
    $('#participantType').val('Company');
});

$('#studentbtn').click(function(e){
    $('#CompanyNotExist').hide(); 
    $('#CompanyDetailsDiv').hide();  
     $('#WelcomeDiv').show(); 
    $('#WelcomeText').text('Welcome Student');   
     $('#ContactPerson').text('Student'); 
      $('#ContactPersonDetailsDiv').show(); 

       $('#contact_person_div').hide(); 
       $('#participantType').val('Student');
      
});

$('#freelancerbtn').click(function(e){
    $('#CompanyNotExist').hide(); 
    $('#CompanyDetailsDiv').hide(); 
     $('#WelcomeDiv').show(); 
    $('#WelcomeText').text('Welcome Freelancer'); 
    $('#ContactPerson').text('Freelancer'); 
     $('#ContactPersonDetailsDiv').show();   
     $('#participantType').val('Freelancer');
});


function toggleCompanyUI(isNoCompanyChecked) {

    if (isNoCompanyChecked) {
        $('#company_id_new').show();
        $('#btnNewCompany').show();
        $('#OtherFields').hide();
        $('#company_id').next('.select2-container').hide();

        $('#CompanyDetailsDiv').hide();
        $('#ContactPersonDetailsDiv').hide();

    } else {
        $('#company_id_new').hide();
        $('#btnNewCompany').hide();
        $('#company_id').next('.select2-container').show();
        $('#OtherFields').show();

        $('#CompanyDetailsDiv').show();
        $('#ContactPersonDetailsDiv').show();
    }
}

$('#NoCompany').change(function () {
    toggleCompanyUI($(this).is(':checked'));
});



$(document).ready(function(){

   /*  $('#NoCompany').change(function(){

        if($(this).is(':checked')){
            $('#company_id_new').show();
            $('#btnNewCompany').show();
            $('#OtherFields').hide();
            $('#company_id').next('.select2-container').hide();

             $('#CompanyDetailsDiv').hide();
             $('#ContactPersonDetailsDiv').hide();
             
        }else{
            $('#company_id_new').hide();
            $('#btnNewCompany').hide();
            $('#company_id').next('.select2-container').show();
            $('#OtherFields').show();
            $('#CompanyDetailsDiv').show();
            $('#ContactPersonDetailsDiv').show();
        }

    }); */







$('#btnNewCompany').click(function(e){
e.preventDefault();
    let companyName = $('#company_id_new').val();
  //  $('#company_new_encoded').val(companyName);


    $.ajax({
        url: "/company/save",
        type: "POST",
        data: {
            _token      : $('meta[name="csrf-token"]').attr('content'),
            company_name: companyName
        },

        success:function(res){

    if(res.success){

        let company = res.company;

        let newOption = new Option(
            company.company_name,
            company.id,
            true,
            true
        );

        $('#company_id')
            .append(newOption)
            .val(company.id)
            .trigger('change');

        // 🔥 FORCE RESET STATE HERE
        $('#NoCompany').prop('checked', false);

        // override UI manually
        toggleCompanyUI(false);

        // extra cleanup
        $('#company_id_new').val('');
        $('#btnNewCompany').hide();
    }
}

       /*  success:function(res){

       // console.log(res);

            if(res.success){

        let company = res.company;

let newOption = new Option(
    company.company_name,   // TEXT
    company.id,             // VALUE
    true,
    true
);

        // add to select
        $('#company_id').append(newOption);

        // update select2 UI
        $('#company_id').trigger('change');

                // Reset UI
                $('#company_id').next('.select2-container').show();
                $('#OtherFields').show();
              $('#company_id_new').hide();
                $('#btnNewCompany').hide();


                // Uncheck checkbox
                $('#NoCompany').prop('checked', false);
                $('#CompanyDetailsDiv').show();   
                 $('#contact_person_div').show();
                // Clear textbox
                $('#company_id_new').val('');

                // Show other fields
                $('#participantFields').show();
            }
        } */

    });

});

});


let isDuplicate = false;

/* Duplicate Detection */
$('#email,#contact').on('keyup change', function(){

    let email   = $('#email').val();
    let contact = $('#contact').val();

    $.ajax({
        url:'/participant/check-duplicate',
        data:{
            email:email,
            contact:contact
        },
        success:function(res){

            if(res.exists){

                isDuplicate = true;
                $('#saveBtn').prop('disabled',true);

                let fields = res.fields.join(" & ");

                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Detected',
                    text: fields + ' already exist in the database',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-center'
                });

            }else{

                isDuplicate = false;
                $('#saveBtn').prop('disabled',false);

            }

        }
    });

});



$('#saveBtn').on('click', function(e){

    e.preventDefault();

    if(isDuplicate){
        Swal.fire({
            icon: 'error',
            title: 'Duplicate',
            text: 'Cannot save duplicate participant.',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-center'
        });
        return;
    }

    let btn      = $('#saveBtn');
    let form     = $('#participantForm')[0];  // get native DOM element
    let formData = new FormData(form);        // FormData includes files

    // kunin ang text
let company_name = $('#company_id option:selected').data('company_name');
formData.append('company_name', company_name);

    if (!form.checkValidity()) {
    form.reportValidity(); // automatic browser validation
    return;
    }

    $.ajax({
        url: '/participant/store-ajax',
        type: 'POST',
        data: formData,
        processData: false, // important!
        contentType: false, // important!
        beforeSend: function(){
            btn.html('<span class="spinner-border spinner-border-sm"></span> Saving...');
            btn.prop('disabled', true);
        },
        success: function(res){

            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'Successfully Added Participant.',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-center'
            });

            $('#participantForm')[0].reset();
            $('.select2').val(null).trigger('change');
        },
        error: function(xhr){
            console.log(xhr.responseText);
        },
        complete: function(){
            btn.html('Save');
            btn.prop('disabled', false);
        }
    });

});

</script>


<script>
$(document).ready(function(){
    $('.select2').select2();
});


/* $(document).ready(function(){

    $('.select2').select2();

    $('#company_id').on('change', function() {
        let company_id = $(this).val();

        console.log('Selected:', company_id);

        if(company_id){
            $.ajax({
                url: '/get-company/' + company_id,
                type: 'GET',
                success: function(data){
                    console.log(data);
                    $('#address').val(data.address);
                     // auto select city in select2
                $('#city_province').val(data.city_province_code).trigger('change');
                $('#level_type').val(data.level_type).trigger('change');
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });

              // ✅ Company Info
            $.ajax({
                url: '/get-company/' + company_id,
                type: 'GET',
                success: function(data){

                    $('#address').val(data.address);
                    $('#city_province').val(data.city_province_code).trigger('change');
                    $('#level_type').val(data.level_type).trigger('change');
                }
            });

            // ✅ Contact Persons
            $.ajax({
                url: '/get-contacts/' + company_id,
                type: 'GET',
                success: function(data){

                    let options = '<option value="">Select Contact Person</option>';

                    data.forEach(function(contact){
                        options += `<option value="${contact.id}">${contact.participant_name}</option>`;
                    });

                    $('#contact_person')
                        .html(options)
                        .trigger('change');
                }
            });





        } 
        
        else {
            $('#address').val('');
        }
    });

}); */

$(document).ready(function(){

    $('.select2').select2();

    $('#company_id').on('change', function() {

        let company_id = $(this).val();

        if(company_id){

            // ✅ Company info (ONLY ONCE)
            $.ajax({
                url: '/get-company/' + company_id,
                type: 'GET',
                success: function(data){

                    console.log('Company:', data);

                    $('#address').val(data.address);
                    $('#city_province').val(data.city_province_code).trigger('change');
                    $('#level_type').val(data.level_type).trigger('change');
                },
                error: function(xhr){
                    console.log('Company Error:', xhr.responseText);
                }
            });

            // ✅ Contacts
            $.ajax({
                url: '/get-contacts/' + company_id,
                type: 'GET',
                success: function(data){

    let options = '<option value="">Select Contact Person</option><option value="New Contact">Add New Contact</option>';

    if(data.length > 0){

        data.forEach(function(contact){
            options += `<option value="${contact.id}">${contact.participant_name}</option>`;
        });

        $('#contact_person')
            .html(options)
            .trigger('change');

        // ✅ SHOW kapag may laman
        $('#contact_person').closest('.form-control').show();
        $('#contact_person_div').show();

    } else {
    console.log('No contact');
        // ❌ HIDE kapag walang contacts
        $('#contact_person')
            .html(options)
            .trigger('change');

            $('#contact_person_div').hide();
             $('#participant_name').val('');
             $('#email').val('');
             $('#participant_contact').val('');
             $('#participant_position').val('');

      //  $('#contact_person').closest('.form-control').hide();
    }
},
                error: function(xhr){
                    console.log('Contacts Error:', xhr.responseText);
                }
            });

        } else {

            $('#address').val('');

            $('#contact_person')
                .html('<option value="">Select Contact Person</option>')
                .trigger('change');
        }
    });


    //Second Set of Onchange
       $('#contact_person').on('change', function() {

        let id = $(this).val(); //this is a table id

        if(id){

            // ✅ Company info (ONLY ONCE)
            $.ajax({
                url: '/get-contacts_details/' + id,
                type: 'GET',
                success: function(data){

                    console.log('Contact Details:', data);
                   console.log(data[0].participant_email);
                    $('#participant_name').val(data[0].participant_name);
                    $('#email').val(data[0].participant_email);
                    $('#participant_contact').val(data[0].participant_contact);
                    $('#participant_position').val(data[0].participant_position);

                    //$('#city_province').val(data.city_province_code).trigger('change');
                    //$('#level_type').val(data.level_type).trigger('change');
                },
                error: function(xhr){
                    console.log('Company Error:', xhr.responseText);
                }
            });

            // ✅ Contacts
           /*  $.ajax({
                url: '/get-contacts/' + company_id,
                type: 'GET',
                success: function(data){

            let options = '<option value="">Select Contact Person</option>';


            },
                error: function(xhr){
                    console.log('Contacts Error:', xhr.responseText);
                }
            }); */

        } else {

            /* $('#address').val('');

            $('#contact_person')
                .html('<option value="">Select Contact Person</option>')
                .trigger('change'); */
        }
    });

});///ending of Document Ready

</script>

<style id="tcc29v">
.participant-type-card{
    padding:15px;
    border:1px solid #e9ecef;
    border-radius:12px;
    background:#fff;
}

.participant-btn{
    border-radius:10px;
    padding:10px 18px;
    font-size:14px;
    font-weight:600;
    display:flex;
    align-items:center;
    gap:8px;
    transition:all .2s ease;
    border:none;
}

.participant-btn i{
    font-size:16px;
}

.participant-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

.company-btn{
    background:#fff3cd;
    color:#856404;
}

.student-btn{
    background:#e2e3e5;
    color:#41464b;
}

.freelancer-btn{
    background:#d1e7dd;
    color:#0f5132;
}

.company-btn:hover{
    background:#ffe69c;
}

.student-btn:hover{
    background:#d6d8db;
}

.freelancer-btn:hover{
    background:#badbcc;
}
</style>

@endsection
