<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Display a list of all messages
    public function index()
    {
        $messages = Message::with(['user', 'category', 'replies'])->get();

        return response()->json($messages);
    }

    // Show a specific message
    public function show($id)
    {
        $message = Message::with(['user', 'category', 'replies'])->findOrFail($id);

        return response()->json($message);
    }

    // Store a new message
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'parent_id' => 'nullable|exists:messages,id',
            'name' => 'required|string|max:255',
            'iconpath' => 'required|string|max:255',
            'chat' => 'required|string',
            'timestamp' => 'required|string',
            'hasUnread' => 'boolean',
            'isMe' => 'boolean',
        ]);

        $message = Message::create($request->all());

        return response()->json([
            'message' => 'Message created successfully',
            'data' => $message,
        ], 201);
    }

    // Update an existing message
    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'iconpath' => 'sometimes|string|max:255',
            'chat' => 'sometimes|string',
            'timestamp' => 'sometimes|string',
            'hasUnread' => 'boolean',
            'isMe' => 'boolean',
        ]);

        $message->update($request->all());

        return response()->json([
            'message' => 'Message updated successfully',
            'data' => $message,
        ]);
    }

    // Delete a message
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return response()->json(['message' => 'Message deleted successfully']);
    }

    // Retrieve replies for a specific message
    public function getReplies($id)
    {
        $message = Message::with('replies')->findOrFail($id);

        return response()->json($message->replies);
    }
}
