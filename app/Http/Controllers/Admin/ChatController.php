<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $threads = ChatThread::with(['patient', 'patient.user'])
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.chat.index', compact('threads'));
    }

    public function show(ChatThread $thread)
    {
        $threads = ChatThread::with(['patient', 'patient.user'])
            ->orderByDesc('updated_at')
            ->get();

        $messages = $thread->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('admin.chat.show', [
            'threads'  => $threads,
            'thread'   => $thread,
            'messages' => $messages,
            'user'     => Auth::user(),
        ]);
    }

    public function send(Request $request, ChatThread $thread)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Только администратор может отвечать в чат.');
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        if (!$thread->admin_id) {
            $thread->admin_id = $user->id;
            $thread->save();
        }

        ChatMessage::create([
            'thread_id' => $thread->id,
            'sender_id' => $user->id,
            'message'   => $data['message'],
        ]);

        return redirect()->route('admin.chat.show', $thread);
    }
}