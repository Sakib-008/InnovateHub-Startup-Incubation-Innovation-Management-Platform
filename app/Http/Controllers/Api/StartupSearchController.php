<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StartupIdea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StartupSearchController extends Controller
{
    /**
     * GET /api/startups/search?q=fintech&category=FinTech
     * Live AJAX search used by the investor browse page.
     */
    public function search(Request $request): JsonResponse
    {
        $query = StartupIdea::approved()
            ->has('showcase')
            ->with(['founder:id,name,avatar', 'showcase:id,startup_idea_id,tagline,gallery_images'])
            ->withCount('investmentInterests');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $startups = $query->limit(12)->get()->map(function ($idea) {
            // Determine whether current investor has expressed interest
            $hasInterest = auth()->check()
                ? $idea->investmentInterests()
                        ->where('investor_id', auth()->id())
                        ->exists()
                : false;

            return [
                'id'               => $idea->id,
                'title'            => $idea->title,
                'category'         => $idea->category,
                'description'      => \Str::limit($idea->description, 100),
                'founder_name'     => $idea->founder->name,
                'tagline'          => $idea->showcase->tagline,
                'interest_count'   => $idea->investment_interests_count,
                'has_interest'     => $hasInterest,
                'thumbnail'        => ! empty($idea->showcase->gallery_images)
                                        ? asset('storage/' . $idea->showcase->gallery_images[0])
                                        : null,
                'url'              => route('investor.startup.show', $idea),
            ];
        });

        return response()->json([
            'success' => true,
            'count'   => $startups->count(),
            'data'    => $startups,
        ]);
    }
}