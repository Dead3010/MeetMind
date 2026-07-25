<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GenerateFrontendController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate(['transcript' => 'required|string|min:20']);

        $apiKey     = config('services.gemini.key');
        $transcript = $request->transcript;
        $style      = $request->input('style', 'modern');

        $styleGuide = match($style) {
            'corporate' => 'Corporate style: navy/white palette, clean sans-serif fonts, formal layout with sidebar navigation, data-dense tables, conservative color accents in blue/grey tones.',
            'minimal'   => 'Minimal style: pure white background, generous whitespace, light grey borders, black text, no gradients, flat buttons, single-column layouts where possible.',
            default     => 'Modern style: vibrant gradients, rounded corners, card-based layouts, subtle shadows, colorful accent colors, glassmorphism elements where appropriate.',
        };

        $prompt = <<<EOT
You are a world-class frontend developer and UI/UX designer. Analyze the meeting transcript below and generate a complete, production-quality single-file HTML application as a prototype for the client's requirements.

MEETING TRANSCRIPT:
{$transcript}

DESIGN STYLE: {$styleGuide}

STEP 1 — ANALYSIS (do this mentally, do not output it):
- Identify the core domain (CRM, ERP, inventory, HR, project management, etc.)
- List every module/feature mentioned or implied
- Identify key entities (users, orders, tasks, etc.) and their relationships
- Determine the primary user role and their main workflow

STEP 2 — GENERATE the HTML with these requirements:

STRUCTURE:
- Sticky top header with app logo/name, user avatar, notification bell
- Left sidebar with icon + label navigation for all modules
- Main content area with breadcrumb trail
- At least 4-5 distinct module screens (switchable via sidebar)
- Dashboard/home screen with KPI cards and mini charts

DATA & INTERACTIVITY:
- Minimum 10 realistic records per data table (use domain-appropriate fake names/values)
- Working search and filter on tables
- Add/Edit modal forms with validation
- Delete with confirmation
- LocalStorage persistence for CRUD operations
- Charts using Chart.js via CDN (at least 2 charts on dashboard)
- Toast notifications for user actions

UI POLISH:
- Smooth CSS transitions on hover/active states
- Loading skeletons or spinners where appropriate
- Empty state illustrations (SVG or emoji) when no data
- Responsive: works on mobile with hamburger menu
- Active nav state highlighting
- Keyboard shortcut hints in tooltips

TECHNICAL:
- Single HTML file, all CSS and JS inline
- Tailwind CSS via CDN for styling
- Chart.js via CDN for charts
- No other external dependencies
- Vanilla JS only, no frameworks
- Must start with <!DOCTYPE html>

Return ONLY the complete HTML. No explanation, no markdown fences. Start directly with <!DOCTYPE html>.
EOT;

        try {
            $response = Http::withOptions(['verify' => false, 'timeout' => 60, 'connect_timeout' => 10])->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent?key={$apiKey}",
                [
                    'contents'         => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature'     => 0.8,
                        'maxOutputTokens' => 16384,
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
