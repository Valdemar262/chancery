<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

function getSuccessResponse(
    mixed $data = ['success' => true],
): JsonResponse {
    return Response::json($data);
}
