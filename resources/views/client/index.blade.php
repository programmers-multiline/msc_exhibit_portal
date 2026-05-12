@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0 fw-bold">Client Directory</h3>
            <small class="text-muted">Manage clients and assigned agents</small>
        </div>

        <div class="d-flex gap-2">
            <a href="/client" class="btn btn-outline-primary btn-sm">Table View</a>
            <a href="/client_card" class="btn btn-outline-secondary btn-sm">Card View</a>
            <button class="btn btn-success btn-sm" id="bulkAssignBtn">
                + Assign Agent
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm border-0 p-2">
        <div class="card-body p-0">

            <div class="table-responsive ">
                <table id="ClientTbl" class="table table-hover table-bordered  align-middle mb-0 crm-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Code</th>
                            <th>Company</th>
                            <th>Address</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>PSC Name</th>
                            <th>Assigned PSC</th>
                            <th>Date Assigned</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>




<style>
#ClientTbl {
    table-layout: fixed;
    width: 100%;
}

/* #ClientTbl th, 
#ClientTbl td {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
} */

#ClientTbl th:nth-child(1),
#ClientTbl td:nth-child(1) {
    width: 80px;  
}

#ClientTbl tbody td:nth-child(2) {
    white-space: normal !important;
    word-break: break-word;
    overflow: visible;
}

#ClientTbl td:nth-child(1) {
    width: 80px;
    color: #0d6efd;
}

#ClientTbl th:nth-child(3),
#ClientTbl td:nth-child(3) {
    width: 250px;
    white-space: normal;
    word-break: break-word;
}

#ClientTbl td:nth-child(5) {
    white-space: normal;
    word-break: break-word;
}

  .crm-table {
    font-size: 13px;
}

.crm-table thead th {
    font-weight: 600;
    letter-spacing: 0.3px;
    border: none !important;
}

.crm-table tbody tr {
    transition: all 0.2s ease-in-out;
}

.crm-table tbody tr:hover {
    background: #f8f9fa;
    transform: scale(1.001);
}

/* Better spacing */
.crm-table td {
    padding: 12px 10px !important;
    vertical-align: middle;
    white-space: nowrap;
    word-wrap: break-word;
}

/* Buttons alignment */
.btn {
    border-radius: 6px;
}

/* Optional badge style for PSC */
.badge-psc {
    background: #e7f1ff;
    color: #0d6efd;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
}

</style>
<script>



$(document).ready(function() {
        $(function () {
            $('#ClientTbl').DataTable({
                processing : true,
                serverSide : true,
                deferRender: true,
                pageLength : 10,
                searchDelay: 500,
                ajax       : '/client/list',
                autoWidth  : false,
                scrollX    : false,
                columns    : [
                    { data: 'customer_code' },
                    { data: 'customer_name' },
                    { data: 'customer_address' },
                    { data: 'contact_person' },
                    { data: 'email' },
                    { data: 'mob_num' },
                    { data: 'psc_name' },
                    { data: 'full_name' },
                    { data: 'assigned_date' }
                ]
            });
        });
});
</script>
@endsection