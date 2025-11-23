<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AIService
{
    protected string $provider;

    public function __construct()
    {
        $this->provider = config('ai.provider', 'openai');
        \Log::info('AIService initialized', [
            'provider' => $this->provider,
            'env_AI_PROVIDER' => env('AI_PROVIDER'),
            'config_ai_provider' => config('ai.provider'),
        ]);
    }

    /**
     * Send a chat request to the configured AI provider and return the assistant reply.
     */
    public function chat(string $userMessage, string $role = 'tutor'): string
    {
        if ($this->provider === 'openai') {
            return $this->chatOpenAI($userMessage, $role);
        }

        if ($this->provider === 'groq') {
            return $this->chatGroq($userMessage, $role);
        }

        if ($this->provider === 'ollama') {
            return $this->chatOllama($userMessage, $role);
        }

        // Other providers can be added here
        throw new \RuntimeException('AI provider not supported: '.$this->provider);
    }

    protected function chatOpenAI(string $userMessage, string $role): string
    {
        $endpoint = rtrim(config('ai.openai.endpoint', 'https://api.openai.com/v1'), '/');
        $model = config('ai.openai.model', 'gpt-3.5-turbo');
        $maxTokens = (int) config('ai.openai.max_tokens', 800);
        $temperature = (float) config('ai.openai.temperature', 0.2);

        $system = config("ai.prompts.{$role}", config('ai.prompts.tutor'));

        $apiKey = config('ai.openai.key');
        if (empty($apiKey)) {
            throw new \RuntimeException('OpenAI API key not configured. Set OPENAI_API_KEY in your .env');
        }

        $resp = Http::withToken($apiKey)
            ->post("{$endpoint}/chat/completions", [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
            ]);

        if ($resp->failed()) {
            $body = $resp->body();
            throw new \RuntimeException('AI API error: '.$body);
        }

        $json = $resp->json();

        // Navigate the response to get the assistant's content
        $content = '';
        if (isset($json['choices'][0]['message']['content'])) {
            $content = $json['choices'][0]['message']['content'];
        } elseif (isset($json['choices'][0]['text'])) {
            $content = $json['choices'][0]['text'];
        }

        return trim($content);
    }

    protected function chatGroq(string $userMessage, string $role): string
    {
        // Try to get key from env first, then from ai_keys.php config file
        $apiKey = env('GROQ_API_KEY');
        if (empty($apiKey)) {
            $keysConfig = config('ai_keys');
            $apiKey = $keysConfig['groq_key'] ?? null;
        }
        
        if (empty($apiKey)) {
            throw new \RuntimeException('Groq API key not configured. Set GROQ_API_KEY in your .env or config/ai_keys.php');
        }

        $endpoint = config('ai.groq.endpoint', 'https://api.groq.com/openai/v1');
        $model = config('ai.groq.model', 'llama-3.3-70b-versatile');
        $maxTokens = (int) config('ai.groq.max_tokens', 800);
        $temperature = (float) config('ai.groq.temperature', 0.2);
        $system = config("ai.prompts.{$role}", config('ai.prompts.tutor'));

        $resp = Http::withToken($apiKey)
            ->timeout(30)
            ->post("{$endpoint}/chat/completions", [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
            ]);

        if ($resp->failed()) {
            $body = $resp->body();
            throw new \RuntimeException('Groq API error: '.$body);
        }

        $json = $resp->json();
        $content = $json['choices'][0]['message']['content'] ?? '';

        return trim($content);
    }

    protected function chatOllama(string $userMessage, string $role): string
    {
        $endpoint = config('ai.ollama.endpoint', 'http://localhost:11434');
        $model = config('ai.ollama.model', 'llama3.2');
        $system = config("ai.prompts.{$role}", config('ai.prompts.tutor'));

        $resp = Http::timeout(60)->post("{$endpoint}/api/chat", [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'stream' => false,
        ]);

        if ($resp->failed()) {
            $body = $resp->body();
            throw new \RuntimeException('Ollama API error: '.$body);
        }

        $json = $resp->json();
        $content = $json['message']['content'] ?? '';

        return trim($content);
    }
}
