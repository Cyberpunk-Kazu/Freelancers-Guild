<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Verify email · {{ config('app.name', 'Freelancers Guild') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,500&family=DM+Sans:wght@400;500;600;700&family=Space+Mono&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f8f3ed] text-[#132b2d]">
        <main class="min-h-screen lg:grid lg:grid-cols-2">
            <section class="guild-panel relative flex min-h-[460px] flex-col overflow-hidden px-8 py-8 sm:px-16 lg:min-h-screen lg:px-16 lg:py-12">
                <div class="relative z-10 flex items-center gap-4">
                    <span class="grid size-10 place-items-center rounded-[5px] bg-[#d9683c] text-lg font-bold text-white">N</span>
                    <span class="guild-script text-2xl italic text-[#f8f3ed]">Freelancers Guild</span>
                </div>
                <div class="relative z-10 my-auto max-w-xl py-16">
                    <h1 class="guild-display text-5xl leading-[.9] tracking-[-.04em] text-[#f8f3ed] sm:text-6xl lg:text-[4rem]">
                        Prove your identity.
                        <span class="block text-[#a8c7c7] italic">Claim your place.</span>
                    </h1>
                    <p class="mt-10 max-w-lg text-lg leading-8 text-[#72b4c4] sm:text-xl">
                        One final step before you enter the hall.
                    </p>
                </div>
            </section>

            <section class="flex min-h-screen items-center justify-center px-6 py-8 sm:px-16 lg:px-20">
                <div class="w-full max-w-[560px]">
                    <header class="mb-10">
                        <h1 class="guild-display text-4xl tracking-[-.03em] text-[#0e2527] sm:text-5xl">Check your email</h1>
                        <p class="mt-2 text-lg text-[#817b76]">Click the verification link in your email, then return here to enter the hall.</p>
                    </header>

                    @if (session('status'))
                        <p class="mb-5 text-sm text-[#477778]">{{ session('status') }}</p>
                    @endif

                    <button type="button" data-firebase-check class="w-full rounded-[9px] bg-[#223b3d] px-6 py-4 font-bold text-white transition hover:bg-[#172e30]">I Verified My Email</button>
                    <p data-firebase-error class="mt-4 hidden text-center text-sm text-[#c04e2b]"></p>
                    <p class="mt-5 text-center text-base text-[#817b76]">Need another email? Check your spam folder or restart registration.</p>
                </div>
            </section>
        </main>
    </body>
</html>
