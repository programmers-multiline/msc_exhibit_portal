@extends('layouts.app')

@section('content')

<div class="card-box mb-30 p-4 shadow-sm">
    <!-- Main Header -->
    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
        <h4 class="h5 text-dark font-weight-bold mb-0">
            <i class="fas fa-chart-bar text-primary mr-2"></i> Agent Performance & Lead Conversion Report
        </h4>
    </div>

    <!-- Main Overview Table -->
    <div class="table-responsive mb-15 border rounded">
        <table class="table table-hover table-striped text-center align-middle mb-0">
            <thead class="bg-light text-secondary font-weight-bold">
                <tr>
                    <th class="text-left pl-3" style="border-top: 3px solid #007bff;">Agent Name</th>
                    <th style="border-top: 3px solid #6c757d;">Total Assigned</th>
                    <th style="border-top: 3px solid #ffc107;">New Leads</th>
                    <th style="border-top: 3px solid #17a2b8;">Active Leads</th>
                    <th style="border-top: 3px solid #28a745;">Converted</th>
                    <th class="bg-dark text-white">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agentReports as $row)
                <tr>
                    <td class="text-left pl-3 font-weight-bold text-secondary">{{ $row->agent_name }}</td>
                    <td class="text-secondary font-weight-bold">{{ number_format($row->total_assigned) }}</td>
                    <td><span class="badge badge-warning text-dark px-3 py-2" style="font-size: 0.9rem;">{{ number_format($row->total_new_lead) }}</span></td>
                    <td><span class="badge badge-info px-3 py-2" style="font-size: 0.9rem;">{{ number_format($row->total_active_leads) }}</span></td>
                    <td><span class="badge badge-success px-3 py-2" style="font-size: 0.9rem;">{{ number_format($row->total_converted) }}</span></td>
                    <td class="table-dark font-weight-bold">{{ number_format($row->total_amount) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No data available for this report.</td>
                </tr>
                @endforelse
            </tbody>
            @if($agentReports->count() > 0)
            <tfoot class="bg-light font-weight-bold border-top-2">
                <tr>
                    <td class="text-left pl-3 text-dark">GRAND TOTAL</td>
                    <td class="text-dark">{{ number_format($agentReports->sum('total_assigned')) }}</td>
                    <td class="text-warning-dark font-weight-bold">{{ number_format($agentReports->sum('total_new_lead')) }}</td>
                    <td class="text-info font-weight-bold">{{ number_format($agentReports->sum('total_active_leads')) }}</td>
                    <td class="text-success font-weight-bold">{{ number_format($agentReports->sum('total_converted')) }}</td>
                    <td class="bg-dark text-white font-weight-bold">{{ number_format($agentReports->sum('total_amount')) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
