<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use App\Models\Thread;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;
use App\Filters\ThreadFilters;

class ThreadController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware("auth")->except([
            'index',
            'show'
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return Response|Collection
     */
    public function index(Request $request, Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if ($request->wantsJson()) {
            return $threads;
        }

        return Inertia::render('Threads/Index', [
            'threads' => $threads,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Threads/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => $request->channel_id,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return Redirect::route('threads.show', ['thread' => $thread->id, 'channel' => $thread->channel->slug]);
    }

    /**
     * Display the specified resource.
     *
     * @param Channel $channel
     * @param Thread $thread
     * @return Response
     */
    public function show(Channel $channel, Thread $thread)
    {
        return Inertia::render('Threads/Show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(20)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Channel $channel
     * @param Thread $thread
     * @return RedirectResponse
     */
    public function delete(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        return redirect()->route('threads');
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return Collection
     */
    private function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->get();
    }
}
