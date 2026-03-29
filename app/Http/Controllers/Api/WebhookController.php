<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleIncoming(Request $request)
    {
        try {
            $data = $request->validate([
                'project' => 'required|string',
                'platform' => 'required|string',
                'provider_id' => 'required|string',
                'external_contact_id' => 'required|string',
                'contact_name' => 'required|string',
                'message_body' => 'nullable|string',
                'message_type' => 'nullable|string',
            ]);

            Log::info('Webhook received', $data);

            // Find valid configured channel
            $channel = Channel::where('project', $data['project'])
                ->where('platform', $data['platform'])
                ->where('provider_id', $data['provider_id'])
                ->first();

            if (!$channel) {
                return response()->json(['error' => 'Channel not found'], 404);
            }

            // Find or create conversation
            $conversation = Conversation::firstOrCreate(
                [
                    'channel_id' => $channel->id,
                    'external_contact_id' => $data['external_contact_id']
                ],
                [
                    'contact_name' => $data['contact_name'],
                ]
            );

            // Update conversational state
            $conversation->update([
                'contact_name' => $data['contact_name'],
                'last_message' => $data['message_body']
            ]);

            // Save the message
            $message = $conversation->messages()->create([
                'body' => $data['message_body'],
                'type' => $data['message_type'] ?? 'text',
                'direction' => 'inbound'
            ]);

            return response()->json(['status' => 'success', 'message_id' => $message->id]);

        } catch (\Exception $e) {
            Log::error('Webhook Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
