@props([
    'course',
    'showMatch' => true,
])

<a href="{{ route('course.detail', ['id' => $course['id'], 'match' => $course['match'] ?? null]) }}"
   class="course-card group flex h-full flex-col"
   aria-label="View {{ $course['title'] }} course detail">

    {{-- Thumbnail --}}
    <div class="relative h-36 overflow-hidden rounded-[24px] bg-slate-950">

        <img src="{{ asset($course['thumbnail']) }}"
             alt="{{ $course['title'] }} thumbnail"
             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">

        {{-- Level Badge --}}
        <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-extrabold text-indigo-700">
            {{ $course['level'] ?? 'Intermediate' }}
        </span> 


    </div>

    {{-- Content --}}
    <div class="mt-4 flex flex-1 flex-col">

        {{-- Title --}}
        <h3 class="line-clamp-2 min-h-[48px] text-base font-black leading-snug text-slate-950">
            {{ $course['title'] }}
        </h3>

        {{-- Footer --}}
       <div class="mt-auto pt-4">

            <div class="flex items-center justify-between gap-3">

                <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">

                    <x-icon name="star"
                            class="h-4 w-4 text-amber-400" />    

                    <span>
                        {{ $course['platform'] }}
                    </span>

                </div>

                @if($showMatch)
                    <span class="shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-black text-indigo-700">
                        {{ $course['match'] }}% Match
                    </span>
                @endif

            </div>

        </div>

    </div>

</a>    