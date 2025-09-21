@extends('layouts.app')

@section('content')
    <h1>Edit Sale #{{ $sale->id }}</h1>

    <form id="sale-form">
        <div class="mb-3">
            <label>Customer</label>
            <select name="user_id" id="user_id" class="form-control">
                <option value="">-- select customer --</option>
                @foreach ($customers as $c)
                    <option value="{{ $c->id }}" {{ $sale->user_id == $c->id ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Sale Date</label>
            <input type="datetime-local" name="sale_date" id="sale_date" class="form-control"
                value="{{ $sale->sale_date->format('Y-m-d\TH:i') }}" required>
        </div>

        <h4>Items</h4>
        <table class="table table-sm" id="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Line Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <button type="button" id="add-row" class="btn btn-secondary btn-sm">Add Item</button>

        <div class="mt-3">
            <h5>Grand Total: <span id="grand-total">0.00 BDT</span></h5>
        </div>

        <button type="submit" class="btn btn-primary" id="submit-btn">Update Sale</button>
    </form>
@endsection

@push('scripts')
    <script>
        const products = @json($products);
        const saleItems = @json($sale->items);
        const productPriceUrlBase = "{{ url('products') }}/";

        let rowIndex = 0;

        function rowHtml(index, data = {}) {
            let productOptions = '<option value="">--select--</option>';
            products.forEach(p => productOptions += `<option value="${p.id}" data-price="${p.price}">${p.name}</option>`);
            return `
<tr data-index="${index}">
    <td>
        <select class="form-control product-select" name="items[${index}][product_id]">${productOptions}</select>
    </td>
    <td><input type="number" min="1" value="${data.quantity || 1}" class="form-control qty" name="items[${index}][quantity]"></td>
    <td><input type="number" step="0.01" value="${data.price || 0.00}" class="form-control price" name="items[${index}][price]"></td>
    <td><input type="number" step="0.01" value="${data.discount || 0.00}" class="form-control discount" name="items[${index}][discount]"></td>
    <td class="line-total">0.00</td>
    <td><button type="button" class="btn btn-danger btn-sm remove-row">x</button></td>
</tr>`;
        }

        function addRow(data = {}) {
            $('#items-table tbody').append(rowHtml(rowIndex, data));
            let $tr = $('#items-table tbody tr').last();
            if (data.product_id) $tr.find('.product-select').val(data.product_id);
            rowIndex++;
            recalcAll();
        }

        function recalcAll() {
            let grand = 0;
            $('#items-table tbody tr').each(function() {
                let qty = Number($(this).find('.qty').val() || 0);
                let price = Number($(this).find('.price').val() || 0);
                let discount = Number($(this).find('.discount').val() || 0);
                let line = (qty * price) - discount;
                if (line < 0) line = 0;
                $(this).find('.line-total').text(line.toFixed(2));
                grand += line;
            });
            $('#grand-total').text(grand.toFixed(2) + ' BDT');
        }

        $(document).ready(function() {
            // load existing items
            saleItems.forEach(item => addRow(item));

            $('#add-row').click(() => addRow());

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                recalcAll();
            });

            $(document).on('change', '.product-select', function() {
                let $tr = $(this).closest('tr');
                let productId = $(this).val();
                if (!productId) {
                    $tr.find('.price').val('0.00');
                    recalcAll();
                    return;
                }
                $.get(`${productPriceUrlBase}${productId}/price`)
                    .done(res => {
                        $tr.find('.price').val(Number(res.price).toFixed(2));
                        recalcAll();
                    })
                    .fail(() => alert('Error fetching price'));
            });

            $(document).on('input', '.qty, .price, .discount', recalcAll);

            $('#sale-form').on('submit', function(e) {
                e.preventDefault();
                let data = {
                    user_id: $('#user_id').val(),
                    sale_date: $('#sale_date').val(),
                    items: []
                };
                $('#items-table tbody tr').each(function() {
                    let product_id = $(this).find('.product-select').val();
                    if (!product_id) return;
                    data.items.push({
                        id: $(this).data('id') || null,
                        product_id,
                        quantity: Number($(this).find('.qty').val()),
                        price: Number($(this).find('.price').val()),
                        discount: Number($(this).find('.discount').val())
                    });
                });
                if (data.items.length === 0) {
                    alert('Add at least one item');
                    return;
                }
                $('#submit-btn').prop('disabled', true);
                $.ajax({
                    method: 'PUT',
                    url: "{{ route('sales.update', $sale) }}",
                    data: data,
                    success(res) {
                        alert('Sale updated: total = ' + res.formatted_total);
                        window.location.href = "{{ route('sales.index') }}";
                    },
                    error(xhr) {
                        alert('Error: ' + (xhr.responseJSON?.message || 'Validation error'));
                        $('#submit-btn').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
