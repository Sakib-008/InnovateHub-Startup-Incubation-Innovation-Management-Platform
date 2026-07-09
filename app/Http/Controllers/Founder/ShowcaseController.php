<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Founder\StoreShowcaseRequest;
use App\Models\InvestmentInterest;
use App\Models\StartupIdea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ShowcaseController extends Controller
{
    public function edit(StartupIdea $idea): View
    {
        abort_if($idea->founder_id !== auth()->id(), 403);
        abort_if(! $idea->isApproved(), 403, 'Only approved ideas can be showcased.');

        $showcase = $idea->showcase ?? new \App\Models\StartupShowcase();
        $interests = InvestmentInterest::where('startup_idea_id', $idea->id)
            ->with('investor.investorProfile')
            ->latest()
            ->get();

        return view('founder.showcase.edit', compact('idea', 'showcase', 'interests'));
    }

    public function update(StoreShowcaseRequest $request, StartupIdea $idea): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        $data = $request->validated();

        // Handle gallery images
        $existingImages = $idea->showcase?->gallery_images ?? [];

        if ($request->hasFile('gallery_images')) {
            $newImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $newImages[] = $image->store('showcase-gallery', 'public');
            }
            $data['gallery_images'] = array_merge($existingImages, $newImages);
        } else {
            $data['gallery_images'] = $existingImages;
        }

        $data['is_public'] = $request->boolean('is_public', true);

        $idea->showcase()->updateOrCreate(
            ['startup_idea_id' => $idea->id],
            $data
        );

        return back()->with('success', 'Showcase updated successfully.');
    }

    public function deleteImage(StartupIdea $idea, int $index): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        $showcase = $idea->showcase;
        $images   = $showcase->gallery_images ?? [];

        if (isset($images[$index])) {
            Storage::disk('public')->delete($images[$index]);
            array_splice($images, $index, 1);
            $showcase->update(['gallery_images' => $images]);
        }

        return back()->with('success', 'Image removed.');
    }

    public function updateInterestStatus(StartupIdea $idea, InvestmentInterest $interest): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        request()->validate(['status' => 'required|in:pending,contacted,declined']);

        $interest->update(['status' => request('status')]);

        return back()->with('success', 'Interest status updated.');
    }
}