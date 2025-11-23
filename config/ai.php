<?php

return [
    // Default provider: groq (free, fast)
    'provider' => env('AI_PROVIDER', 'groq'),

    // OpenAI specific settings
    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'model' => env('AI_OPENAI_MODEL', 'gpt-3.5-turbo'),
        'endpoint' => env('AI_OPENAI_ENDPOINT', 'https://api.openai.com/v1'),
        'max_tokens' => (int) env('AI_MAX_TOKENS', 800),
        'temperature' => (float) env('AI_TEMPERATURE', 0.2),
    ],

    // Groq specific settings (free tier, very fast)
    'groq' => [
        'key' => env('GROQ_API_KEY') ?: config('ai_keys.groq_key'),
        'model' => env('AI_GROQ_MODEL', 'llama-3.3-70b-versatile'),
        'endpoint' => env('AI_GROQ_ENDPOINT', 'https://api.groq.com/openai/v1'),
        'max_tokens' => (int) env('AI_MAX_TOKENS', 800),
        'temperature' => (float) env('AI_TEMPERATURE', 0.2),
    ],

    // Ollama specific settings (local, free)
    'ollama' => [
        'model' => env('AI_OLLAMA_MODEL', 'llama3.2'),
        'endpoint' => env('AI_OLLAMA_ENDPOINT', 'http://localhost:11434'),
    ],

    // system prompt templates
    'prompts' => [
        'tutor' => "You are a helpful tutor for an online quiz platform. Provide concise, step-by-step explanations and show reasoning when asked. Avoid revealing any private data.",
        'question_generator' => "You are an assistant that generates quiz questions. Produce a single question with four options and indicate the correct one in JSON format.",
    ],
];
