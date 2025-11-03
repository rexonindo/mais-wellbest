@extends('layouts.app')

@section('title', 'Production Actual Input')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Add Production Input
        </div>
        <div class="card-body">
            <form action="{{ route('production.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Process Name</label>
                        <input type="text" name="process_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Machine Code</label>
                        <input type="text" name="machine_code" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Product Code</label>
                        <input type="text" name="product_code" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Target Qty</label>
                        <input type="number" name="target_qty" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Actual Qty</label>
                        <input type="number" name="actual_qty" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Production Date</label>
                        <input type="date" name="production_date" class="form-control" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-success w-100">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            Production Records
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Process</th>
                        <th>Machine</th>
                        <th>Product</th>
                        <th>Target</th>
                        <th>Actual</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inputs as $input)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $input->process_name }}</td>
                            <td>{{ $input->machine_code }}</td>
                            <td>{{ $input->product_code }}</td>
                            <td>{{ $input->target_qty }}</td>
                            <td>{{ $input->actual_qty }}</td>
                            <td>{{ $input->production_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No data yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
