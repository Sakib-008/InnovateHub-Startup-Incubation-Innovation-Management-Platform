<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Founder\StoreTaskRequest;
use App\Http\Requests\Founder\UpdateTaskRequest;
use App\Models\Milestone;
use App\Models\StartupIdea;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request, StartupIdea $idea, Milestone $milestone): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        $milestone->tasks()->create($request->validated());

        return back()->with('success', 'Task added.');
    }

    public function update(UpdateTaskRequest $request, StartupIdea $idea, Milestone $milestone, Task $task): RedirectResponse
    {
        // Team member OR founder can update task status
        $teamUserIds = $idea->team?->members->pluck('user_id')->toArray() ?? [];
        abort_if(
            $idea->founder_id !== auth()->id() && ! in_array(auth()->id(), $teamUserIds),
            403
        );

        $task->update(['status' => $request->status]);

        // Auto-update milestone status based on tasks
        $this->syncMilestoneStatus($milestone);

        return back()->with('success', 'Task status updated.');
    }

    public function destroy(StartupIdea $idea, Milestone $milestone, Task $task): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        $task->delete();
        $this->syncMilestoneStatus($milestone);

        return back()->with('success', 'Task deleted.');
    }

    private function syncMilestoneStatus(Milestone $milestone): void
    {
        $total    = $milestone->tasks()->count();
        $done     = $milestone->tasks()->where('status', 'done')->count();
        $inProg   = $milestone->tasks()->where('status', 'in_progress')->count();

        $status = match(true) {
            $total > 0 && $done === $total => 'completed',
            $inProg > 0 || $done > 0      => 'in_progress',
            default                        => 'not_started',
        };

        $milestone->update(['status' => $status]);
    }
}