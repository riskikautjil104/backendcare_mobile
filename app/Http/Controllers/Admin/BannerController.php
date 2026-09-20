<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Menampilkan daftar semua banner promosi / iklan.
     */
    public function index()
    {
        $banners = Banner::orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Form tambah banner baru.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Simpan banner baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_url' => 'nullable|url|max:500',
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        if (!$request->hasFile('image_file') && empty($validated['image_url'])) {
            return back()->withInput()->withErrors([
                'image_url' => 'Harap unggah file gambar banner atau masukkan tautan URL gambar eksternal.',
            ]);
        }

        $imagePath = $validated['image_url'] ?? '';

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('banners', $filename, 'public');
            $imagePath = $path;
        }

        Banner::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image_url' => $imagePath,
            'link_url' => $validated['link_url'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'order' => (int) ($validated['order'] ?? 0),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner iklan berhasil ditambahkan.');
    }

    /**
     * Form edit banner.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Perbarui data banner.
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_url' => 'nullable|url|max:500',
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $imagePath = $banner->image_url;

        if ($request->hasFile('image_file')) {
            // Hapus file lama jika disimpan di storage lokal
            if (!empty($banner->image_url) && !str_starts_with($banner->image_url, 'http')) {
                Storage::disk('public')->delete(ltrim($banner->image_url, '/'));
            }

            $file = $request->file('image_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('banners', $filename, 'public');
            $imagePath = $path;
        } elseif (!empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        $banner->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image_url' => $imagePath,
            'link_url' => $validated['link_url'] ?? null,
            'is_active' => $request->boolean('is_active', false),
            'order' => (int) ($validated['order'] ?? 0),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner iklan berhasil diperbarui.');
    }

    /**
     * Hapus banner.
     */
    public function destroy(Banner $banner)
    {
        if (!empty($banner->image_url) && !str_starts_with($banner->image_url, 'http')) {
            Storage::disk('public')->delete(ltrim($banner->image_url, '/'));
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner iklan berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif banner.
     */
    public function toggle(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        $statusText = $banner->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Status banner berhasil {$statusText}.");
    }
}
