<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Snippet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TelexWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log incoming request
        Log::info('Telex Webhook Received', $request->all());

        $message = $request->input('message', '');
        $userId = $request->input('user_id', 'anonymous');
        $channelId = $request->input('channel_id', env('TELEX_CHANNEL_ID'));

        // Parse command
        $response = $this->processCommand($message, $userId, $channelId);

        // Send response back to Telex
        $this->sendToTelex($channelId, $response);

        return response()->json(['status' => 'success']);
    }

    private function processCommand($message, $userId, $channelId)
    {
        $message = trim($message);

        // Help command
        if (stripos($message, '/help') === 0) {
            return $this->helpCommand();
        }

        // Save snippet: /save php echo "Hello";
        if (stripos($message, '/save') === 0) {
            return $this->saveSnippet($message, $userId, $channelId);
        }

        // Get snippets: /get php
        if (stripos($message, '/get') === 0) {
            return $this->getSnippets($message, $userId);
        }

        // List all snippets
        if (stripos($message, '/list') === 0) {
            return $this->listSnippets($userId);
        }

        // Delete snippet: /delete 1
        if (stripos($message, '/delete') === 0) {
            return $this->deleteSnippet($message, $userId);
        }

        // Default response
        return "👋 Hi! I'm your Code Snippet Manager.\n\nType `/help` to see what I can do!";
    }

    private function helpCommand()
    {
        return "🤖 **Code Snippet Manager Bot**\n\n" .
               "**Commands:**\n" .
               "`/save <language> <code>` - Save a code snippet\n" .
               "`/get <language>` - Get snippets by language\n" .
               "`/list` - List all your snippets\n" .
               "`/delete <id>` - Delete a snippet\n" .
               "`/help` - Show this help message\n\n" .
               "**Example:**\n" .
               "`/save php echo 'Hello World';`";
    }

    private function saveSnippet($message, $userId, $channelId)
    {
        // Parse: /save php echo "Hello";
        $parts = explode(' ', $message, 3);
        
        if (count($parts) < 3) {
            return "❌ Invalid format. Use: `/save <language> <code>`";
        }

        $language = strtolower($parts[1]);
        $code = $parts[2];

        $snippet = Snippet::create([
            'user_id' => $userId,
            'language' => $language,
            'code' => $code,
            'channel_id' => $channelId
        ]);

        return "✅ Snippet saved! (ID: {$snippet->id})\n\n" .
               "**Language:** {$language}\n" .
               "**Code:**\n```{$language}\n{$code}\n```";
    }

    private function getSnippets($message, $userId)
    {
        // Parse: /get php
        $parts = explode(' ', $message, 2);
        
        if (count($parts) < 2) {
            return "❌ Please specify a language. Use: `/get <language>`";
        }

        $language = strtolower($parts[1]);
        
        $snippets = Snippet::where('user_id', $userId)
                          ->where('language', $language)
                          ->get();

        if ($snippets->isEmpty()) {
            return "📭 No {$language} snippets found.";
        }

        $response = "📚 **Your {$language} Snippets:**\n\n";
        
        foreach ($snippets as $snippet) {
            $response .= "**ID:** {$snippet->id}\n";
            $response .= "```{$language}\n{$snippet->code}\n```\n\n";
        }

        return $response;
    }

    private function listSnippets($userId)
    {
        $snippets = Snippet::where('user_id', $userId)->get();

        if ($snippets->isEmpty()) {
            return "📭 You haven't saved any snippets yet.\n\nUse `/save <language> <code>` to get started!";
        }

        $grouped = $snippets->groupBy('language');
        
        $response = "📚 **Your Snippet Library:**\n\n";
        
        foreach ($grouped as $language => $items) {
            $response .= "**{$language}:** {$items->count()} snippet(s)\n";
        }

        $response .= "\nUse `/get <language>` to view snippets.";

        return $response;
    }

    private function deleteSnippet($message, $userId)
    {
        // Parse: /delete 1
        $parts = explode(' ', $message, 2);
        
        if (count($parts) < 2) {
            return "❌ Please specify a snippet ID. Use: `/delete <id>`";
        }

        $id = (int)$parts[1];
        
        $snippet = Snippet::where('id', $id)
                         ->where('user_id', $userId)
                         ->first();

        if (!$snippet) {
            return "❌ Snippet not found or you don't have permission to delete it.";
        }

        $snippet->delete();

        return "✅ Snippet {$id} deleted successfully!";
    }

    private function sendToTelex($channelId, $message)
    {
        try {
            Http::post(env('TELEX_API_URL') . '/send', [
                'channel_id' => $channelId,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send to Telex', ['error' => $e->getMessage()]);
        }
    }
}