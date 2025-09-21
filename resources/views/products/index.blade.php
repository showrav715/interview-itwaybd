@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-success mb-3">+ New Product</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Last Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->sku }}</td>
                        <td>{{ formatCurrency($p->price) }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>{{ $p->latestNote->body ?? '—' }}</td>
                        <td>
                            <a href="{{ route('products.show', $p) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('products.edit', $p) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('products.destroy', $p) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Delete this product?')"
                                    class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <button type="button" class="btn btn-secondary btn-sm add-note-btn" data-type="product"
                                data-id="{{ $p->id }}">
                                Add Note
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->links() }}
    </div>

    @include('partials.note-modal')
@endsection
