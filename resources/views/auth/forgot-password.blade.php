<x-app-layout >
    <div class="login" >
        <form method="POST" action="{{ route('password.email') }}" 
            x-data="{
                loading:false,
            }" x-on:submit="loading = true">
            @csrf
            <h2>忘記密碼？輸入Email以重製密碼</h2>
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <label for="">
                <p>電子郵件 <span>*</span></p>
                <input type="email" name="email" class="mt-1"  :errors="$errors" required autofocus placeholder="Email address..." />
            </label>
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <label for="" >
                <button type="submit"  >
                    <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p x-show="!loading">發送電子郵件</p>
                </button>
            </label>
        </form>
    </div>

    @push('scripts')
    <script>
        document.querySelector('header').classList.add('isActive');
    </script>
    @endpush
</x-app-layout>

{{-- <x-app-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('忘記密碼了嗎？ 沒問題。 只需告訴我們您的電子郵件地址，我們將通過電子郵件向您發送一個密碼重置連結，您可以通過該連結重建一個新密碼。') }}
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('發送電子郵件') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>

    @push('scripts')
    <script>
        document.querySelector('header').classList.add('isActive');
    </script>
    @endpush
</x-app-layout> --}}
