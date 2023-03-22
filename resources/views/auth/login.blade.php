<x-app-layout>
    <div class="login">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h2>登入</h2>
            <label for="">
                <p>電子郵件 <span>*</span></p>
                <input type="email" name="email" :value="old('email')" required autofocus />
            </label>
            <label for="">
                <p>密碼 <span>*</span></p>
                <input type="password" name="password" required />
            </label>
            <label for="remember_me" class="flex items-center">
                <input type="checkbox" id="remember_me" name="remember" class="mr-1" />記住我
            </label>
            <label for="">
                <button type="submit">登入</button>
            </label>
            <div>
                <a href="/register">還沒有帳號嗎？點擊註冊</a>
                <a href="/">忘記密碼？</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.querySelector('header').classList.add('isActive');
    </script>
    @endpush
</x-app-layout>

{{-- <x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-3">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout> --}}
