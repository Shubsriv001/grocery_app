<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function update(Request $request)
    {
        $theme = $request->input('theme');
        
        // Validate theme
        if (!in_array($theme, ['light', 'dark'])) {
            return response()->json(['error' => 'Invalid theme'], 400);
        }

        // Store theme in session
        session(['theme' => $theme]);

        return response()->json(['success' => true]);
    }
}
