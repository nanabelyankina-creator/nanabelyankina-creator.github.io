<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            abort(403, 'Только клиенты могут использовать чат.');
        }

        $thread = ChatThread::firstOrCreate(
            ['patient_id' => $patient->id],
            ['admin_id' => null]
        );

        $messages = $thread->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('client.chat.index', [
            'thread'   => $thread,
            'messages' => $messages,
            'user'     => $user,
        ]);
    }

    public function send(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            abort(403, 'Только клиенты могут использовать чат.');
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $thread = ChatThread::firstOrCreate(
            ['patient_id' => $patient->id],
            ['admin_id' => null]
        );

        ChatMessage::create([
            'thread_id' => $thread->id,
            'sender_id' => $user->id,
            'message'   => $data['message'],
        ]);

        return redirect()->route('client.chat.index');
    }
}