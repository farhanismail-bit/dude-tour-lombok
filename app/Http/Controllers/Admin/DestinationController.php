<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DestinationController extends Controller
{
    /**
     * Menampilkan daftar destination.
     */
    public function index(Request $request)
    {
        $query = Destination::query()
            ->withCount('tourPackages');

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'location',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'category',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'short_description',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status')
            && $request->input('status') !== 'all'
        ) {

            if ($request->input('status') === 'active') {

                $query->where(
                    'is_active',
                    true
                );

            } elseif ($request->input('status') === 'inactive') {

                $query->where(
                    'is_active',
                    false
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $paginator = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $destinations = [
            'data' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'from' => $paginator->firstItem(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'to' => $paginator->lastItem(),
            'total' => $paginator->total(),
            'links' => $paginator->linkCollection()->toArray(),
        ];

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $statistics = [

            'total' => Destination::count(),

            'active' => Destination::where(
                'is_active',
                true
            )->count(),

            'inactive' => Destination::where(
                'is_active',
                false
            )->count(),

            'used' => Destination::has(
                'tourPackages'
            )->count(),

            'unused' => Destination::doesntHave(
                'tourPackages'
            )->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | RETURN INERTIA
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'Admin/Destinations/Index',
            [
                'destinations' => $destinations,

                'statistics' => $statistics,

                'filters' => [
                    'search' => $request->input(
                        'search',
                        ''
                    ),

                    'status' => $request->input(
                        'status',
                        'all'
                    ),
                ],
            ]
        );
    }

    /**
     * Form tambah destination.
     */
    public function create()
    {
        return Inertia::render(
            'Admin/Destinations/Create'
        );
    }

    /**
     * Simpan destination baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:destinations,slug',
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */

        $validated['slug'] = $validated['slug']
            ?: Str::slug($validated['name']);

        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $validated['image'] = $request
                ->file('image')
                ->store(
                    'destinations',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVE
        |--------------------------------------------------------------------------
        */

        $validated['is_active'] =
            $request->boolean(
                'is_active'
            );

        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        Destination::create(
            $validated
        );

        return redirect()
            ->route('admin.destinations.index')
            ->with(
                'success',
                'Destination berhasil ditambahkan.'
            );
    }

    /**
     * Detail destination.
     */
    public function show(
        Destination $destination
    ) {

        $destination->load([
            'tourPackages' => function ($query) {

                $query
                    ->select([
                        'tour_packages.id',
                        'tour_packages.name',
                        'tour_packages.slug',
                        'tour_packages.price',
                        'tour_packages.is_active',
                        'tour_packages.is_featured',
                    ])
                    ->orderBy(
                        'tour_package_destinations.sort_order'
                    );
            },
        ]);

        return Inertia::render(
            'Admin/Destinations/Show',
            [
                'destination' => $destination,
            ]
        );
    }

    /**
     * Form edit.
     */
    public function edit(
        Destination $destination
    ) {

        return Inertia::render(
            'Admin/Destinations/Edit',
            [
                'destination' => $destination,
            ]
        );
    }

    /**
     * Update destination.
     */
    public function update(
        Request $request,
        Destination $destination
    ) {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:destinations,slug,' . $destination->id,
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */

        $validated['slug'] = $validated['slug']
            ?: Str::slug($validated['name']);

        /*
        |--------------------------------------------------------------------------
        | IMAGE BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            if (
                $destination->image
                && Storage::disk('public')->exists(
                    $destination->image
                )
            ) {

                Storage::disk('public')->delete(
                    $destination->image
                );
            }

            $validated['image'] = $request
                ->file('image')
                ->store(
                    'destinations',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVE
        |--------------------------------------------------------------------------
        */

        $validated['is_active'] =
            $request->boolean(
                'is_active'
            );

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $destination->update(
            $validated
        );

        return redirect()
            ->route(
                'admin.destinations.index'
            )
            ->with(
                'success',
                'Destination berhasil diperbarui.'
            );
    }

    /**
     * Hapus destination.
     */
    public function destroy(
        Destination $destination
    ) {

        /*
        |--------------------------------------------------------------------------
        | CEK RELASI
        |--------------------------------------------------------------------------
        */

        if (
            $destination->tourPackages()->exists()
        ) {

            return back()->with(
                'error',
                'Destination tidak dapat dihapus karena masih digunakan oleh paket tour.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE IMAGE
        |--------------------------------------------------------------------------
        */

        if (
            $destination->image
            && Storage::disk('public')->exists(
                $destination->image
            )
        ) {

            Storage::disk('public')->delete(
                $destination->image
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        $destination->delete();

        return redirect()
            ->route(
                'admin.destinations.index'
            )
            ->with(
                'success',
                'Destination berhasil dihapus.'
            );
    }

    /**
     * Toggle status aktif.
     */
    public function toggleActive(
        Destination $destination
    ) {

        $destination->update([
            'is_active' => !$destination->is_active,
        ]);

        return back()->with(
            'success',
            $destination->is_active
                ? 'Destination berhasil diaktifkan.'
                : 'Destination berhasil dinonaktifkan.'
        );
    }
}