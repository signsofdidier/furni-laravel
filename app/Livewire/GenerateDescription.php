<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class GenerateDescription extends Component
{
    // De titel van het product of item waar we een beschrijving voor willen genereren
    public $title;

    // Hier komt de gegenereerde omschrijving terecht
    public $description;

    // Functie om een AI-gegenereerde beschrijving op te halen
    public function generate()
    {
        // Stuur een POST request naar Groq API (met Llama 3 model)
        $response = Http::withToken(env('GROQ_API_KEY'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama3-8b-8192',  // AI model (kan wijzigen in .env)
                'messages' => [
                    // Systeem prompt: zeg dat de AI een e-commerce copywriter is
                    ['role' => 'system', 'content' => 'You are a copywriter specialized in e-commerce.'],
                    // User prompt: geef titel mee (dus: waar moet de omschrijving over gaan?)
                    ['role' => 'user', 'content' => "Write a catchy description for: {$this->title}"],
                ],
            ]);

        // Haal de tekst op uit het antwoord (of toon foutmelding als het misgaat)
        $this->description = $response['choices'][0]['message']['content'] ?? 'No description generated.';
    }

    public function render()
    {
        return view('livewire.generate-description');
    }
}
