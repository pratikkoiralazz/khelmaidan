<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TeamMatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamMatchController extends Controller
{
    public function createMatchChallenge(Request $request, int $bookingId): JsonResponse
    {
        $validated = $request->validate([
            'host_team_name' => 'required|string|max:100',
        ]);

        $booking = Booking::findOrFail($bookingId);

        if ($booking->status !== 'confirmed') {
            return response()->json(['error' => 'Only confirmed bookings can host open match challenges.'], 422);
        }

        $splitFee = (int) ceil($booking->total_amount / 2);

        $match = TeamMatch::create([
            'booking_id' => $booking->id,
            'host_player_id' => auth()->id() ?? $booking->player_id,
            'host_team_name' => $validated['host_team_name'],
            'opponent_status' => 'open',
            'split_fee_per_team' => $splitFee,
        ]);

        return response()->json([
            'message' => 'Open match challenge published to Team Matchmaking Hub.',
            'match' => $match,
        ], 201);
    }

    public function acceptChallenge(Request $request, int $matchId): JsonResponse
    {
        $validated = $request->validate([
            'opponent_team_name' => 'required|string|max:100',
        ]);

        $match = TeamMatch::where('opponent_status', 'open')->findOrFail($matchId);

        $match->update([
            'opponent_player_id' => auth()->id(),
            'opponent_team_name' => $validated['opponent_team_name'],
            'opponent_status' => 'matched',
        ]);

        return response()->json([
            'message' => 'Challenge accepted! Court fees splitted.',
            'match' => $match,
        ]);
    }
}