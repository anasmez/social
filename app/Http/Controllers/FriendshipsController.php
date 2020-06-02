<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\User;

class FriendshipsController extends Controller
{
    public function store(User $recipient)
    {
        Friendship::firstOrCreate([
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient->id,
            'status' => 'pending'
        ]);
        return response()->json([
            'friendship_status' => 'pending'
        ]);
    }

    public function destroy(User $recipient)
    {
        Friendship::where([
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient->id,
        ])->delete();

        return response()->json([
            'friendship_status' => 'deleted'
        ]);
    }

}
