@extends('layouts.dashboard')

@section('title', 'Sign Up | CourseRecommend')

@section('page-content')
<div class="mx-auto flex min-h-[80vh] max-w-5xl items-center justify-center pt-16 lg:pt-0">
    <section class="soft-card relative w-full max-w-xl overflow-hidden p-7 md:p-10">

        <img src="{{ asset('assets/backgrounds/bg-doodle-stars.svg') }}"
             alt=""
             aria-hidden="true"
             class="absolute right-0 top-0 w-80 opacity-30">

        <img src="{{ asset('assets/backgrounds/bg-yellow-blob.svg') }}"
             alt=""
             aria-hidden="true"
             class="absolute -bottom-20 -left-16 w-72 opacity-50">

        <div class="relative z-10">

             <a href="{{ url()->previous() }}" class="mb-5 inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-700">
            <x-icon name="arrow" class="h-4 w-4 rotate-180" /> Back
            </a>

            <h1 class="mt-5 text-4xl font-black leading-tight text-slate-950 md:text-5xl">
                Create your account.
            </h1>

            <p class="mt-4 text-base font-medium leading-relaxed text-slate-500">
                Daftar untuk menyimpan preferensi dan mendapatkan rekomendasi course yang lebih personal.
            </p>

            <form action="{{ route('register') }}" method="POST" class="mt-9 space-y-6">
                @csrf

                <div>
                    <label for="name" class="form-label">Nama</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama anda"
                        class="input-modern mt-2"
                        autocomplete="name"
                        required
                    >
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="Contoh: user@email.com"
                        class="input-modern mt-2"
                        autocomplete="email"
                        required
                    >
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="form-label">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Minimal 8 karakter"
                        class="input-modern mt-2"
                        autocomplete="new-password"
                        required
                    >
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="Ulangi password"
                        class="input-modern mt-2"
                        autocomplete="new-password"
                        required
                    >
                    @error('password_confirmation')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="primary-btn w-full py-4 text-base">
                    Sign Up
                </button>
            </form>

            <p class="mt-7 text-center text-sm font-medium text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-black text-indigo-700 hover:text-indigo-900">
                    Sign In
                </a>
            </p>

        </div>
    </section>
</div>
@endsection