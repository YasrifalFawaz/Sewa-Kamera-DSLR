<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoneyDetectionController extends Controller
{
    public function detect(Request $request)
    {
        try {

            $response = Http::timeout(60)->post(
                'http://127.0.0.1:5000/detect',
                [
                    'image' => $request->image
                ]
            );

            return response()->json($response->json());

        } catch (\Exception $e) {

            return response()->json([
                'valid' => false,
                'message' => 'Python AI Server tidak aktif'
            ]);
        }
    }
}