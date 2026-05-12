@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h3 class="mb-2">Company Directory</h3>
 <a href="/companies">Table </a> OR <a href="/company_card">Card Format</a>   <button class="btn btn-sm btn-success mb-2" id="bulkAssignBtn">Assign Agent</button>
 <div class="table-responsive">
   <table id="CompanyTbl" class="table table-bordered  hover bg bg-white w-100">
    <thead>
        <tr class="bg bg-dark text-white">
            <th >Action</th>
             <!-- <th >#</th> -->
            <th class="table-plus" >Company Name</th>
            <th>Contact Persons</th>
            <th>Company Address</th>
            <th>Agent Name</th>
        </tr>
    </thead>
</table>
</div>
</div>


<!-- MOdal for Assigning of PSC -->
 <div class="modal fade" id="assignModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Select PSC</h5>
</div>

<div class="modal-body">

<select id="psc_id" name="psc_id" class="form-control" style="text-transform:uppercase;">
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

<style>
    #CompanyTbl td {
    white-space: normal !important;
    word-wrap: break-word;
}
   
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

</style>
<script>
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
          //  loadCompanies();
          $('#CompanyTbl').DataTable().ajax.reload();

        }
    }); 


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


$(document).ready(function() {
   $('#CompanyTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "/companies",

       /*  columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'company_name', name: 'company_name'},
            { data: 'contact_persons', name: 'contact_persons' },
            { data: 'company_address', name: 'company_address' },
            { data: 'psc_name', name: 'psc_name' }
        ] */
         columns: [
        { 
            data: 'checkbox', 
            name: 'checkbox', 
            orderable: false, 
            searchable: false 
        },
       // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
        { data: 'company_name', name: 'company_name'},
        { data: 'contact_persons', name: 'contact_persons' },
        { data: 'company_address', name: 'company_address' },
        { data: 'psc_name', name: 'psc_name' }
    ]
    });
});
</script>
@endsection