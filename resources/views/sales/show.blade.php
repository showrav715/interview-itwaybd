@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Sale #{{ $sale->id }}</h1>
        <p><strong>Customer:</strong> {{ $sale->user->name }}</p>
        <p><strong>Date:</strong> {{ $sale->created_at->format('d M, Y H:i') }}</p>
        <p><strong>Total:</strong> {{ $sale->formatted_total }}</p>

        <hr>
        <h3>Sale Items</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ formatCurrency($item->price) }}</td>
                        <td>{{ $item->discount }}%</td>
                        <td>{{ formatCurrency($item->quantity * $item->price - $item->discount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>
        <h3>Notes</h3>
        <ul id="notes-list">
            @forelse($sale->notes as $note)
                <li>
                    {{ $note->body }}
                    <small class="text-muted">
                        — {{ $note->creator->name ?? 'System' }}
                        ({{ $note->created_at->diffForHumans() }})
                    </small>
                </li>
            @empty
                <li>No notes yet.</li>
            @endforelse
        </ul>

        <button class="btn btn-primary add-note-btn" data-type="sale" data-id="{{ $sale->id }}">
            Add Note
        </button>
    </div>

    @include('partials.note-modal')
@endsection
