<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img src="{{asset('img/Logo_PENS.png')}}" class="object-scale-down mx-auto w-24 h-24">
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        

        <form method="POST" action="{{ route('verify-otp') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="OTP" :value="__('Otp')" />

                <x-input id="otp" class="block mt-1 w-full" type="text" name="otp" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-3">
                    {{ __('Verify') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
