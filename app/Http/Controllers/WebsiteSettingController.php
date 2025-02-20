<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use Exception;
use Illuminate\Support\Facades\Validator;

class WebsiteSettingController extends Controller
{
    public function index()
    {
        $settings = WebsiteSetting::first();
        return view('dashboard.website-settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website_name' => 'required|string|max:255',
            'website_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $settings = WebsiteSetting::firstOrNew([]);

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $settings->logo = $logoPath;
            }

            if ($request->hasFile('favicon')) {
                $faviconPath = $request->file('favicon')->store('favicons', 'public');
                $settings->favicon = $faviconPath;
            }

            $settings->website_name = $request->website_name;
            $settings->website_description = $request->website_description;
            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully.',
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error updating settings.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
