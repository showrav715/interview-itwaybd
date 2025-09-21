<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteStoreRequest;
use App\Services\NoteService;
use App\Models\Sale;
use App\Models\Product;

class NoteController extends Controller
{
    public function store(NoteStoreRequest $request)
    {
        $data = $request->validated();

        $map = [
            'sale' => Sale::class,
            'product' => Product::class,
        ];

        $modelClass = $map[$data['notable_type']];
        $notable = $modelClass::findOrFail($data['notable_id']);

        $note = NoteService::add($notable, $data['body'], auth()->id());

        return response()->json([
            'success' => true,
            'note' => [
                'id' => $note->id,
                'body' => $note->body,
                'created_at' => $note->created_at->diffForHumans(),
            ]
        ]);
    }
}
