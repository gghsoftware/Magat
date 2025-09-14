<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
         return view('admin.manage-package.index');
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $packages = Package::when($q !== '', fn($qr) =>
                $qr->where('name','like',"%{$q}%")
                   ->orWhere('slug','like',"%{$q}%"))
            ->orderByDesc('id')
            ->paginate(10)->withQueryString();

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $package = new Package(['is_active' => true]);
        return view('admin.packages.create', compact('package'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('packages', 'public');
        }

        $data['inclusions'] = $this->normalizeJsonArray($request->input('inclusions'));

        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $img) {
                $galleryPaths[] = $img->store('packages/gallery', 'public');
            }
        }
        $data['gallery'] = $galleryPaths;

        $package = Package::create($data);

        return redirect()->route('admin.packages.index')
            ->with('success', "Package “{$package->name}” created.");
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $this->validateData($request, $package->id);

        if ($request->hasFile('thumbnail')) {
            if ($package->thumbnail && !str_starts_with($package->thumbnail, 'http')) {
                Storage::disk('public')->delete($package->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('packages', 'public');
        }

        $data['inclusions'] = $this->normalizeJsonArray($request->input('inclusions'));

        $existing = is_array($package->gallery) ? $package->gallery : [];

        $toRemove = (array) $request->input('remove_gallery', []);
        foreach ($toRemove as $path) {
            if ($path && !str_starts_with($path, 'http')) {
                Storage::disk('public')->delete($path);
            }
            $existing = array_values(array_filter($existing, fn($p) => $p !== $path));
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $img) {
                $existing[] = $img->store('packages/gallery', 'public');
            }
        }

        $data['gallery'] = $existing;

        $package->update($data);

        return redirect()->route('admin.packages.index')
            ->with('success', "Package “{$package->name}” updated.");
    }

    public function destroy(Package $package)
    {
        if ($package->thumbnail && !str_starts_with($package->thumbnail, 'http')) {
            Storage::disk('public')->delete($package->thumbnail);
        }
        if (is_array($package->gallery)) {
            foreach ($package->gallery as $path) {
                if ($path && !str_starts_with($path, 'http')) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
        $package->delete();

        return back()->with('success', 'Package deleted.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name'             => ['required','string','max:255'],
            'slug'             => ['nullable','string','max:255', Rule::unique('packages','slug')->ignore($id)],
            'price'            => ['required','integer','min:0'],

            'thumbnail'        => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'gallery_images'   => ['nullable','array'],
            'gallery_images.*' => ['image','mimes:jpg,jpeg,png,webp','max:2048'],

            'inclusions'       => ['nullable'],
            'is_active'        => ['required','boolean'],
        ]);
    }

    private function normalizeJsonArray($input): ?array
    {
        if (is_array($input)) {
            return array_values(array_filter(array_map('trim', $input), fn($v) => $v !== ''));
        }
        if (is_string($input) && $input !== '') {
            $trimmed = trim($input);
            if (str_starts_with($trimmed, '[')) {
                $decoded = json_decode($trimmed, true);
                return is_array($decoded) ? $decoded : null;
            }
            $lines = preg_split('/\r\n|\r|\n/', $trimmed);
            return array_values(array_filter(array_map('trim', $lines), fn($v) => $v !== ''));
        }
        return null;
    }
}
