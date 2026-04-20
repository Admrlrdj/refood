@extends('layouts.app')

@section('content')
    @php
        $mappedFoods = $foods->map(function ($f) use ($donors) {
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
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Restoran / Donatur</th>
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
                                    <td class="px-4 py-4 text-right space-x-3">
                                        <button @click="openEditModal(f)"
                                            class="text-warning-500 hover:underline text-sm font-medium">Edit</button>
                                        <button @click="openDeleteModal(f)"
                                            class="text-error-500 hover:underline text-sm font-medium">Hapus</button>
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
        let map, marker, editMap, editMarker;

        function initLeafletMap() {
            if (map) map.remove();
            const defaultLat = -6.200000,
                defaultLng = 106.816666;
            map = L.map('modalMap').setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            document.getElementById('inputLatitude').value = defaultLat;
            document.getElementById('inputLongitude').value = defaultLng;

            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                document.getElementById('inputLatitude').value = pos.lat;
                document.getElementById('inputLongitude').value = pos.lng;
                map.panTo(pos);
            });
        }

        function initLeafletEditMap(lat, lng) {
            if (editMap) editMap.remove();
            editMap = L.map('editModalMap').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(editMap);
            editMarker = L.marker([lat, lng], {
                draggable: true
            }).addTo(editMap);

            editMarker.on('dragend', function(e) {
                const pos = editMarker.getLatLng();
                document.getElementById('editInputLatitude').value = pos.lat;
                document.getElementById('editInputLongitude').value = pos.lng;
                document.getElementById('editInputLatitude').dispatchEvent(new Event('input'));
                document.getElementById('editInputLongitude').dispatchEvent(new Event('input'));
                editMap.panTo(pos);
            });
        }

        document.getElementById('btnSearchMap')?.addEventListener('click', function() {
            const q = document.getElementById('mapSearchInput').value;
            if (!q) return alert('Ketik alamat dulu!');
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}`)
                .then(r => r.json()).then(d => {
                    if (d.length > 0) {
                        const lat = parseFloat(d[0].lat),
                            lon = parseFloat(d[0].lon);
                        map.setView([lat, lon], 16);
                        marker.setLatLng([lat, lon]);
                        document.getElementById('inputLatitude').value = lat;
                        document.getElementById('inputLongitude').value = lon;
                    } else alert('Alamat tidak ditemukan.');
                });
        });

        document.getElementById('btnEditSearchMap')?.addEventListener('click', function() {
            const q = document.getElementById('editMapSearchInput').value;
            if (!q) return alert('Ketik alamat dulu!');
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}`)
                .then(r => r.json()).then(d => {
                    if (d.length > 0) {
                        const lat = parseFloat(d[0].lat),
                            lon = parseFloat(d[0].lon);
                        editMap.setView([lat, lon], 16);
                        editMarker.setLatLng([lat, lon]);
                        document.getElementById('editInputLatitude').value = lat;
                        document.getElementById('editInputLongitude').value = lon;
                        document.getElementById('editInputLatitude').dispatchEvent(new Event('input'));
                        document.getElementById('editInputLongitude').dispatchEvent(new Event('input'));
                    } else alert('Alamat tidak ditemukan.');
                });
        });

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
                    this.editForm.latitude = food.latitude || -6.200000;
                    this.editForm.longitude = food.longitude || 106.816666;
                    this.isEditModalOpen = true;
                    // Jeda agar element DOM modal terbuka sebelum render peta
                    setTimeout(() => {
                        if (document.getElementById('editModalMap')) {
                            initLeafletEditMap(this.editForm.latitude, this.editForm.longitude);
                            if (editMap) editMap.invalidateSize();
                        }
                    }, 250);
                },

                openDeleteModal(food) {
                    this.deleteForm = {
                        id: food.id,
                        name: food.name
                    };
                    this.isDeleteModalOpen = true;
                },

                init() {
                    this.$watch('isModalOpen', (val) => {
                        if (val) {
                            setTimeout(() => {
                                if (document.getElementById('modalMap')) {
                                    initLeafletMap();
                                    if (map) map.invalidateSize();
                                }
                            }, 250);
                        }
                    });
                }
            }));
        });
    </script>
@endsection
