@php
$navItems = [
    ['label' => 'Home', 'route' => 'home', 'icon' => 'home'],
    ['label' => 'Explore', 'route' => 'explore', 'icon' => 'search'],
    ['label' => 'Preference Form', 'route' => 'preferences.create', 'icon' => 'target'],
    ['label' => 'Recommendations', 'route' => 'explore', 'icon' => 'sparkle', 'query' => ['sort' => 'recommended'],'hash' => '#course-list'],
    ['label' => 'Roadmap', 'route' => 'roadmap', 'icon' => 'roadmap'],
    ['label' => 'My Learning', 'route' => 'my-learning', 'icon' => 'book'],
    ['label' => 'Profile', 'route' => 'profile', 'icon' => 'user'],
];
@endphp

<aside class="fixed inset-y-0 left-0 z-40 hidden w-[230px] border-r border-indigo-100 bg-white/80 px-4 py-5 backdrop-blur-xl lg:block">
    <a href="{{ route('home') }}" class="flex items-center gap-3">
        <div class="grid h-10 w-10 place-items-center rounded-2xl bg-indigo-600 text-sm font-black text-white shadow-lg shadow-indigo-100">
            CR
        </div>

        <div class="leading-tight">
            <p class="text-base font-black tracking-tight text-slate-950">CourseRecommend</p>
            <p class="text-xs font-bold text-slate-400">AI Learning Platform</p>
        </div>
    </a>

    <nav class="mt-10 space-y-1.5">
    @foreach ($navItems as $item)
        @php
            $query = $item['query'] ?? [];
            $hash = $item['hash'] ?? '';
            $href = route($item['route'], $query) . $hash;

            if ($item['label'] === 'Explore') {
                $active = request()->routeIs('explore') && request('sort') !== 'recommended';
            } elseif ($item['label'] === 'Recommendations') {
                $active = request()->routeIs('explore') && request('sort') === 'recommended';
            } else {
                $active = request()->routeIs($item['route']);
            }
        @endphp

        <a href="{{ $href }}"
           class="flex items-center gap-3 rounded-2xl px-3.5 py-3 text-sm font-extrabold transition
           {{ $active ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-700' }}">
            <x-icon name="{{ $item['icon'] }}" class="h-5 w-5" />
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
</aside>

<header class="fixed left-0 right-0 top-0 z-40 border-b border-indigo-100 bg-white/85 px-4 py-3 backdrop-blur-xl lg:hidden">
    <div class="flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-600 text-xs font-black text-white">
                CRz
            </div>
            <div>
                <p class="text-sm font-black text-slate-950">CourseRecommend</p>
                <p class="text-[11px] font-bold text-slate-400">AI Learning Platform</p>
            </div>
        </a>
    </div>
</header>

<div class="hidden lg:block lg:w-[230px] lg:shrink-0"></div>