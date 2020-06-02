<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\User;

class FriendshipsController extends Controller
{
    public function store(User $recipient)
    {
        $friendship = Friendship::firstOrCreate([
            'sender_id' => auth()->id(),
            'recipient_id' => $recipient->id,
            'status' => 'pending'
        ]);

        return response()->json([
            'friendship_status' => $friendship->fresh()->status
        ]);
    }

    public function destroy(User $user)
    {
        $friendship = Friendship::where([
            'sender_id' => auth()->id(),
            'recipient_id' => $user->id,
        ])->orWhere([
            'sender_id' => $user->id,
            'recipient_id' => auth()->id(),
        ])->first();

        if ($friendship->status === 'denied') {
            return response()->json([
                'friendship_status' => 'denied'
            ]);
        }
        return response()->json([
            'friendship_status' => $friendship->delete() ? 'deleted' : ''
        ]);
    }

}
