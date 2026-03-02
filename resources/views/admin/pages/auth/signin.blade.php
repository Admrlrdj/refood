@extends('layouts.fullscreen-layout')

@section('content')
    <div class="relative z-1 bg-white p-6 sm:p-0 dark:bg-gray-900">
        <div class="relative flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row dark:bg-gray-900">
            <!-- Form -->
            <div class="flex w-full flex-1 flex-col lg:w-1/2">
                <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                    <div>
                        <div class="mb-5 sm:mb-8">
                            <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                                Admin Sign In
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Enter your email and password to sign in!
                            </p>
                        </div>
                        <div>
                            <form method="POST" action="{{ url('/admin/login') }}">
                                @csrf @if ($errors->any())
                                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <div class="mb-5">
                                    <label class="mb-2.5 block font-medium text-gray-900 dark:text-white">Email</label>
                                    <div class="relative">
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            placeholder="Masukkan email admin" required
                                            class="w-full rounded-lg border border-gray-300 bg-transparent py-4 pl-6 pr-10 text-gray-900 outline-none focus:border-brand-500 focus-visible:shadow-none dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-brand-500" />
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="mb-2.5 block font-medium text-gray-900 dark:text-white">Password</label>
                                    <div class="relative">
                                        <input type="password" name="password" placeholder="6+ Karakter" required
                                            class="w-full rounded-lg border border-gray-300 bg-transparent py-4 pl-6 pr-10 text-gray-900 outline-none focus:border-brand-500 focus-visible:shadow-none dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-brand-500" />
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <button type="submit"
                                        class="w-full cursor-pointer rounded-lg border border-brand-500 bg-brand-500 p-4 font-medium text-white transition hover:bg-opacity-90">
                                        Sign In
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
