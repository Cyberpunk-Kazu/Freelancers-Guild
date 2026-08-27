<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reset password · {{ config('app.name', 'Freelancers Guild') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
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
                        A new password,
                        <span class="block text-[#a8c7c7] italic">a new beginning.</span>
                    </h1>
                </div>
            </section>

            <section class="flex min-h-screen items-center justify-center px-6 py-8 sm:px-16 lg:px-20">
                <div class="w-full max-w-[560px]">
                    <header class="mb-10">
                        <h1 class="guild-display text-4xl tracking-[-.03em] text-[#0e2527] sm:text-5xl">Set a new password</h1>
                        <p class="mt-2 text-lg text-[#817b76]">Choose a strong password for your guild record.</p>
                    </header>

                    <form class="space-y-5" action="{{ route('password.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold tracking-wide text-[#273d3e]">EMAIL ADDRESS</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" class="guild-input" required>
                        </div>
                        <div>
                            <label for="password" class="mb-2 block text-sm font-bold tracking-wide text-[#273d3e]">NEW PASSWORD</label>
                            <input id="password" name="password" type="password" class="guild-input" required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-bold tracking-wide text-[#273d3e]">CONFIRM PASSWORD</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="guild-input" required>
                            @error('password') <p class="mt-2 text-sm text-[#c04e2b]">{{ $message }}</p> @enderror
                        </div>
                        <p class="text-sm text-[#817b76]">Use at least 10 characters, including one uppercase letter and one special character.</p>
                        <button type="submit" class="w-full rounded-[9px] bg-[#223b3d] px-6 py-4 font-bold text-white transition hover:bg-[#172e30]">Reset Password</button>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
