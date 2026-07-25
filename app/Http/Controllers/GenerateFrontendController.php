<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GenerateFrontendController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate(['transcript' => 'required|string|min:20']);

        $apiKey    = config('services.gemini.key');
        $transcript = $request->transcript;

        $prompt = <<<EOT
You are an expert frontend developer. Based on the meeting transcript below, generate a complete single-file HTML application that serves as a frontend prototype/mockup for the client's requirements.

MEETING TRANSCRIPT:
{$transcript}

INSTRUCTIONS:
1. Analyze what kind of app/CRM/system the client needs
2. Generate a COMPLETE, beautiful, single HTML file with embedded CSS and JS
3. Include realistic UI with proper navigation, forms, tables, and sample data
4. Use a modern, professional design (dark or light theme, your choice)
5. Include ALL main sections/modules mentioned in the meeting
6. Add sample/dummy data so it looks realistic and functional
7. Make buttons and forms interactive (use localStorage for data persistence if needed)
8. Add a prominent header with the app name derived from the requirements

REQUIREMENTS FOR THE HTML:
- Must be a single complete HTML file (no external dependencies except CDN links)
- Use Tailwind CSS via CDN for styling
- Use vanilla JS only
- Must have proper navigation/sidebar
- Must look like a real professional application
- Include at least 3-4 main modules/sections based on requirements
- Add sample data (at least 5-10 records per section)

Return ONLY the complete HTML code, nothing else. Start with <!DOCTYPE html>.
EOT;

        try {
            $response = Http::withOptions(['verify' => false, 'timeout' => 60, 'connect_timeout' => 10])->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent?key={$apiKey}",
                [
                    'contents'         => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature'     => 0.7,
                        'maxOutputTokens' => 8192,
                    ],
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Connection failed: ' . $e->getMessage()], 500);
        }

        if (!$response->ok()) {
            $error = $response->json('error.message') ?? 'Gemini API error';
            return response()->json(['error' => $error], 500);
        }

        $html = $response->json('candidates.0.content.parts.0.text');

        if (!$html) {
            return response()->json(['error' => 'No HTML generated'], 500);
        }

        // Strip markdown code fences if present
        $html = preg_replace('/^```html\s*/i', '', trim($html));
        $html = preg_replace('/```\s*$/', '', $html);

        return response()->json(['html' => $html]);
    }
}
