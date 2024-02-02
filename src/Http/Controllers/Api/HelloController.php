<?php

namespace Sakydev\BoringCms\Src\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class HelloController extends Controller
{
    public function hello($message)
    {
        return response()->json(['message' => "Hello oye $message"]);
    }
}
