@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <!-- Header -->
   <div class="mb-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

        <!-- LEFT -->
        <div>
            <h3 class="mb-0 fw-bold">Client Directory</h3>
            <small class="text-muted">Manage clients and assigned agents</small>
        </div>

        <!-- RIGHT ACTIONS -->
        <div class="d-flex gap-2">
            <a href="/client" class="btn btn-outline-primary btn-sm">Table View</a>
            <a href="/client_card" class="btn btn-outline-secondary btn-sm active">Card View</a>
            <button class="btn btn-success btn-sm" id="bulkAssignBtn">
                + Assign Agent
            </button>
        </div>

    </div>

    <!-- SEARCH BAR (separate row = cleaner UI) -->
    <div class="mt-3">
        <input type="text" id="clientSearch" class="form-control"
            placeholder="🔍 Search client (name, code, address, agent...)">
    </div>

</div>

    <div class="mb-2">
        <span class="badge bg-success">
            Total Clients: <span id="totalClients">0</span>
        </span>
    </div>

    <!-- Card Container -->
    <div class="row" id="clientCardContainer"></div>
    <div id="paginationLinks" class="mt-4 d-flex justify-content-center"></div>

</div>


<style>
.client-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    transition: 0.25s ease;
    height: 100%;
    background: #fff;
}

.client-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 26px rgba(13,110,253,0.15);
}

/* Header color accent */
.section-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: #fff;
    margin: 15px -15px 12px -15px;
    padding: 10px 15px;
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    border-radius: 0;
}

/* Labels */
.detail-label {
    font-size: 11px;
    color: #6c757d;
    margin-bottom: 2px;
}

/* Values */
.detail-value {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 10px;
    color: #212529;
}

/* Highlight code */
.detail-value.text-primary {
    font-weight: 700;
    color: #0d6efd !important;
}

/* Agent badge upgraded */
.agent-badge {
    background: linear-gradient(135deg, #e7f1ff, #d1e7ff);
    color: #0d6efd;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    border: 1px solid #b6d4fe;
}

/* Card padding feel */
.client-card .card-body {
    padding: 10px;
}

#paginationLinks button {
    min-width: 38px;
    border-radius: 6px;
}

#paginationLinks {
    overflow-x: auto;
    padding-bottom: 10px;
}

</style>


<script>
$(document).ready(function () {
    loadClientCards();

    $('#clientSearch').on('keyup', function () {
        let search = $(this).val();
        loadClientCards(1, search);
    });
});

function loadClientCards(page = 1, search = '') {
    $.ajax({
        url: '/client/Cardlist?page=' + page,
        type: 'GET',
        data: {
            search: search
        },
        success: function (res) {

             $('#totalClients').text(res.total.toLocaleString());
            let html = '';

            res.data.forEach(client => {
                html += `
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card client-card p-3">

                            <div class="section-title">Client Details</div>

                            <div class="detail-label">Code</div>
                            <div class="detail-value text-primary">${client.customer_code ?? '-'}</div>

                            <div class="detail-label">Company</div>
                            <div class="detail-value">${client.customer_name ?? '-'}</div>

                            <div class="detail-label">Address</div>
                            <div class="detail-value">${client.customer_address ?? '-'}</div>

                            <div class="section-title mt-3">Agent Details</div>

                            <div class="detail-label">PSC Name</div>
                            <div class="detail-value">
                                <span class="agent-badge">${client.psc_name ?? '-'}</span>
                            </div>

                            <div class="detail-label">Assigned PSC</div>
                            <div class="detail-value">${client.full_name ?? '-'}</div>

                        </div>
                    </div>
                `;
            });

            $('#clientCardContainer').html(html);

            renderPagination(res);
        }
    });
}

function renderPagination(res) {
    let html = '';
    let current = res.current_page;
    let last = res.last_page;

    if (last <= 1) return;

    // Prev button
    html += `
        <button class="btn btn-sm btn-outline-primary mx-1"
        ${current === 1 ? 'disabled' : ''}
        onclick="loadClientCards(${current - 1}, $('#clientSearch').val())">
            ‹ Prev
        </button>
    `;

    // Page range (smart window)
    let start = Math.max(1, current - 2);
    let end = Math.min(last, current + 2);

    if (start > 1) {
        html += `<button class="btn btn-sm btn-outline-primary mx-1"
            onclick="loadClientCards(1, $('#clientSearch').val())">1</button>`;
        if (start > 2) html += `<span class="mx-1">...</span>`;
    }

    for (let i = start; i <= end; i++) {
        html += `
            <button class="btn btn-sm mx-1 ${
                i === current ? 'btn-primary' : 'btn-outline-primary'
            }"
            onclick="loadClientCards(${i}, $('#clientSearch').val())">
                ${i}
            </button>
        `;
    }

    if (end < last) {
        if (end < last - 1) html += `<span class="mx-1">...</span>`;
        html += `<button class="btn btn-sm btn-outline-primary mx-1"
            onclick="loadClientCards(${last}, $('#clientSearch').val())">${last}</button>`;
    }

    // Next button
    html += `
        <button class="btn btn-sm btn-outline-primary mx-1"
        ${current === last ? 'disabled' : ''}
        onclick="loadClientCards(${current + 1}, $('#clientSearch').val())">
            Next ›
        </button>
    `;

    $('#paginationLinks').html(`
        <div class="d-flex flex-wrap justify-content-center gap-1">
            ${html}
        </div>
    `);
}
</script>
@endsection