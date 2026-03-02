@extends('layouts.app')

@section('content')
    @php
        $mappedUsers = $users->map(function ($u) {
            return [
                'id' => (string) $u->_id,
                'name' => $u->name,
                'email' => $u->email,
            ];
        });
    @endphp

    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">Admin Users</h2>
            <nav>
                <ol class="flex flex-wrap items-center gap-1.5">
                    <li class="flex items-center gap-1.5"><a href="{{ route('admin.dashboard') }}"
                            class="text-sm text-gray-500 hover:text-brand-500 dark:text-gray-400">Dashboard</a></li>
                    <li class="flex items-center gap-1.5"><span class="text-gray-500 dark:text-gray-400">/</span><span
                            class="text-sm font-medium text-gray-800 dark:text-white/90">Users</span></li>
                </ol>
            </nav>
        </div>

        @if (session('success'))
            <div x-data="{ showAlert: true }" x-show="showAlert" x-transition
                class="mb-5 flex w-full items-center border-l-6 border-success-500 bg-success-50 px-7 py-4 shadow-theme-md dark:border-success-500 dark:bg-gray-dark rounded-r-lg">
                <div class="mr-5 flex h-9 w-full max-w-[36px] items-center justify-center rounded-lg bg-success-500"><svg
                        width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.2984 0.826822L15.2868 0.811827L15.2741 0.797751C14.9173 0.401867 14.3238 0.400754 13.9657 0.794406L5.91888 9.45376L2.05667 5.2868C1.69856 4.89287 1.10487 4.89389 0.747996 5.28987C0.417335 5.65675 0.417335 6.22337 0.747996 6.59026L0.747959 6.59029L0.752701 6.59541L4.86742 11.0348C5.14445 11.3405 5.52858 11.5 5.89581 11.5C6.29242 11.5 6.65178 11.3355 6.92401 11.035L15.2162 2.11161C15.5833 1.74452 15.576 1.18615 15.2984 0.826822Z"
                            fill="white" stroke="white"></path>
                    </svg></div>
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
                class="mb-5 flex w-full items-center border-l-6 border-error-500 bg-error-50 px-7 py-4 shadow-theme-md dark:border-error-500 dark:bg-gray-dark rounded-r-lg">

                <div class="mr-5 flex h-9 w-full max-w-[36px] items-center justify-center rounded-lg bg-error-500">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L7 5.58579L1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L5.58579 7L0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L7 8.41421L12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L8.41421 7L13.7071 1.70711Z"
                            fill="white"></path>
                    </svg>
                </div>

                <div class="w-full">
                    <h5 class="text-lg font-semibold text-error-500 mb-1">Terjadi Kesalahan!</h5>
                    <ul class="list-disc list-inside text-sm text-gray-500 dark:text-gray-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <button @click="showAlert = false" type="button"
                    class="ml-auto p-1.5 rounded-lg text-gray-500 hover:bg-error-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        <div x-data="userTable()">

            @include('admin.pages.users.components.create-modal')
            @include('admin.pages.users.components.edit-modal')
            @include('admin.pages.users.components.delete-modal')

            <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex flex-col gap-4 px-5 mb-4 md:flex-row md:items-center md:justify-between sm:px-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Admin</h3>
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
                            <input type="text" x-model="search" placeholder="Cari nama/email..."
                                class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[300px]" />
                        </div>
                        <button type="button" @click="isModalOpen = true"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition shadow-theme-xs"><svg
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 4.16667V15.8333M4.16669 10H15.8334" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg> Add Data</button>
                    </div>
                </div>

                <div class="overflow-hidden">
                    <div class="max-w-full px-5 overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-gray-200 border-y dark:border-gray-700">
                                    <th
                                        class="px-4 py-3 font-medium text-gray-500 text-start text-theme-sm dark:text-gray-400 w-1/2">
                                        Nama Lengkap</th>
                                    <th
                                        class="px-4 py-3 font-medium text-gray-500 text-start text-theme-sm dark:text-gray-400 w-1/3">
                                        Email</th>
                                    <th
                                        class="px-4 py-3 font-medium text-gray-500 text-end text-theme-sm dark:text-gray-400">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr x-show="paginatedItems.length === 0" x-cloak>
                                    <td colspan="3" class="py-6 text-center text-gray-500 dark:text-gray-400">Tidak ada
                                        data ditemukan.</td>
                                </tr>
                                <template x-for="u in paginatedItems" :key="u.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition">
                                        <td class="py-4 whitespace-nowrap px-4">
                                            <div class="flex items-center">
                                                <div class="shrink-0 w-10 h-10 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold text-sm shadow-sm"
                                                    x-text="u.name.charAt(0).toUpperCase()"></div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white"
                                                        x-text="u.name"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400" x-text="u.email"></div>
                                        </td>

                                        <td class="px-4 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end space-x-3">
                                                <button type="button" @click="openEditModal(u)" title="Edit Data"
                                                    class="text-gray-500 hover:text-warning-500 dark:text-gray-400 dark:hover:text-warning-400 transition"><svg
                                                        class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg></button>
                                                <button type="button" @click="openDeleteModal(u)" title="Hapus Data"
                                                    class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500 transition"><svg
                                                        class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.8"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg></button>
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('userTable', () => ({
                search: '',
                isModalOpen: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
                isEditModalOpen: {{ $errors->any() && old('_method') == 'PUT' ? 'true' : 'false' }},
                isDeleteModalOpen: false,

                editForm: {
                    id: '',
                    name: '',
                    email: ''
                },
                deleteForm: {
                    id: '',
                    name: ''
                },

                allItems: @json($mappedUsers),
                itemsPerPage: 10,
                currentPage: 1,

                openEditModal(user) {
                    this.editForm = {
                        ...user
                    };
                    this.isEditModalOpen = true;
                },
                openDeleteModal(user) {
                    this.deleteForm = {
                        id: user.id,
                        name: user.name
                    };
                    this.isDeleteModalOpen = true;
                },

                get filteredItems() {
                    if (this.search === '') return this.allItems;
                    return this.allItems.filter(item =>
                        item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.email.toLowerCase().includes(this.search.toLowerCase())
                    );
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
                }
            }));
        });
    </script>
@endsection
