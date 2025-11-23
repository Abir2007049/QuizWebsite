<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AiConversation;
use App\Models\AiMessage;

class AIController extends Controller
{
    public function chat(Request $request, AIService $ai)
    {
        $request->validate([
            'message' => 'required|string|max:3000',
            'conversation_id' => 'nullable|integer|exists:ai_conversations,id',
            'role' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Basic rate limiting can be applied via throttle middleware on the route

        $message = $request->input('message');
        $role = $request->input('role', 'tutor');

        // Optionally create or reuse a conversation
        $conversation = null;
        if ($request->filled('conversation_id')) {
            $conversation = AiConversation::find($request->conversation_id);
        } else {
            $conversation = AiConversation::create([
                'user_id' => $user ? $user->id : null,
                'title' => substr($message, 0, 80),
            ]);
        }

        // Save user message
        $userMessage = AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $message,
        ]);

        // Call AI
        try {
            $reply = $ai->chat($message, $role);
        } catch (\Throwable $e) {
            \Log::error('AI Chat Error: ' . $e->getMessage(), [
                'user_id' => $user ? $user->id : null,
                'message' => $message,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Failed to get AI response. Please check your API key and try again.'
            ], 500);
        }

        // Save assistant message
        $assistantMessage = AiMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $reply,
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'reply' => $reply,
        ]);
    }
}
