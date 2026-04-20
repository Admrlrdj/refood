<div x-show="isEditModalOpen" x-cloak style="display: none;"
    class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity p-4">
    <div @click.away="isEditModalOpen = false"
        class="w-full max-w-3xl rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark max-h-[95vh] overflow-y-auto custom-scrollbar">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Makanan: <span x-text="editForm.name"
                    class="text-brand-500"></span></h3>
            <button type="button" @click="isEditModalOpen = false" class="text-gray-500 hover:text-gray-700"><svg
                    class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg></button>
        </div>

        <form :action="'/admin/foods/' + editForm.id" method="POST" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih
                        Restoran</label>
                    <select name="donor_id" x-model="editForm.donor_id" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-500 dark:border-gray-700 dark:text-white">
                        @foreach ($donors as $donor)
                            <option value="{{ $donor->_id }}">{{ $donor->restaurant_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status
                        Donasi</label>
                    <select name="status" x-model="editForm.status" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm focus:border-brand-500 dark:border-gray-700 dark:text-white">
                        <option value="available">Tersedia (Available)</option>
                        <option value="booked">Diambil Kurir (Booked)</option>
                        <option value="delivered">Selesai (Delivered)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                        Makanan</label>
                    <input type="text" name="name" x-model="editForm.name" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah
                        Porsi</label>
                    <input type="number" name="portion" x-model="editForm.portion" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Batas
                        Kadaluarsa</label>
                    <input type="datetime-local" name="expired_at" x-model="editForm.expired_at" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat
                        Penjemputan</label>
                    <input type="text" name="pickup_address" x-model="editForm.pickup_address" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi /
                    Catatan</label>
                <textarea name="description" x-model="editForm.description" rows="2" required
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white"></textarea>
            </div>

            <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Update Titik
                    Koordinat</label>
                <div class="flex gap-2 mb-3">
                    <input id="editMapSearchInput" type="text"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm dark:border-gray-700 dark:text-white"
                        placeholder="Cari alamat baru...">
                    <button type="button" id="btnEditSearchMap"
                        class="rounded-lg bg-brand-500 px-4 py-2 text-sm text-white hover:bg-brand-600 transition">Cari</button>
                </div>
                <div id="editModalMap" class="w-full h-[200px] rounded-lg border border-gray-300 bg-gray-100 relative">
                </div>
                <input type="hidden" name="latitude" id="editInputLatitude" x-model="editForm.latitude">
                <input type="hidden" name="longitude" id="editInputLongitude" x-model="editForm.longitude">
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                <button type="button" @click="isEditModalOpen = false"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                    class="rounded-lg bg-warning-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-warning-600 transition">Update
                    Makanan</button>
            </div>
        </form>
    </div>
</div>
