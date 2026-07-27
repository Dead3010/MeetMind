<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->meetings()->latest()->get());
    }

    public function store(Request $request)
    {
        $meeting = $request->user()->meetings()->create([
            'title'              => $request->title,
            'summary'            => $request->summary,
            'topic'              => $request->topic,
            'priority'           => $request->priority,
            'priority_reason'    => $request->priority_reason,
            'action_items'       => $request->action_items ?? [],
            'key_decisions'      => $request->key_decisions ?? [],
            'sentiment'          => $request->sentiment,
            'duration_estimate'  => $request->duration_estimate,
            'transcript'         => $request->transcript,
            'date'               => $request->date ?? now(),
            'conclusion'         => $request->conclusion,
            'conversation_flow'  => $request->conversation_flow ?? [],
            'speaker_sentiments' => $request->speaker_sentiments ?? [],
            'mom'                => $request->mom,
        ]);

        return response()->json($meeting);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->meetings()->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function clearAll(Request $request)
    {
        $request->user()->meetings()->delete();
        return response()->json(['success' => true]);
    }

    public function savePreview(Request $request, $id)
    {
        $request->validate(['html' => 'required|string']);
        $meeting = $request->user()->meetings()->findOrFail($id);
        $meeting->update(['preview_html' => $request->html]);
        return response()->json(['success' => true]);
    }
}
