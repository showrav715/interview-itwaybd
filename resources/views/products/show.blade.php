@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>{{ $product->name }}</h1>
        <p><strong>SKU:</strong> {{ $product->sku }}</p>
        <p><strong>Price:</strong> {{ formatCurrency($product->price) }}</p>
        <p><strong>Stock:</strong> {{ $product->stock }}</p>
        <p><strong>Description:</strong> {{ $product->description }}</p>

        <hr>
        <h3>Notes</h3>
        <ul id="notes-list">
            @forelse($product->notes as $note)
                <li>
                    {{ $note->body }}
                    <small class="text-muted">
                        — {{ $note->creator->name ?? 'System' }} ({{ $note->created_at->diffForHumans() }})
                    </small>
                </li>
            @empty
                <li>No notes yet.</li>
            @endforelse
        </ul>

        <button class="btn btn-primary add-note-btn" data-type="product" data-id="{{ $product->id }}">
            Add Note
        </button>
    </div>

    @include('partials.note-modal')
@endsection
