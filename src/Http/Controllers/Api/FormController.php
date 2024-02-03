<?php

namespace Sakydev\Boring\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Sakydev\Boring\Models\Form;
use Symfony\Component\HttpFoundation\Response;

class FormController extends Controller
{
    public function index(): JsonResponse {
        $forms = new Form();
        $results = $forms->all();

        return new JsonResponse($results, Response::HTTP_OK);
    }
}
