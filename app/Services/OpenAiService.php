<?php

namespace App\Services;

use App\Exceptions\GenerateRecipeException;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAiService
{
    public function getRecipe(array $ingredients): array
    {
        return Cache::remember(Arr::join($ingredients, ''), Carbon::SECONDS_PER_MINUTE * 60, function () use ($ingredients) {
            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $this->getPrompt($ingredients),
                'max_tokens' => 500,
                'temperature' => 0.3
            ]);

            if (!$result || empty($result->choices) || !Str::isJson($result->choices[0]->text)) {
                throw new GenerateRecipeException();
            }

            return json_decode($result->choices[0]->text, true);
        });
    }

    private function getPrompt(array $ingredients): string
    {
        return 'Write a recipe based on these ingredients: '.
            Arr::join($ingredients, ', ').
            '. Only use metric units. Provide a response in JSON with the following format: '.
            json_encode([
                'name' => 'string',
                'ingredients' => [
                    'string',
                    'string',
                ],
                'instructions' => [
                    'string',
                    'string',
                ],
            ]);
    }
}
