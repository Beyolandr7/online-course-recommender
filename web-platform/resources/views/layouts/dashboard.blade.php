<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CourseRecommend' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-app text-slate-950 antialiased">
    <div class="min-h-screen lg:flex">
        <x-sidebar />

        <main class="min-w-0 flex-1 px-4 py-6 sm:px-6 lg:px-8 pb-20 lg:pb-8">
            <div class="mx-auto w-full max-w-[1360px]">
                @yield('page-content')
            </div>
        </main>
    </div>

    {{-- Mobile Bottom Navigation --}}
    @php
    $bottomNavs = [
        ['label' => 'Home', 'route' => 'home', 'icon' => 'home'],
        ['label' => 'Explore', 'route' => 'explore', 'icon' => 'search'],
        ['label' => 'Roadmap', 'route' => 'roadmap', 'icon' => 'roadmap'],
        ['label' => 'Learning', 'route' => 'my-learning', 'icon' => 'book'],
        ['label' => 'Profile', 'route' => 'profile', 'icon' => 'user'],
    ];
    @endphp
    <nav class="fixed bottom-0 left-0 right-0 z-50 flex items-center justify-around border-t border-indigo-100 bg-white/90 pb-4 pt-2.5 backdrop-blur-xl lg:hidden shadow-[-4px_-4px_24px_rgba(79,70,229,0.06)]">
        @foreach ($bottomNavs as $nav)
            @php
                $active = request()->routeIs($nav['route']);
            @endphp
            <a href="{{ route($nav['route']) }}" 
               class="flex flex-col items-center gap-1 text-[10px] font-bold transition-all duration-300 {{ $active ? 'text-indigo-600 scale-105' : 'text-slate-400 hover:text-slate-600' }}">
                <x-icon name="{{ $nav['icon'] }}" class="h-5 w-5 {{ $active ? 'stroke-[2.2px]' : 'stroke-[1.8px]' }}" />
                <span>{{ $nav['label'] }}</span>
            </a>
        @endforeach
    </nav>
</body>
</html>