<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Freelancers Guild') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,500&family=DM+Sans:wght@400;500;600;700&family=Space+Mono&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f8f3ed] text-[#132b2d]">
        <main class="min-h-screen lg:grid lg:grid-cols-2">
            <section class="guild-panel relative flex min-h-[620px] flex-col overflow-hidden px-8 py-8 sm:px-16 lg:min-h-screen lg:px-16 lg:py-12">
                <div class="relative z-10 flex items-center gap-4">
                    <span class="grid size-10 place-items-center rounded-[5px] bg-[#d9683c] text-lg font-bold text-white">N</span>
                    <span class="guild-script text-2xl italic text-[#f8f3ed]">Freelancers Guild</span>
                </div>

                <div class="relative z-10 my-auto max-w-xl py-20">
                    <h1 class="guild-display text-5xl leading-[.9] tracking-[-.04em] text-[#f8f3ed] sm:text-6xl lg:text-[4rem]">
                        Where heroes are
                        <span class="block text-[#a8c7c7] italic">forged and quests begin.</span>
                    </h1>
                    <p class="mt-10 max-w-lg text-lg leading-8 text-[#72b4c4] sm:text-xl">
                        Take on quests. Prove your worth. Rise through the ranks. The Guild awaits those bold enough to answer the call.
                    </p>
                    <div class="mt-12 flex items-center gap-4 text-xl text-[#88c1c2]">
                        <span class="guild-emblem">⚔</span>
                        <span class="guild-emblem">♢</span>
                        <span class="guild-emblem guild-emblem-active">▤</span>
                        <span class="guild-emblem">♟</span>
                        <span class="guild-emblem">⚜</span>
                        <span class="hidden h-px w-72 bg-[#3c6b6d] sm:block"></span>
                    </div>
                </div>

                <p class="relative z-10 font-mono text-xs tracking-wide text-[#507477]">
                    All adventurers aged 18 and above are welcome
                </p>
            </section>

            <section class="flex min-h-screen items-center justify-center px-6 py-8 sm:px-16 lg:px-20">
                <div class="w-full max-w-[560px]">
                    <nav class="mb-12 grid grid-cols-2 rounded-xl bg-[#eee8e1] p-1 text-center font-semibold text-[#7f7a76]">
                        <a href="{{ route('login') }}" class="rounded-lg bg-white px-4 py-4 text-[#132b2d] shadow-[0_2px_4px_rgba(34,29,25,.12)]">Sign In</a>
                        <a href="{{ route('register') }}" class="px-4 py-4">Create Account</a>
                    </nav>

                    <header class="mb-10">
                        <h2 class="guild-display text-4xl tracking-[-.03em] text-[#0e2527] sm:text-5xl">Welcome back</h2>
                        <p class="mt-2 text-lg text-[#817b76]">Your guild record awaits. Return to the hall.</p>
                    </header>

                    <form id="signin" class="space-y-5" action="{{ route('login.store') }}" method="post">
                        @csrf
                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold tracking-wide text-[#273d3e]">EMAIL ADDRESS</label>
                            <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com" class="guild-input" required>
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between gap-4">
                                <label for="password" class="text-sm font-bold tracking-wide text-[#273d3e]">PASSWORD</label>
                                <a href="#" class="text-sm font-medium text-[#d45d34] hover:underline">Forgot password?</a>
                            </div>
                            <div class="relative">
                                <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" class="guild-input pr-20" required>
                                <button type="button" data-toggle-password class="absolute inset-y-0 right-0 px-4 text-sm text-[#817b76] hover:text-[#132b2d]">SHOW</button>
                            </div>
                        </div>

                        <label class="flex items-center gap-3 pt-1 text-base text-[#252d2e]">
                            <input type="checkbox" name="remember" class="size-4 rounded border-[#8c8985] accent-[#223b3d]">
                            <span>Remember me for 30 days</span>
                        </label>

                        <button type="submit" class="w-full rounded-[9px] bg-[#223b3d] px-6 py-4 font-bold text-white transition hover:bg-[#172e30]">
                            Sign In
                        </button>
                    </form>

                    @if ($errors->any())
                        <p class="mt-4 text-sm text-[#c04e2b]">{{ $errors->first() }}</p>
                    @endif

                    <div class="my-8 flex items-center gap-4 text-sm text-[#b0a59c]">
                        <span class="h-px flex-1 bg-[#ddd4cb]"></span>
                        <span>OR</span>
                        <span class="h-px flex-1 bg-[#ddd4cb]"></span>
                    </div>

                    <button type="button" data-firebase-google class="block w-full rounded-[9px] border border-[#ddd4cb] bg-[#f2ebe3] px-4 py-4 text-center font-medium text-[#243537] transition hover:bg-[#e9dfd5]">
                        Continue with Google
                    </button>
                    <p data-firebase-error class="mt-3 hidden text-center text-sm text-[#c04e2b]"></p>

                    <p class="mt-8 text-center text-base text-[#817b76]">
                        No account yet?
                        <a href="{{ route('register') }}" class="font-bold text-[#132b2d] hover:underline">Create one free</a>
                    </p>
                </div>
            </section>
        </main>

        <button type="button" aria-label="Help" class="fixed bottom-4 right-4 grid size-10 place-items-center rounded-full bg-[#252525] text-xl text-white shadow-lg ring-2 ring-[#b8b4ae]">?</button>
    </body>
</html>
