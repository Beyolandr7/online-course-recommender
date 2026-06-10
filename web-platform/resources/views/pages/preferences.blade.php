@extends('layouts.dashboard')

@section('title', 'Preference Form | CourseRecommend')

@section('page-content')
<div class="mx-auto max-w-5xl pt-16 lg:pt-0">
    <section class="soft-card relative overflow-hidden p-7 md:p-10">

        <img src="{{ asset('assets/backgrounds/bg-doodle-stars.svg') }}"
             alt=""
             aria-hidden="true"
             class="absolute right-0 top-0 w-96 opacity-35">

        <img src="{{ asset('assets/backgrounds/bg-yellow-blob.svg') }}"
             alt=""
             aria-hidden="true"
             class="absolute -bottom-16 -left-12 w-80 opacity-50">

        <div class="relative z-10">

            <div class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-4 py-2 text-sm font-black text-indigo-700">
                <x-icon name="target" class="h-4 w-4" />
                Personalized input
            </div>

            <h1 class="mt-5 max-w-2xl text-4xl font-black leading-tight text-slate-950 md:text-5xl">
                Build your personalized learning path.
            </h1>

            <p class="mt-4 max-w-2xl text-base font-medium leading-relaxed text-slate-500">
                Isi data berikut agar sistem dapat memberi rekomendasi course berdasarkan jurusan, jenjang kemahiran awal, minat, dan target kamu.
            </p>

            <form action="{{ route('preferences.store') }}" method="POST" class="mt-9 space-y-6" novalidate>
                @csrf

                <div>
                    <label for="major" class="form-label">Jurusan</label>
                    <input id="major" name="major" type="text" value="{{ old('major') }}" placeholder="Contoh: Teknik Informatika" class="input-modern mt-2" autocomplete="organization-title">
                    @error('major') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="initial_level" class="form-label">Jenjang Kemahiran Awal</label>

                    <input 
                        id="initial_level" 
                        name="initial_level" 
                        type="text"
                        list="level_options"
                        value="{{ old('initial_level') }}"
                        placeholder="Pilih atau ketik level"
                        class="input-modern mt-2"
                    >

                    @error('initial_level') 
                        <p class="form-error">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="target_level" class="form-label">Target Kemahiran</label>

                    <input 
                        id="target_level" 
                        name="target_level" 
                        type="text"
                        list="level_options"
                        value="{{ old('target_level') }}"
                        placeholder="Pilih atau ketik target level"
                        class="input-modern mt-2"
                    >

                    @error('target_level') 
                        <p class="form-error">{{ $message }}</p> 
                    @enderror
                </div>

                <datalist id="level_options">
                    <option value="Beginner">
                    <option value="Intermediate">
                    <option value="Advanced">
                </datalist>
            </div>

                <div>
                    <label for="interest" class="form-label">Minat</label>
                    <textarea id="interest" name="interest" rows="5" placeholder="Contoh: AI, Machine Learning, Data Science" class="input-modern mt-2 resize-none">{{ old('interest') }}</textarea>
                    @error('interest') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <button class="primary-btn w-full py-4 text-base">
                    Generate Recommendation
                </button>
            </form>

        </div>
    </section>
</div>
@endsection