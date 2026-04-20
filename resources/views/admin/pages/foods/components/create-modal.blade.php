<div x-show="isModalOpen" x-cloak style="display: none;"
    class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity p-4">
    <div @click.away="isModalOpen = false"
        class="w-full max-w-3xl rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark max-h-[95vh] overflow-y-auto custom-scrollbar">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Tambah Donasi Makanan</h3>
            <button type="button" @click="isModalOpen = false"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400"><svg class="w-6 h-6" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg></button>
        </div>

        <form action="{{ route('admin.foods.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Restoran /
                    Donatur <span class="text-error-500">*</span></label>
                <select name="donor_id" required
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">
                    <option value="">-- Pilih Donatur (Terverifikasi) --</option>
                    @foreach ($donors as $donor)
                        <option value="{{ $donor->_id }}">{{ $donor->restaurant_name }} ({{ $donor->name }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Makanan <span
                            class="text-error-500">*</span></label>
                    <input type="text" name="name" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white"
                        placeholder="Contoh: Nasi Kotak Ayam Bakar">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Porsi <span
                            class="text-error-500">*</span></label>
                    <input type="number" name="portion" min="1" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white"
                        placeholder="Contoh: 20">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Batas Kadaluarsa
                        (Expired) <span class="text-error-500">*</span></label>
                    <input type="datetime-local" name="expired_at" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Penjemputan
                        <span class="text-error-500">*</span></label>
                    <input type="text" name="pickup_address" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white"
                        placeholder="Detail jalan, patokan, dll.">
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi / Catatan
                    Tambahan <span class="text-error-500">*</span></label>
                <textarea name="description" rows="2" required
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white"
                    placeholder="Contoh: Harus diambil sebelum jam 5 sore."></textarea>
            </div>

            <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Titik Koordinat
                    Penjemputan <span class="text-error-500">*</span></label>
                <div class="flex gap-2 mb-3">
                    <input id="mapSearchInput" type="text"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm focus:border-brand-500 dark:border-gray-700 dark:text-white"
                        placeholder="Cari alamat...">
                    <button type="button" id="btnSearchMap"
                        class="rounded-lg bg-brand-500 px-4 py-2 text-sm text-white hover:bg-brand-600 transition">Cari</button>
                </div>
                <div id="modalMap" class="w-full h-[200px] rounded-lg border border-gray-300 bg-gray-100 relative">
                </div>
                <input type="hidden" name="latitude" id="inputLatitude" required>
                <input type="hidden" name="longitude" id="inputLongitude" required>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                <button type="button" @click="isModalOpen = false"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">Simpan
                    Makanan</button>
            </div>
        </form>
    </div>
</div>
