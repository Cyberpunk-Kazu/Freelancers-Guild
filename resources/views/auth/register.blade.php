<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Create account · {{ config('app.name', 'Freelancers Guild') }}</title>
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
                        Find your place.
                        <span class="block text-[#a8c7c7] italic">Write your legend.</span>
                    </h1>
                    <p class="mt-10 max-w-lg text-lg leading-8 text-[#72b4c4] sm:text-xl">
                        Join a guild of bold freelancers, take on meaningful quests, and build a career worth telling stories about.
                    </p>
                </div>
                <p class="relative z-10 font-mono text-xs tracking-wide text-[#507477]">
                    All adventurers aged 18 and above are welcome · Local realm only
                </p>
            </section>

            <section class="flex min-h-screen items-center justify-center px-6 py-8 sm:px-16 lg:px-20">
                <div class="w-full max-w-[560px]">
                    <nav class="mb-12 grid grid-cols-2 rounded-xl bg-[#eee8e1] p-1 text-center font-semibold text-[#7f7a76]">
                        <a href="{{ route('login') }}" class="px-4 py-4">Sign In</a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-white px-4 py-4 text-[#132b2d] shadow-[0_2px_4px_rgba(34,29,25,.12)]">Create Account</a>
                    </nav>

                    <header class="mb-10">
                        <h2 class="guild-display text-4xl tracking-[-.03em] text-[#0e2527] sm:text-5xl">Join the guild</h2>
                        <p class="mt-2 text-lg text-[#817b76]">Create your record and answer the call.</p>
                    </header>

                    <form class="space-y-5" action="{{ route('register.store') }}" method="post">
                        @csrf
                        <div>
                            <label for="name" class="mb-2 block text-sm font-bold tracking-wide text-[#273d3e]">FULL NAME</label>
                            <input id="name" name="name" type="text" autocomplete="name" value="{{ old('name') }}" placeholder="Your name" class="guild-input" required>
                            @error('name') <p class="mt-2 text-sm text-[#c04e2b]">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold tracking-wide text-[#273d3e]">EMAIL ADDRESS</label>
                            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" placeholder="you@example.com" class="guild-input" required>
                            @error('email') <p class="mt-2 text-sm text-[#c04e2b]">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password" class="mb-2 block text-sm font-bold tracking-wide text-[#273d3e]">PASSWORD</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" placeholder="At least 8 characters" class="guild-input" required>
                            @error('password') <p class="mt-2 text-sm text-[#c04e2b]">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-bold tracking-wide text-[#273d3e]">CONFIRM PASSWORD</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Repeat your password" class="guild-input" required>
                        </div>
                        <button type="submit" class="w-full rounded-[9px] bg-[#223b3d] px-6 py-4 font-bold text-white transition hover:bg-[#172e30]">Create Account</button>
                    </form>

                    <p class="mt-8 text-center text-base text-[#817b76]">
                        Already a member?
                        <a href="{{ route('login') }}" class="font-bold text-[#132b2d] hover:underline">Sign in</a>
                    </p>
                </div>
            </section>
        </main>
    </body>
</html>
