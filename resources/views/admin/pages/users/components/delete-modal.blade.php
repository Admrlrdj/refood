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
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-2">Hapus Akun Admin?</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Apakah Anda yakin ingin menghapus admin bernama <span
                class="font-bold text-gray-800 dark:text-white" x-text="deleteForm.name"></span> secara permanen?</p>

        <div class="flex justify-center gap-3">
            <button type="button" @click="isDeleteModalOpen = false"
                class="w-full rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
            <form :action="'/admin/users/' + deleteForm.id" method="POST" class="w-full m-0">
                @csrf @method('DELETE')
                <button type="submit"
                    class="w-full rounded-lg bg-error-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-error-600 transition shadow-theme-xs">Ya,
                    Hapus!</button>
            </form>
        </div>
    </div>
</div>
