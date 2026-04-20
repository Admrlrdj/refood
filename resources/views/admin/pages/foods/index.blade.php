@extends('admin.layouts.app')

@section('content')
    @php
        // Mapping agar data JSON aman masuk ke Alpine.js
        $mappedFoods = $foods->map(function ($f) use ($donors) {
            // Cari nama restoran berdasarkan donor_id
            $donor = $donors->firstWhere('_id', $f->donor_id);
            return [
                'id' => (string) $f->_id,
                'donor_id' => (string) $f->donor_id,
                'restaurant_name' => $donor ? $donor->restaurant_name : 'Unknown',
                'name' => $f->name,
                'description' => $f->description,
                'portion' => $f->portion,
                'pickup_address' => $f->pickup_address,
                'latitude' => $f->latitude,
                'longitude' => $f->longitude,
                'status' => $f->status,
                'expired_at' => \Carbon\Carbon::parse($f->expired_at)->format('Y-m-d\TH:i'),
            ];
        });
    @endphp

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-container {
            z-index: 10 !important;
        }
    </style>

    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Data Donasi Makanan</h2>
            <nav>
                <ol class="flex flex-wrap items-center gap-1.5">
                    <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-brand-500">Dashboard /</a>
                    </li>
                    <li class="font-medium text-gray-800 dark:text-white/90">Foods</li>
                </ol>
            </nav>
        </div>

        @if (session('success'))
            <div class="mb-5 p-4 bg-success-50 text-success-600 border-l-4 border-success-500">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-5 p-4 bg-error-50 text-error-600 border-l-4 border-error-500">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div x-data="foodTable()">
            @include('admin.pages.foods.components.create-modal')
            @include('admin.pages.foods.components.edit-modal')
            @include('admin.pages.foods.components.delete-modal')

            <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-gray-dark">
                <div class="flex justify-between items-center px-5 mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Makanan</h3>
                    <button @click="isModalOpen = true"
                        class="bg-brand-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-brand-600">+ Tambah
                        Donasi</button>
                </div>

                <div class="overflow-x-auto px-5">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Makanan</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Restoran</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Porsi</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="f in allItems" :key="f.id">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white" x-text="f.name"></div>
                                        <div class="text-xs text-error-500"
                                            x-text="'Exp: ' + f.expired_at.replace('T', ' ')"></div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500" x-text="f.restaurant_name"></td>
                                    <td class="px-4 py-4 text-sm text-gray-500 font-bold" x-text="f.portion + ' Porsi'">
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <span x-show="f.status == 'available'"
                                            class="px-2 py-1 bg-success-50 text-success-600 text-xs rounded-full">Tersedia</span>
                                        <span x-show="f.status == 'booked'"
                                            class="px-2 py-1 bg-warning-50 text-warning-600 text-xs rounded-full">Diambil
                                            Kurir</span>
                                        <span x-show="f.status == 'delivered'"
                                            class="px-2 py-1 bg-brand-50 text-brand-600 text-xs rounded-full">Selesai</span>
                                    </td>
                                    <td class="px-4 py-4 text-right space-x-2">
                                        <button @click="openEditModal(f)" class="text-warning-500 text-sm">Edit</button>
                                        <button @click="openDeleteModal(f)" class="text-error-500 text-sm">Hapus</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Copy logic Leaflet dari index Volunteer/Donor sebelumnya ke sini
        // ... (initLeafletMap, initLeafletEditMap) ...

        document.addEventListener('alpine:init', () => {
            Alpine.data('foodTable', () => ({
                isModalOpen: false,
                isEditModalOpen: false,
                isDeleteModalOpen: false,
                editForm: {
                    id: '',
                    donor_id: '',
                    name: '',
                    description: '',
                    portion: '',
                    pickup_address: '',
                    latitude: '',
                    longitude: '',
                    status: '',
                    expired_at: ''
                },
                deleteForm: {
                    id: '',
                    name: ''
                },
                allItems: @json($mappedFoods),

                openEditModal(food) {
                    this.editForm = {
                        ...food
                    };
                    this.isEditModalOpen = true;
                    // setTimeout(() => initLeafletEditMap(food.latitude, food.longitude), 200);
                },
                openDeleteModal(food) {
                    this.deleteForm = {
                        id: food.id,
                        name: food.name
                    };
                    this.isDeleteModalOpen = true;
                }
            }));
        });
    </script>
@endsection
