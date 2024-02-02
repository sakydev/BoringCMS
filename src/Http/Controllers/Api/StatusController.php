<?php

namespace Sakydev\Boring\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class StatusController extends Controller
{
    public function status(string $item)
    {
        return response()->json([
            'item' => $item,
            'message' => 'ok',
            'time' => time(),
        ]);
    }
}
