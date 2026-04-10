<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeoSetting;

class SeoSettingController extends Controller
{
    /**
     * Ambil data SEO berdasarkan page
     */
    public function index(Request $request)
    {
        $page = $request->query('page');

        $seo = SeoSetting::where('page', $page)->first();

        return response()->json([
            'success' => true,
            'data' => $seo
        ]);
    }

    /**
     * Update / Create SEO berdasarkan page
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'page' => 'required|string|max:100', // 🔥 WAJIB

            'keywords' => 'nullable|string',
            'page_title' => 'required|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_author' => 'nullable|string|max:255',
            'meta_robots' => 'nullable|string|max:50',
            'heading_h1' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'enable_sitemap' => 'boolean',
            'enable_robots' => 'boolean',
        ]);

        if ($request->hasFile('hero_image')) {
            $validated['hero_image'] = $request->file('hero_image')->store('seo', 'public');
        }

        if (!in_array($validated['page'], ['home', 'sentra_batik'])) {
            $validated['hero_image'] = null;
            $validated['image_alt'] = null;
        }

        $seo = SeoSetting::updateOrCreate(
            ['page' => $validated['page']], // 🔥 berdasarkan page
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'SEO berhasil diperbarui',
            'data' => $seo
        ]);
    }
}
