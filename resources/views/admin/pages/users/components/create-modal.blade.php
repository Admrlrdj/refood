<div x-show="isModalOpen" x-cloak style="display: none;"
    class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity p-4">
    <div @click.away="isModalOpen = false"
        class="w-full max-w-lg rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Tambah Admin Baru</h3>
            <button @click="isModalOpen = false"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"><svg class="w-6 h-6"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg></button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input type="text" name="name" required
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                    placeholder="Nama Admin">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                <input type="email" name="email" required
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                    placeholder="admin@domain.com">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input type="password" name="password" required minlength="6"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:text-white"
                    placeholder="Minimal 6 karakter">
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                <button type="button" @click="isModalOpen = false"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 transition">Batal</button>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition shadow-theme-xs">Simpan
                    Data</button>
            </div>
        </form>
    </div>
</div>
