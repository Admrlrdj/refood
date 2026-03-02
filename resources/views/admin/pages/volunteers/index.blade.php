@extends('layouts.app')

@section('content')
    @php
        $mappedVolunteers = $volunteers->map(function ($v) {
            return [
                'id' => (string) $v->_id,
                'name' => $v->name,
                'username' => $v->username,
                'phone' => $v->phone,
                'vehicle_type' => $v->vehicle_type,
                'latitude' => $v->last_latitude,
                'longitude' => $v->last_longitude,
                'is_verified' => (bool) $v->is_verified, // Hanya gunakan boolean ini
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
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Volunteers</h2>
            <nav>
                <ol class="flex flex-wrap items-center gap-1.5">
                    <li class="flex items-center gap-1.5">
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-1 text-sm text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-400">Dashboard</a>
                    </li>
                    <li class="flex items-center gap-1.5">
                        <span class="text-gray-500 dark:text-gray-400"><svg class="w-4 h-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg></span>
                        <span
                            class="flex items-center gap-1 text-sm font-medium text-gray-800 dark:text-white/90">Volunteers</span>
                    </li>
                </ol>
            </nav>
        </div>

        @if (session('success'))
            <div x-data="{ showAlert: true }" x-show="showAlert" x-transition
                class="mb-5 flex w-full items-center border-l-6 border-success-500 bg-success-50 px-7 py-4 shadow-theme-md dark:border-success-500 dark:bg-gray-dark rounded-r-lg">
                <div class="mr-5 flex h-9 w-full max-w-[36px] items-center justify-center rounded-lg bg-success-500">
                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.2984 0.826822L15.2868 0.811827L15.2741 0.797751C14.9173 0.401867 14.3238 0.400754 13.9657 0.794406L5.91888 9.45376L2.05667 5.2868C1.69856 4.89287 1.10487 4.89389 0.747996 5.28987C0.417335 5.65675 0.417335 6.22337 0.747996 6.59026L0.747959 6.59029L0.752701 6.59541L4.86742 11.0348C5.14445 11.3405 5.52858 11.5 5.89581 11.5C6.29242 11.5 6.65178 11.3355 6.92401 11.035L15.2162 2.11161C15.5833 1.74452 15.576 1.18615 15.2984 0.826822Z"
                            fill="white" stroke="white"></path>
                    </svg>
                </div>
                <div class="w-full">
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ session('success') }}</h5>
                </div>
                <button @click="showAlert = false" type="button"
                    class="ml-auto p-1.5 rounded-lg text-gray-500 hover:bg-success-200 transition"><svg class="w-5 h-5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ showAlert: true }" x-show="showAlert" x-transition
                class="mb-5 flex w-full items-start border-l-6 border-error bg-error-50 px-7 py-4 shadow-theme-md dark:bg-[#1B1B24] dark:shadow-none rounded-r-lg">
                <div class="w-full text-error mt-0.5">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="showAlert = false" type="button"
                    class="ml-auto p-1.5 rounded-lg text-error hover:bg-error-200 transition"><svg class="w-5 h-5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>
        @endif

        <div x-data="volunteerTable()">

            <div x-show="isModalOpen" x-cloak style="display: none;"
                class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity p-4">
                <div @click.away="isModalOpen = false"
                    class="w-full max-w-2xl rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark max-h-[95vh] overflow-y-auto custom-scrollbar">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Tambah Relawan Baru</h3>
                        <button @click="isModalOpen = false"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"><svg
                                class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg></button>
                    </div>

                    <form action="{{ route('admin.volunteers.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                                    Lengkap</label><input type="text" name="name" required
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                                    placeholder="Masukkan nama"></div>
                            <div><label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">No. HP /
                                    WhatsApp</label><input type="text" name="phone" required
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                                    placeholder="08123456789"></div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label><input
                                    type="text" name="username" required
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                                    placeholder="budi_kurir"></div>
                            <div><label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label><input
                                    type="password" name="password" required minlength="6"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                                    placeholder="Minimal 6 karakter"></div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                Kendaraan</label>
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select name="vehicle_type" required
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                    @change="isOptionSelected = true">
                                    <option value="" disabled selected
                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Pilih Kendaraan</option>
                                    <option value="Motor" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Motor
                                    </option>
                                    <option value="Mobil" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Mobil
                                    </option>
                                    <option value="Sepeda" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Sepeda</option>
                                </select>
                                <span
                                    class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Titik Lokasi
                                Relawan (OpenStreetMap)</label>
                            <div class="flex gap-2 mb-3">
                                <input id="mapSearchInput" type="text"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white shadow-theme-xs"
                                    placeholder="Cari alamat atau nama tempat... (Contoh: Monas)">
                                <button type="button" id="btnSearchMap"
                                    class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition shadow-theme-xs whitespace-nowrap">Cari
                                    Peta</button>
                            </div>
                            <div id="modalMap"
                                class="w-full h-[250px] rounded-lg border border-gray-300 dark:border-gray-700 relative bg-gray-100">
                            </div>
                            <input type="hidden" name="latitude" id="inputLatitude" required>
                            <input type="hidden" name="longitude" id="inputLongitude" required>
                        </div>

                        <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                            <button type="button" @click="isModalOpen = false"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] transition">Batal</button>
                            <button type="submit"
                                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition shadow-theme-xs">Simpan
                                Data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="isEditModalOpen" x-cloak style="display: none;"
                class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity p-4">
                <div @click.away="isEditModalOpen = false"
                    class="w-full max-w-2xl rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark max-h-[95vh] overflow-y-auto custom-scrollbar">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Relawan: <span
                                x-text="editForm.name" class="text-brand-500"></span></h3>
                        <button @click="isEditModalOpen = false"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"><svg
                                class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg></button>
                    </div>

                    <form :action="'/admin/volunteers/' + editForm.id" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                                    Lengkap</label><input type="text" name="name" x-model="editForm.name" required
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white">
                            </div>
                            <div><label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">No. HP /
                                    WhatsApp</label><input type="text" name="phone" x-model="editForm.phone"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label><input
                                    type="text" name="username" x-model="editForm.username" required
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white">
                            </div>
                            <div><label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Password
                                    Baru</label><input type="password" name="password" minlength="6"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                                    placeholder="Kosongkan jika tidak diubah"></div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis
                                Kendaraan</label>
                            <div class="relative z-20 bg-transparent">
                                <select name="vehicle_type" x-model="editForm.vehicle_type" required
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 text-gray-800 dark:text-white/90">
                                    <option value="Motor" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Motor
                                    </option>
                                    <option value="Mobil" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Mobil
                                    </option>
                                    <option value="Sepeda" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        Sepeda</option>
                                </select>
                                <span
                                    class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Ubah Titik
                                Lokasi Peta</label>
                            <div class="flex gap-2 mb-3">
                                <input id="editMapSearchInput" type="text"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white"
                                    placeholder="Cari alamat baru...">
                                <button type="button" id="btnEditSearchMap"
                                    class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition">Cari</button>
                            </div>
                            <div id="editModalMap"
                                class="w-full h-[200px] rounded-lg border border-gray-300 bg-gray-100 relative"></div>
                            <input type="hidden" name="latitude" id="editInputLatitude" x-model="editForm.latitude">
                            <input type="hidden" name="longitude" id="editInputLongitude" x-model="editForm.longitude">
                        </div>

                        <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                            <button type="button" @click="isEditModalOpen = false"
                                class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">Batal</button>
                            <button type="submit"
                                class="rounded-lg bg-warning-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-warning-600">Update
                                Data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="isDeleteModalOpen" x-cloak style="display: none;"
                class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity p-4">
                <div @click.away="isDeleteModalOpen = false"
                    class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark text-center">
                    <div
                        class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-error-50 text-error-500 dark:bg-error-500/10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-2">Hapus Relawan?</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Apakah Anda yakin ingin menghapus relawan
                        bernama <span class="font-bold text-gray-800 dark:text-white" x-text="deleteForm.name"></span>
                        secara permanen?</p>

                    <div class="flex justify-center gap-3">
                        <button type="button" @click="isDeleteModalOpen = false"
                            class="w-full rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">Batal</button>
                        <form :action="'/admin/volunteers/' + deleteForm.id" method="POST" class="w-full m-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full rounded-lg bg-error-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-error-600 transition shadow-theme-xs">Ya,
                                Hapus!</button>
                        </form>
                    </div>
                </div>
            </div>


            <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex flex-col gap-4 px-5 mb-4 md:flex-row md:items-center md:justify-between sm:px-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Volunteer</h3>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative">
                            <button type="button" class="absolute -translate-y-1/2 left-4 top-1/2"><svg
                                    class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                        fill="" />
                                </svg></button>
                            <input type="text" x-model="search" placeholder="Cari nama/username..."
                                class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[300px]" />
                        </div>
                        <button type="button" @click="isModalOpen = true"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition shadow-theme-xs">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 4.16667V15.8333M4.16669 10H15.8334" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg> Add Data
                        </button>
                    </div>
                </div>

                <div class="overflow-hidden">
                    <div class="max-w-full px-5 overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-gray-200 border-y dark:border-gray-700">
                                    <th
                                        class="px-4 py-3 font-medium text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                        Relawan</th>
                                    <th
                                        class="px-4 py-3 font-medium text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                        Kontak</th>
                                    <th
                                        class="px-4 py-3 font-medium text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                        Kendaraan</th>
                                    <th
                                        class="px-4 py-3 font-medium text-gray-500 text-start text-theme-sm dark:text-gray-400 min-w-[200px]">
                                        Lokasi</th>
                                    <th
                                        class="px-4 py-3 font-medium text-gray-500 text-start text-theme-sm dark:text-gray-400">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 font-medium text-gray-500 text-end text-theme-sm dark:text-gray-400">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr x-show="paginatedItems.length === 0" x-cloak>
                                    <td colspan="6" class="py-6 text-center text-gray-500 dark:text-gray-400">Tidak ada
                                        data ditemukan.</td>
                                </tr>
                                <template x-for="v in paginatedItems" :key="v.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition">
                                        <td class="py-4 whitespace-nowrap px-4">
                                            <div class="flex items-center">
                                                <div class="shrink-0 w-10 h-10 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold text-sm shadow-sm"
                                                    x-text="v.name.charAt(0).toUpperCase()"></div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white"
                                                        x-text="v.name"></div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                                        x-text="'@' + v.username"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400" x-text="v.phone"></div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400" x-text="v.vehicle_type">
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                <template x-if="v.latitude !== null && v.longitude !== null">
                                                    <span>
                                                        <span class="block text-xs font-mono text-brand-500">Lat: <span
                                                                x-text="v.latitude"></span></span>
                                                        <span class="block text-xs font-mono text-brand-500">Lng: <span
                                                                x-text="v.longitude"></span></span>
                                                    </span>
                                                </template>
                                                <template x-if="v.latitude === null || v.longitude === null">
                                                    <span class="italic text-gray-400 text-xs">Belum ada lokasi
                                                        <br>(Menunggu app)</span>
                                                </template>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <template x-if="v.is_verified">
                                                <span
                                                    class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">Terverifikasi</span>
                                            </template>
                                            <template x-if="!v.is_verified">
                                                <span
                                                    class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">Belum
                                                    / Ditolak</span>
                                            </template>
                                        </td>

                                        <td class="px-4 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end space-x-3">

                                                <div class="flex space-x-2">
                                                    <template x-if="!v.is_verified">
                                                        <form :action="'/admin/volunteers/' + v.id + '/verify'"
                                                            method="POST" class="inline">
                                                            @csrf <button type="submit" title="Terima / Verifikasi"
                                                                class="text-success-600 hover:bg-success-50 border border-success-200 px-2 py-1.5 rounded-lg text-xs font-medium transition dark:border-success-500/20 dark:text-success-500"><svg
                                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg></button>
                                                        </form>
                                                    </template>

                                                    <template x-if="v.is_verified">
                                                        <form :action="'/admin/volunteers/' + v.id + '/reject'"
                                                            method="POST" class="inline">
                                                            @csrf <button type="submit" title="Tolak / Batal Verifikasi"
                                                                class="text-error-600 hover:bg-error-50 border border-error-200 px-2 py-1.5 rounded-lg text-xs font-medium transition dark:border-error-500/20 dark:text-error-500"><svg
                                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg></button>
                                                        </form>
                                                    </template>
                                                </div>

                                                <span class="h-5 w-px bg-gray-300 dark:bg-gray-700"></span>

                                                <button type="button" @click="openEditModal(v)" title="Edit Data"
                                                    class="text-gray-500 hover:text-warning-500 dark:text-gray-400 dark:hover:text-warning-400 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <button type="button" @click="openDeleteModal(v)" title="Hapus Data"
                                                    class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
                    <div class="flex items-center justify-between">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800"><span
                                class="hidden sm:inline">Previous</span></button>
                        <ul class="hidden items-center gap-0.5 sm:flex">
                            <template x-for="page in displayedPages" :key="page">
                                <li><button x-show="page !== '...'" @click="goToPage(page)"
                                        :class="currentPage === page ? 'bg-brand-500 text-white shadow-md' :
                                            'text-gray-700 hover:bg-brand-500/[0.08] hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500'"
                                        class="flex h-10 w-10 items-center justify-center rounded-lg text-theme-sm font-medium transition"
                                        x-text="page"></button><span x-show="page === '...'"
                                        class="flex h-10 w-10 items-center justify-center text-gray-500">...</span></li>
                            </template>
                        </ul>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''"
                            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800"><span
                                class="hidden sm:inline">Next</span></button>
                    </div>
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

        document.getElementById('btnSearchMap').addEventListener('click', function() {
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

        document.getElementById('btnEditSearchMap').addEventListener('click', function() {
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
            Alpine.data('volunteerTable', () => ({
                search: '',
                isModalOpen: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
                isEditModalOpen: {{ $errors->any() && old('_method') == 'PUT' ? 'true' : 'false' }},
                isDeleteModalOpen: false,

                editForm: {
                    id: '',
                    name: '',
                    username: '',
                    phone: '',
                    vehicle_type: '',
                    latitude: '',
                    longitude: ''
                },
                deleteForm: {
                    id: '',
                    name: ''
                },

                allItems: @json($mappedVolunteers),
                itemsPerPage: 10,
                currentPage: 1,

                openEditModal(volunteer) {
                    this.editForm = {
                        ...volunteer
                    };
                    this.editForm.latitude = volunteer.latitude || -6.200000;
                    this.editForm.longitude = volunteer.longitude || 106.816666;
                    this.isEditModalOpen = true;
                    setTimeout(() => {
                        initLeafletEditMap(this.editForm.latitude, this.editForm.longitude);
                    }, 200);
                },

                openDeleteModal(volunteer) {
                    this.deleteForm = {
                        id: volunteer.id,
                        name: volunteer.name
                    };
                    this.isDeleteModalOpen = true;
                },

                get filteredItems() {
                    if (this.search === '') return this.allItems;
                    return this.allItems.filter(item => item.name.toLowerCase().includes(this.search
                        .toLowerCase()) || item.username.toLowerCase().includes(this.search
                        .toLowerCase()));
                },
                get totalPages() {
                    return Math.ceil(this.filteredItems.length / this.itemsPerPage) || 1;
                },
                get paginatedItems() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    return this.filteredItems.slice(start, start + this.itemsPerPage);
                },
                get displayedPages() {
                    const range = [];
                    for (let i = 1; i <= this.totalPages; i++) {
                        if (i === 1 || i === this.totalPages || (i >= this.currentPage - 1 && i <=
                                this.currentPage + 1)) range.push(i);
                        else if (range[range.length - 1] !== '...') range.push('...');
                    }
                    return range;
                },
                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },
                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },
                goToPage(page) {
                    if (typeof page === 'number') this.currentPage = page;
                },

                init() {
                    this.$watch('search', () => this.currentPage = 1);
                    this.$watch('isModalOpen', (val) => {
                        if (val) setTimeout(() => initLeafletMap(), 200);
                    });
                    if (this.isModalOpen) setTimeout(() => initLeafletMap(), 200);
                }
            }));
        });
    </script>
@endsection
