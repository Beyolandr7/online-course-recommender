@extends('layouts.dashboard')

@section('title', 'My Profile | CourseRecommend')

@section('page-content')
@php
    $user = $user ?? [
        'name'    => 'Guest',
        'avatar'  => 'null',
        'degree'  => '-',
        'major'   => '-',
        'level'   => '-',
        'details' => [],
    ];

    $stats        = $stats        ?? [];    
    $skills       = $skills       ?? [];
    $quickActions = $quickActions ?? [];

    $initials = collect(explode(' ', $user['name']))
        ->take(2)
        ->map(fn($w) => strtoupper($w[0]))
        ->implode('');
@endphp

<div class="mx-auto max-w-5xl px-1 pb-10"
     x-data="{
         profile: $persist({
             name: '{{ $user['name'] }}',
             degree: '{{ $user['degree'] }}',
             major: '{{ $user['major'] }}',
             level: '{{ $user['level'] }}',
             interest: 'Python, Data Science, Machine Learning',
             target: 'Advanced'
         }),
         showEditModal: false,
         editMode: 'all',
         tempProfile: {},
         openEditModal(mode = 'all') {
             this.editMode = mode;
             this.tempProfile = { ...this.profile };
             this.showEditModal = true;
         },
         saveProfile() {
             this.profile = { ...this.tempProfile };
             this.showEditModal = false;
         },
         get initials() {
             return this.profile.name.split(' ').slice(0, 2).map(w => w ? w[0].toUpperCase() : '').join('');
         }
     }">

    {{-- Back --}}
    <a href="{{ route('home') }}"
       class="mb-6 inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 transition hover:text-indigo-600">
        <x-icon name="arrow-left" class="h-4 w-4" />
        Back to home
    </a>

    <h1 class="mb-6 text-3xl font-black tracking-tight text-slate-950">My profile</h1>

    <div class="grid gap-5 lg:grid-cols-[1fr_300px]">

        {{-- LEFT --}}
        <div class="space-y-5">

            {{-- Profile card --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6">
                <div class="flex items-start gap-4">

                    {{-- Avatar --}}
                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-full border-4 border-indigo-100 bg-indigo-50
                                flex items-center justify-center text-xl font-bold text-indigo-600">
                        @if($user['avatar'])
                            <img src="{{ asset($user['avatar']) }}" :alt="profile.name" class="h-full w-full object-cover">
                        @else
                            <span x-text="initials"></span>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        {{-- Name--}}
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-xl font-black text-slate-950" x-text="profile.name">{{ $user['name'] }}</h2>
                        </div>

                        <p class="mt-1 text-sm font-semibold text-slate-400 flex items-center gap-1">
                            <span x-text="profile.degree">{{ $user['degree'] }}</span>
                            <span>&bull;</span>
                            <span x-text="profile.major">{{ $user['major'] }}</span>
                        </p>

                        <span class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-indigo-100
                                     bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700">
                            <x-icon name="medal" class="h-3.5 w-3.5" />
                            <span x-text="profile.level">{{ $user['level'] }}</span>
                        </span>
                    </div>
                </div>

                {{-- Detail rows --}}
                @if(count($user['details']) > 0)
                    <ul class="mt-5 space-y-3">
                        @foreach ($user['details'] as $detail)
                            @php
                                $icon  = $detail['icon']  ?? 'info-circle';
                                $label = $detail['label'] ?? '';
                                $value = $detail['value'] ?? '-';
                            @endphp
                            <li class="flex items-start gap-3 text-sm">
                                <span class="mt-0.5 grid h-7 w-7 shrink-0 place-items-center rounded-xl bg-indigo-50 text-indigo-500">
                                    <x-icon name="{{ $icon }}" class="h-4 w-4" />
                                </span>
                                <span class="w-32 shrink-0 font-semibold text-slate-400">{{ $label }}</span>
                                <span class="font-semibold text-slate-800"
                                      @if($label === 'Jurusan') x-text="profile.major" @endif
                                      @if($label === 'Jenjang') x-text="profile.degree" @endif
                                      @if($label === 'Kemahiran Asal') x-text="profile.level" @endif
                                      @if($label === 'Minat') x-text="profile.interest" @endif
                                      @if($label === 'Target') x-text="profile.target" @endif
                                >{{ $value }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

               {{-- Banner --}}
                <div class="mt-6 flex items-center justify-between gap-3 rounded-[20px] bg-indigo-50/70 px-5 py-4">
                    <div>
                        <p class="text-sm font-black text-slate-800">Keep learning, keep growing.</p>
                        <p class="mt-0.5 text-xs font-semibold text-slate-500">Your future self will thank you.</p>
                    </div>
                    <x-icon name="sparkle" class="h-8 w-8 text-indigo-400 shrink-0" />
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6">
                <h3 class="mb-4 text-sm font-black text-slate-950">Quick actions</h3>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($quickActions as $action)
                        @php
                            $actionIcon  = $action['icon']  ?? 'bolt';
                            $actionLabel = $action['label'] ?? '';
                        @endphp
                        <button type="button"
                           @click="openEditModal('{{ $actionLabel === 'Edit Interests'? 'interests': ($actionLabel === 'Update Goal' ? 'goal' : 'all') }}')"
                            class="flex flex-col items-center gap-2 rounded-2xl border border-slate-100 bg-white
                                   px-3 py-4 text-center text-xs font-bold text-slate-600 shadow-sm
                                   transition hover:bg-indigo-50 hover:text-indigo-700">
                            <span class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-50 text-indigo-500">
                                <x-icon name="{{ $actionIcon }}" class="h-4 w-4" />
                            </span>
                            {{ $actionLabel }}
                        </button>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="space-y-5">

            {{-- Stats --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6">
                <h3 class="mb-4 text-sm font-black text-slate-950">Your stats</h3>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($stats as $stat)
                        @php
                            $statValue = $stat['value'] ?? '-';
                            $statLabel = $stat['label'] ?? '';
                        @endphp
                        <div class="rounded-[16px] bg-indigo-50/60 p-3 text-center">
                            <p class="text-xl font-black text-slate-950">{{ $statValue }}</p>
                            <p class="mt-1 text-[10px] font-bold leading-tight text-slate-400">{{ $statLabel }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Skills --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6">
                <h3 class="mb-5 text-sm font-black text-slate-950">In Progress Skills</h3>
                <div class="space-y-4">
                    @foreach ($skills as $skill)
                        @php
                            $skillName    = $skill['name']    ?? '';
                            $skillPercent = $skill['percent'] ?? 0;
                        @endphp
                        <div>
                            <div class="mb-1.5 flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-700">{{ $skillName }}</span>
                                <span class="text-sm font-bold text-slate-500">{{ $skillPercent }}%</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-indigo-50">
                                <div class="h-full rounded-full bg-indigo-600 transition-all duration-500"
                                     style="width: {{ $skillPercent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('my-learning', ['tab' => 'In Progress']) }}"
                   class="mt-6 block text-center w-full rounded-xl border border-slate-200 py-2 text-sm font-semibold
                          text-slate-500 transition hover:bg-slate-50">
                    View full progress
                </a>
            </div>

        </div>
    </div>

    {{-- Edit Profile Modal --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" 
         x-show="showEditModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
         
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showEditModal = false"></div>
        
        {{-- Dialog --}}
        <div class="relative w-full max-w-lg rounded-3xl border border-white/20 bg-white/95 p-6 shadow-2xl backdrop-blur-xl transition-all"
             x-show="showEditModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
             
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 class="font-[Sora,sans-serif] text-lg font-extrabold text-slate-950"
                    x-text="editMode === 'interests' ? 'Edit Interests': (editMode === 'goal'? 'Update Goal': 'Edit Profile')">
                    Edit Profile
                </h3>
                <button type="button" @click="showEditModal = false" class="text-slate-400 transition hover:text-slate-600" aria-label="Close">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="saveProfile()" class="mt-4 space-y-4">
                <div x-show="editMode === 'all'">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama</label>
                    <input type="text" x-model="tempProfile.name" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                
                <div class="grid grid-cols-2 gap-4" x-show="editMode === 'all'">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Jenjang</label>
                        <input type="text" x-model="tempProfile.degree" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Jurusan</label>
                        <input type="text" x-model="tempProfile.major" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4" x-show="editMode === 'all' || editMode === 'goal'">
                    <div x-show="editMode === 'all'">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Kemahiran Asal</label>
                        <input type="text" x-model="tempProfile.level" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>
                    <div x-show="editMode === 'all' || editMode === 'goal'" :class="editMode === 'goal' ? 'col-span-2' : ''">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Target Kemahiran</label>
                        <input type="text" x-model="tempProfile.target" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>
                </div>
                
                <div x-show="editMode === 'all' || editMode === 'interests'">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Minat</label>
                    <textarea x-model="tempProfile.interest" rows="3" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 resize-none"></textarea>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="showEditModal = false" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-500 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-md shadow-indigo-100 transition hover:bg-indigo-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection