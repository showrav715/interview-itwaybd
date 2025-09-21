<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleStoreRequest;
use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {

        $query = Sale::with(['user', 'items.product', 'latestNote']);

        if ($request->filled('customer_name')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "{$request->customer_name}%"));
        }

        if ($request->filled('product_name')) {
            $query->whereHas('items.product', fn($q) => $q->where('name', 'like', "{$request->product_name}%"));
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('sale_date', [$request->date_from, $request->date_to]);
        }

        $sales = $query->orderBy('sale_date', 'desc')->paginate(15)->withQueryString();

        // DB sum instead of collection sum (more efficient)
        $pageTotal = $query->sum('total');

        $customers = User::orderBy('name')->limit(50)->get();
        return view('sales.index', compact('sales', 'pageTotal', 'customers'));
    }

    public function create()
    {
        $customers = User::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('sales.create', compact('customers', 'products'));
    }

    public function store(SaleStoreRequest $request)
    {
        $validated = $request->validated();
        $saleData = [
            'user_id' => $validated['user_id'],
            'sale_date' => $validated['sale_date'],
            'notes' => $validated['notes'] ?? null,
        ];
        $items = $validated['items'];

        $sale = SaleService::createSale(saleData: $saleData, itemsData: $items);

        return response()->json([
            'success' => true,
            'message' => 'Sale created.',
            'sale_id' => $sale->id,
            'formatted_total' => $sale->formatted_total,
        ]);
    }

    public function show($id)
    {
        $sale = Sale::with(['user', 'items.product', 'notes.creator'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('items.product'); // eager load line items
        $customers = User::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('sales.edit', compact('sale', 'customers', 'products'));
    }




    public function update(SaleStoreRequest $request, Sale $sale)
    {
        $validated = $request->validated();
        $saleData = [
            'user_id' => $validated['user_id'],
            'sale_date' => $validated['sale_date'],
            'notes' => $validated['notes'] ?? null,
        ];
        $items = $validated['items'];

        $sale = SaleService::updateSale(sale: $sale, saleData: $saleData, itemsData: $items);

        return response()->json([
            'success' => true,
            'message' => 'Sale updated.',
            'sale_id' => $sale->id,
            'formatted_total' => $sale->formatted_total,
        ]);
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale moved to trash.');
    }
    public function trash()
    {
        $trashed = Sale::onlyTrashed()->with('user')->paginate(15);
        return view('sales.trash', compact('trashed'));
    }

    public function restore($id)
    {
        $sale = Sale::onlyTrashed()->where('id', $id)->firstOrFail();
        $sale->restore();
        $sale->items()->withTrashed()->get()->each->restore();
        return redirect()->route('sales.trash')->with('success', 'Sale restored.');
    }
}
