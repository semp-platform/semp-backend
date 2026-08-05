<?php

namespace App\Http\Controllers\Api\Reference;

use App\Http\Controllers\Controller;
use App\Models\Reference\Lcda;
use Illuminate\Http\JsonResponse;

class LcdaController extends Controller
{
    public function byLga(int $lga): JsonResponse
    {
        return response()->json(
            Lcda::query()
                ->where('lga_id', $lga)
                ->where('is_active', true)
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                ])
        );
    }
}
