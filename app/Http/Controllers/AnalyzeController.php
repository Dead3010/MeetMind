<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnalyzeController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate(['transcript' => 'required|string|min:20']);

        $apiKey     = $request->input('gemini_key') ?: config('services.gemini.key');
        $transcript = $request->transcript;

        $prompt = <<<EOT
You are an expert meeting analyst. Analyze the following meeting transcript and return a structured JSON object.

The transcript may be tagged with [You] for the main speaker and [Others] for other participants. If no tags exist, intelligently infer speaker changes from context (topic shifts, question-answer patterns, contrasting opinions). Label the main/first speaker as "You" and others as "Guest 1", "Guest 2", etc. consistently throughout.

TRANSCRIPT:
{$transcript}

Return ONLY valid JSON with this exact structure:
{
  "title": "Short descriptive title for this meeting (max 8 words)",
  "summary": "3-4 sentence summary of the entire meeting",
  "topic": "one of: Product, Sales, HR, Finance, Marketing, Engineering, Design, Legal, Operations, General",
  "priority": "one of: Urgent, High, Normal, Low",
  "priority_reason": "One sentence explaining why this priority was assigned",
  "action_items": ["action item 1", "action item 2"],
  "key_decisions": ["decision 1", "decision 2"],
  "duration_estimate": "Estimated meeting duration based on content e.g. 30 mins",
  "sentiment": "one of: Positive, Neutral, Tense, Mixed",
  "conclusion": "2-3 sentences describing the final outcome and what was agreed upon at the end of the meeting",
  "conversation_flow": [
    {"speaker": "You", "text": "what this person said or discussed"},
    {"speaker": "Guest 1", "text": "how they responded or what they said"}
  ],
  "speaker_sentiments": {
    "You": "one of: Confident, Agreeable, Skeptical, Enthusiastic, Neutral, Concerned",
    "Guest 1": "one of: Confident, Agreeable, Skeptical, Enthusiastic, Neutral, Concerned"
  },
  "mom": {
    "attendees": ["You", "Guest 1"],
    "agenda": "What was the main agenda or purpose of this meeting",
    "discussion_points": [
      "Key point discussed 1",
      "Key point discussed 2"
    ]
  }
}

Rules:
- conversation_flow should have 4-8 entries capturing the main dialogue exchanges
- Keep each conversation_flow text concise (1-2 sentences max)
- speaker_sentiments should include all identified speakers
- mom.attendees should list all identified speakers
- Priority: Urgent=deadlines/blockers, High=important decisions, Normal=regular updates, Low=FYI only
EOT;

        try {
            $response = Http::withOptions(['verify' => false, 'timeout' => 60, 'connect_timeout' => 10])->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}",
                [
                    'contents'         => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['response_mime_type' => 'application/json'],
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Connection failed: ' . $e->getMessage()], 500);
        }

        if (! $response->ok()) {
            $error = $response->json('error.message') ?? 'Gemini API error';
            return response()->json(['error' => $error], 500);
        }

        $text = $response->json('candidates.0.content.parts.0.text');
        $data = json_decode($text, true);

        if (! $data) {
            return response()->json(['error' => 'Invalid response from Gemini'], 500);
        }

        return response()->json($data);
    }
}
