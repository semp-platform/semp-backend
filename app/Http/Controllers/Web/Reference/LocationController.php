<?php

namespace App\Http\Controllers\Web\Reference;

use App\Http\Controllers\Controller;
use App\Models\Reference\Lga;
use App\Models\Reference\Ward;
use App\Models\Reference\Lcda;
use Illuminate\Http\JsonResponse;
use App\Models\Reference\LcdaWard;


class LocationController extends Controller
{


public function lcdaWards(int $lcda): JsonResponse
{
    return response()->json(
        LcdaWard::query()
            ->where('lcda_id', $lcda)
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ])
    );
}
    public function lgas(int $state): JsonResponse
    {
        return response()->json(
            Lga::query()
                ->where('state_id', $state)
                ->where('is_active', true)
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                ])
        );
    }

    public function wards(int $lga): JsonResponse
    {
        return response()->json(
            Ward::query()
                ->where('lga_id', $lga)
                ->where('is_active', true)
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                ])
        );
    }

    public function lcdas(int $lga): JsonResponse
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
