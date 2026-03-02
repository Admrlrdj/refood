<div x-show="isModalOpen" x-cloak style="display: none;"
    class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity p-4">
    <div @click.away="isModalOpen = false"
        class="w-full max-w-2xl rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark max-h-[95vh] overflow-y-auto custom-scrollbar">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Tambah Relawan Baru</h3>
            <button @click="isModalOpen = false"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.volunteers.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                        Lengkap</label>
                    <input type="text" name="name" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        placeholder="Masukkan nama">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">No. HP /
                        WhatsApp</label>
                    <input type="text" name="phone" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        placeholder="08123456789">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                    <input type="text" name="username" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        placeholder="budi_kurir">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" name="password" required minlength="6"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        placeholder="Minimal 6 karakter">
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Kendaraan</label>
                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                    <select name="vehicle_type" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                        @change="isOptionSelected = true">
                        <option value="" disabled selected
                            class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Pilih Kendaraan</option>
                        <option value="Motor" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Motor</option>
                        <option value="Mobil" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Mobil</option>
                        <option value="Sepeda" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Sepeda</option>
                    </select>
                    <span
                        class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
            </div>

            <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Titik Lokasi Relawan
                    (OpenStreetMap)</label>
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
