<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function tour()
    {
        return view('onboarding.tour');
    }

    public function advertiserTour()
    {
        return view('onboarding.advertiser-tour');
    }

    public function adminTour()
    {
        return view('onboarding.admin-tour');
    }

    public function completeTour(Request $request)
    {
        $tourType = $request->input('tour_type', 'initial');

        if (auth()->check()) {
            $user = auth()->user();
            $completedTours = $user->completed_tours ?? [];

            // Check if this tour type is already completed
            $alreadyCompleted = collect($completedTours)->contains('tour_type', $tourType);

            if (!$alreadyCompleted) {
                $completedTours[] = [
                    'tour_type' => $tourType,
                    'completed_at' => now()->toDateTimeString()
                ];

                $updateData = ['completed_tours' => $completedTours];

                // Mark onboarding as completed if it's the initial tour
                if ($tourType === 'initial') {
                    $updateData['onboarding_completed'] = true;
                }

                $user->update($updateData);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tour completed! Welcome to ' . config('app.name') . '!'
        ]);
    }

    public function hasTourCompleted($tourType)
    {
        if (!auth()->check()) {
            return response()->json(['completed' => false]);
        }

        $completedTours = auth()->user()->completed_tours ?? [];
        $completed = collect($completedTours)->contains('tour_type', $tourType);

        return response()->json(['completed' => $completed]);
    }
}
