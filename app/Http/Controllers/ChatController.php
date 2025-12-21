<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = $request->message;

        $systemContext = <<<EOT
            You are an AI assistant for a multi-branch Inventory Management System (IMS).

            Rules:
            1. Always answer questions related to this system: branches, products, categories, promotions, users, purchase orders, and Telegram alerts.
            2. If the user says something friendly (e.g., "hello", "hi", "good morning", "how are you?"), respond in a friendly manner.
            3. If the user asks something unrelated to the IMS, reply politely: "I can only answer questions about the Inventory Management System."
            4. Keep your tone professional yet friendly.
        EOT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $systemContext],
                ['role' => 'user', 'content' => $message],
            ],
        ]);

        $result = $response->json();

        $reply = 'No reply';
        if (isset($result['choices'][0]['message']['content'])) {
            $reply = $result['choices'][0]['message']['content'];
        } elseif (isset($result['error']['message'])) {
            $reply = "Error from API: " . $result['error']['message'];
        }

        return response()->json([
            'reply' => $reply,
        ]);
    }
}
