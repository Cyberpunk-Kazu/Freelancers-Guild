<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Guild hall · {{ config('app.name', 'Freelancers Guild') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f8f3ed] text-[#132b2d]">
        <main class="mx-auto flex min-h-screen w-full max-w-3xl items-center justify-center px-6 py-12">
            <section class="w-full rounded-2xl border border-[#ddd4cb] bg-white p-8 shadow-sm sm:p-12">
                <div class="flex items-center gap-4">
                    <span class="grid size-10 place-items-center rounded-[5px] bg-[#d9683c] text-lg font-bold text-white">N</span>
                    <span class="guild-script text-2xl italic">Freelancers Guild</span>
                </div>
                <h1 class="guild-display mt-12 text-5xl tracking-[-.03em]">Welcome to the hall.</h1>
                <p class="mt-3 text-lg text-[#817b76]">Your guild record is ready, {{ auth()->user()->name }}.</p>
                <form class="mt-10" action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="rounded-[9px] bg-[#223b3d] px-6 py-4 font-bold text-white transition hover:bg-[#172e30]" type="submit">Sign Out</button>
                </form>
            </section>
        </main>
    </body>
</html>
