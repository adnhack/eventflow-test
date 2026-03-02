<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnrichmentController extends Controller
{
    public function getData(Request $request)
    {
        $data = ['enriched_info' => 'This is some enriched data based on the event.'];

        // Return a response with the enriched data
        return response()->json([
            'message' => 'Data enriched successfully',
            'enriched_data' => $data,
        ], 200);
    }
}
