<x-app-layout >
    <div class="login" >
        <form method="POST" action="{{ route('login') }}" 
            x-data="{
                loading:false,
            }" x-on:submit="loading = true">
            @csrf
            <h2>登入</h2>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <label for="">
                <p>電子郵件 <span>*</span></p>
                <x-input type="email" name="email" class="mt-1" :value="old('email')"  :errors="$errors" required autofocus placeholder="Email address..." />
            </label>
            <label for="">
                <p>密碼 <span>*</span></p>
                <x-input type="password" name="password" class="mt-1"  :errors="$errors" required placeholder="Password..." />
            </label>
            <label for="remember_me" class="flex items-center">
                <input type="checkbox" id="remember_me" name="remember" class="mr-1"  />記住我
            </label>
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <label for="" >
                <button type="submit"  >
                    <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p x-show="!loading">登入</p>
                </button>
            </label>
            <div>
                <a href="{{route('register')}}">還沒有帳號嗎？點擊註冊</a>
                <a href="{{route('password.request')}}">忘記密碼？</a>
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
