<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

class GenerateRecipeException extends \Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Something when wrong when contacting the OpenAI API.'
        ]);
    }
}
