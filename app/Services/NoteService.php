<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

class NoteService
{
    public static function add(Model $notable, string $body, ?int $userId = null): Note
    {
        return $notable->notes()->create([
            'body' => ucfirst(trim($body)),
            'created_by' => $userId,
        ]);
    }
}
