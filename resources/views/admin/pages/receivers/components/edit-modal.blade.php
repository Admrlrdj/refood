<div x-show="isEditModalOpen" x-cloak style="display: none;"
    class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity p-4">
    <div @click.away="isEditModalOpen = false"
        class="w-full max-w-2xl rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark max-h-[95vh] overflow-y-auto custom-scrollbar">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Yayasan: <span
                    x-text="editForm.foundation_name" class="text-brand-500"></span></h3>
            <button @click="isEditModalOpen = false"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"><svg
                    class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg></button>
        </div>

        <form :action="'/admin/receivers/' + editForm.id" method="POST" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap
                        (Pengurus)</label>
                    <input type="text" name="name" x-model="editForm.name" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">No. HP /
                        WhatsApp</label>
                    <input type="text" name="phone" x-model="editForm.phone" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat
                        Email</label>
                    <input type="email" name="email" x-model="editForm.email" required
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Password
                        Baru</label>
                    <input type="password" name="password" minlength="6"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                        placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Yayasan /
                    Panti</label>
                <input type="text" name="foundation_name" x-model="editForm.foundation_name" required
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white">
            </div>

            <div class="pt-2 border-t border-gray-100 dark:border-gray-800">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Ubah Titik Lokasi
                    Peta</label>
                <div class="flex gap-2 mb-3">
                    <input id="editMapSearchInput" type="text"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white"
                        placeholder="Cari alamat baru...">
                    <button type="button" id="btnEditSearchMap"
                        class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition">Cari</button>
                </div>
                <div id="editModalMap" class="w-full h-[200px] rounded-lg border border-gray-300 bg-gray-100 relative">
                </div>
                <input type="hidden" name="latitude" id="editInputLatitude" x-model="editForm.latitude">
                <input type="hidden" name="longitude" id="editInputLongitude" x-model="editForm.longitude">
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                <button type="button" @click="isEditModalOpen = false"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 transition">Batal</button>
                <button type="submit"
                    class="rounded-lg bg-warning-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-warning-600 transition">Update
                    Data</button>
            </div>
        </form>
    </div>
</div>
