<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TourPackageController extends Controller
{
    /**
     * ============================================================
     * INDEX
     * ============================================================
     *
     * Menampilkan daftar tour package.
     */
    public function index(Request $request)
    {
        $query = TourPackage::query()
            ->with([
                'destinations:id,name,slug,location,category',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('tag', 'like', "%{$search}%")
                    ->orWhere(
                        'short_description',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas(
                        'destinations',
                        function ($destination) use ($search) {
                            $destination->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status')
            && $request->input('status') !== 'all'
        ) {

            if ($request->input('status') === 'active') {

                $query->where('is_active', true);

            } elseif ($request->input('status') === 'inactive') {

                $query->where('is_active', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FEATURED FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('featured')
            && $request->input('featured') !== 'all'
        ) {

            if ($request->input('featured') === 'featured') {

                $query->where('is_featured', true);

            } elseif ($request->input('featured') === 'not_featured') {

                $query->where('is_featured', false);
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

        $packages = [
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
            'total' => TourPackage::count(),

            'active' => TourPackage::where(
                'is_active',
                true
            )->count(),

            'inactive' => TourPackage::where(
                'is_active',
                false
            )->count(),

            'featured' => TourPackage::where(
                'is_featured',
                true
            )->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'Admin/TourPackages/Index',
            [
                'packages' => $packages,

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

                    'featured' => $request->input(
                        'featured',
                        'all'
                    ),
                ],
            ]
        );
    }


    /**
     * ============================================================
     * CREATE
     * ============================================================
     *
     * Form tambah tour package.
     */
    public function create()
    {
        $destinations = Destination::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'location',
                'category',
            ]);

        return Inertia::render(
            'Admin/TourPackages/Create',
            [
                'destinations' => $destinations,
            ]
        );
    }


    /**
     * ============================================================
     * STORE
     * ============================================================
     *
     * Menyimpan tour package baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'tag' => [
                'nullable',
                'string',
                'max:100',
            ],

            'short_description' => [
                'required',
                'string',
                'max:500',
            ],

            'description' => [
                'required',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'duration_days' => [
                'required',
                'integer',
                'min:1',
            ],

            'duration_nights' => [
                'required',
                'integer',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'destination_ids' => [
                'nullable',
                'array',
            ],

            'destination_ids.*' => [
                'integer',
                'exists:destinations,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */

        $slug = $this->generateUniqueSlug(
            $validated['name']
        );

        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request
                ->file('image')
                ->store(
                    'tour-packages',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE + RELATION
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(function () use (
                $validated,
                $request,
                $slug,
                $imagePath
            ) {

                $package = TourPackage::create([

                    'name' =>
                        $validated['name'],

                    'slug' =>
                        $slug,

                    'tag' =>
                        $validated['tag'] ?? null,

                    'short_description' =>
                        $validated['short_description'],

                    'description' =>
                        $validated['description'],

                    'price' =>
                        $validated['price'],

                    'duration_days' =>
                        $validated['duration_days'],

                    'duration_nights' =>
                        $validated['duration_nights'],

                    'image' =>
                        $imagePath,

                    'is_featured' =>
                        $request->boolean('is_featured'),

                    'is_active' =>
                        $request->boolean('is_active'),
                ]);

                $this->syncDestinations(
                    $package,
                    $validated['destination_ids'] ?? []
                );
            });

        } catch (\Throwable $e) {

            /*
            | Jika database gagal setelah gambar tersimpan,
            | hapus gambar supaya tidak menjadi file yatim.
            */

            if ($imagePath) {

                Storage::disk('public')
                    ->delete($imagePath);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.tour-packages.index')
            ->with(
                'success',
                'Tour package berhasil ditambahkan.'
            );
    }


    /**
     * ============================================================
     * EDIT
     * ============================================================
     *
     * Form edit tour package.
     */
    public function edit(TourPackage $tourPackage)
    {
        $tourPackage->load([
            'destinations:id,name,location,category',
        ]);

        $destinations = Destination::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'location',
                'category',
            ]);

        return Inertia::render(
            'Admin/TourPackages/Edit',
            [
                'package' => $tourPackage,
                'destinations' => $destinations,
            ]
        );
    }


    /**
     * ============================================================
     * UPDATE
     * ============================================================
     *
     * Memperbarui tour package.
     */
    public function update(
        Request $request,
        TourPackage $tourPackage
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'tag' => [
                'nullable',
                'string',
                'max:100',
            ],

            'short_description' => [
                'required',
                'string',
                'max:500',
            ],

            'description' => [
                'required',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'duration_days' => [
                'required',
                'integer',
                'min:1',
            ],

            'duration_nights' => [
                'required',
                'integer',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'remove_image' => [
                'nullable',
                'boolean',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'destination_ids' => [
                'nullable',
                'array',
            ],

            'destination_ids.*' => [
                'integer',
                'exists:destinations,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */

        $slug = $this->generateUniqueSlug(
            $validated['name'],
            $tourPackage->id
        );

        /*
        |--------------------------------------------------------------------------
        | OLD IMAGE
        |--------------------------------------------------------------------------
        */

        $oldImage = $tourPackage->image;

        $newImagePath = null;

        /*
        |--------------------------------------------------------------------------
        | NEW IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $newImagePath = $request
                ->file('image')
                ->store(
                    'tour-packages',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATABASE
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(function () use (
                $request,
                $validated,
                $tourPackage,
                $slug,
                &$oldImage,
                $newImagePath
            ) {

                /*
                |--------------------------------------------------------------
                | Data utama
                |--------------------------------------------------------------
                */

                $tourPackage->update([

                    'name' =>
                        $validated['name'],

                    'slug' =>
                        $slug,

                    'tag' =>
                        $validated['tag'] ?? null,

                    'short_description' =>
                        $validated['short_description'],

                    'description' =>
                        $validated['description'],

                    'price' =>
                        $validated['price'],

                    'duration_days' =>
                        $validated['duration_days'],

                    'duration_nights' =>
                        $validated['duration_nights'],

                    'is_featured' =>
                        $request->boolean(
                            'is_featured'
                        ),

                    'is_active' =>
                        $request->boolean(
                            'is_active'
                        ),
                ]);


                /*
                |--------------------------------------------------------------
                | HAPUS GAMBAR
                |--------------------------------------------------------------
                */

                if (
                    $request->boolean('remove_image')
                    && !$newImagePath
                    && $tourPackage->image
                ) {

                    $oldImage =
                        $tourPackage->image;

                    $tourPackage->update([
                        'image' => null,
                    ]);
                }


                /*
                |--------------------------------------------------------------
                | GANTI GAMBAR
                |--------------------------------------------------------------
                */

                if ($newImagePath) {

                    $oldImage =
                        $tourPackage->image;

                    $tourPackage->update([
                        'image' => $newImagePath,
                    ]);
                }


                /*
                |--------------------------------------------------------------
                | DESTINATIONS
                |--------------------------------------------------------------
                */

                $this->syncDestinations(
                    $tourPackage,
                    $validated['destination_ids'] ?? []
                );
            });

        } catch (\Throwable $e) {

            /*
            | Jika proses database gagal,
            | gambar baru jangan dibiarkan.
            */

            if ($newImagePath) {

                Storage::disk('public')
                    ->delete($newImagePath);
            }

            throw $e;
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE OLD IMAGE AFTER SUCCESS
        |--------------------------------------------------------------------------
        */

        if (
            $oldImage
            && (
                $request->boolean('remove_image')
                || $newImagePath
            )
        ) {

            if (
                Storage::disk('public')
                    ->exists($oldImage)
            ) {

                Storage::disk('public')
                    ->delete($oldImage);
            }
        }


        return redirect()
            ->route('admin.tour-packages.index')
            ->with(
                'success',
                'Tour package berhasil diperbarui.'
            );
    }


    /**
     * ============================================================
     * DESTROY
     * ============================================================
     *
     * Menghapus tour package.
     */
    public function destroy(
        TourPackage $tourPackage
    ) {

        /*
        |--------------------------------------------------------------------------
        | CEK BOOKING
        |--------------------------------------------------------------------------
        |
        | Jangan sampai package yang sudah digunakan booking
        | dihapus sembarangan.
        |
        */

        if (
            method_exists(
                $tourPackage,
                'bookings'
            )
            && $tourPackage
                ->bookings()
                ->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'Tour package tidak dapat dihapus karena sudah digunakan pada booking.'
                );
        }


        $image = $tourPackage->image;


        DB::transaction(function () use (
            $tourPackage
        ) {

            /*
            | Lepaskan relasi destination.
            */

            $tourPackage
                ->destinations()
                ->detach();

            /*
            | Hapus package.
            */

            $tourPackage->delete();
        });


        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE GAMBAR
        |--------------------------------------------------------------------------
        */

        if (
            $image
            && Storage::disk('public')
                ->exists($image)
        ) {

            Storage::disk('public')
                ->delete($image);
        }


        return redirect()
            ->route('admin.tour-packages.index')
            ->with(
                'success',
                'Tour package berhasil dihapus.'
            );
    }


    /**
     * ============================================================
     * TOGGLE ACTIVE
     * ============================================================
     */
    public function toggleActive(
        TourPackage $tourPackage
    ) {

        $tourPackage->update([
            'is_active' =>
                !$tourPackage->is_active,
        ]);

        return back()
            ->with(
                'success',
                $tourPackage->is_active
                    ? 'Tour package berhasil diaktifkan.'
                    : 'Tour package berhasil dinonaktifkan.'
            );
    }


    /**
     * ============================================================
     * TOGGLE FEATURED
     * ============================================================
     */
    public function toggleFeatured(
        TourPackage $tourPackage
    ) {

        $tourPackage->update([
            'is_featured' =>
                !$tourPackage->is_featured,
        ]);

        return back()
            ->with(
                'success',
                $tourPackage->is_featured
                    ? 'Tour package ditambahkan ke featured.'
                    : 'Tour package dihapus dari featured.'
            );
    }


    /**
     * ============================================================
     * GENERATE UNIQUE SLUG
     * ============================================================
     */
    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {

        $slug = Str::slug($name);

        /*
        | Jika nama hanya menghasilkan slug kosong.
        */

        if ($slug === '') {
            $slug = 'tour-package';
        }

        $originalSlug = $slug;

        $counter = 1;


        while (
            TourPackage::query()
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    function ($query) use ($ignoreId) {
                        $query->where(
                            'id',
                            '!=',
                            $ignoreId
                        );
                    }
                )
                ->exists()
        ) {

            $slug =
                $originalSlug
                . '-'
                . $counter;

            $counter++;
        }


        return $slug;
    }


    /**
     * ============================================================
     * SYNC DESTINATIONS
     * ============================================================
     */
    private function syncDestinations(
        TourPackage $package,
        array $destinationIds
    ): void {

        /*
        | Hilangkan ID duplikat.
        */

        $destinationIds =
            array_values(
                array_unique(
                    array_map(
                        'intval',
                        $destinationIds
                    )
                )
            );


        $syncData = [];


        foreach (
            $destinationIds
            as $index => $destinationId
        ) {

            $syncData[$destinationId] = [
                'sort_order' =>
                    $index + 1,
            ];
        }


        $package
            ->destinations()
            ->sync($syncData);
    }
}