@extends('layouts.app')
@section('title', 'Production Log')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">Add Production Log</div>
        <div class="card-body">
            <form action="{{ route('prdlog.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Work Order</label>
                        <select name="wo_no" class="form-select" required>
                            <option value="">-- Select WO --</option>
                            @foreach($workOrders as $wo)
                                <option value="{{ $wo }}">{{ $wo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Item</label>
                        <select name="itm_cd" class="form-select" required>
                            <option value="">-- Select Item --</option>
                            @foreach($items as $cd => $nm)
                                <option value="{{ $cd }}">{{ $nm }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Process</label>
                        <select name="proc_cd" class="form-select" required>
                            <option value="">-- Select Process --</option>
                            @foreach($processes as $cd => $nm)
                                <option value="{{ $cd }}">{{ $nm }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Machine</label>
                        <select name="mchn_cd" class="form-select">
                            <option value="">-- Select Machine --</option>
                            @foreach($machines as $cd => $nm)
                                <option value="{{ $cd }}">{{ $nm }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Employee</label>
                        <select name="emp_id" class="form-select" required>
                            <option value="">-- Select Operator --</option>
                            @foreach($employees as $id => $nm)
                                <option value="{{ $id }}">{{ $nm }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>In Qty</label>
                        <input type="number" step="0.01" name="in_qty" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>Out Qty</label>
                        <input type="number" step="0.01" name="out_qty" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>NG Qty</label>
                        <input type="number" step="0.01" name="ng_qty" class="form-control" value="0">
                    </div>

                    <div class="col-md-12">
                        <label>Remarks</label>
                        <textarea name="rmks" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-md-12 text-end">
                        <button class="btn btn-success mt-2">Save Log</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Production Logs</div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>WO No</th>
                        <th>Item</th>
                        <th>Process</th>
                        <th>Machine</th>
                        <th>Employee</th>
                        <th>Out Qty</th>
                        <th>NG Qty</th>
                        <th>Start</th>
                        <th>End</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->wo_no }}</td>
                            <td>{{ $log->item->itm_nm ?? '-' }}</td>
                            <td>{{ $log->process->proc_nm ?? '-' }}</td>
                            <td>{{ $log->machine->mchn_nm ?? '-' }}</td>
                            <td>{{ $log->employee->emp_nm ?? '-' }}</td>
                            <td>{{ $log->out_qty }}</td>
                            <td>{{ $log->ng_qty }}</td>
                            <td>{{ $log->start_time }}</td>
                            <td>{{ $log->end_time }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
