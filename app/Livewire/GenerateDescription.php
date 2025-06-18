<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class GenerateDescription extends Component
{
    public $title;
    public $description;

    public function generate()
    {
        $response = Http::withToken(env('GROQ_API_KEY'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama3-8b-8192',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a copywriter specialized in e-commerce.'],
                    ['role' => 'user', 'content' => "Write a catchy description for: {$this->title}"],
                ],
            ]);

        $this->description = $response['choices'][0]['message']['content'] ?? 'No description generated.';
    }

    public function render()
    {
        return view('livewire.generate-description');
    }
}
