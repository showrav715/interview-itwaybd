@extends('layouts.app')
@section('content')
    <h1>Sales</h1>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-2">
            <input name="customer_name" class="form-control" placeholder="Customer name" value="{{ request('customer_name') }}">
        </div>
        <div class="col-md-2">
            <input name="product_name" class="form-control" placeholder="Product name in items"
                value="{{ request('product_name') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-4">

            <button class="btn btn-primary">
                <i class="fas fa-filter"></i>
                Filter</button>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Reset</a>
            <a href="{{ route('sales.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i>
                New Sale</a>
            <a href="{{ route('sales.trash') }}" class="btn btn-secondary">
                <i class="fas fa-trash"></i>
                Trash</a>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Items</th>
                <th width="15%">Last Note</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>{{ $sale->sale_date->format('Y-m-d H:i') }}</td>
                    <td>{{ number_format($sale->total, 2) }} BDT</td>
                    <td>
                        <ul class="mb-0">
                            @foreach ($sale->items as $it)
                                <li><i>{{ $it->product->name ?? '—' }}</i>  x{{ $it->quantity }} =
                                    {{ number_format($it->total, 2) }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $sale->latestNote->body ?? '—' }}</td>
                    <td>
                        <form onsubmit="return confirm('Move to trash?')" style="display:inline" method="POST"
                            action="{{ route('sales.destroy', $sale) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-warning btn-sm">
                                Trash</button>
                        </form>

                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-info btn-sm">View</a>
                        <button type="button" class="btn btn-secondary btn-sm add-note-btn" data-type="sale"
                            data-id="{{ $sale->id }}">
                            Add Note
                        </button>

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        <strong>Page total:</strong> {{ number_format($pageTotal, 2) }} BDT
    </div>

    {{ $sales->links() }}
    @include('partials.note-modal')
@endsection
