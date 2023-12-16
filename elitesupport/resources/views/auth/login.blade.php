<x-guest-layout>
    
    <x-authentication-card >
        
        <x-slot name="logo">
           {{--  <x-authentication-card-logo /> --}}
           <img src="{{asset('images/al-logo.svg')}}" alt="logo" width="178.05" height="37.88" >
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
           
        <form method="POST" action="{{ route('login') }}" >
            <center><h1><b>Elite Login</b></h1></center>
            @csrf

            <div>
                <x-label for="email" value="{{ __('Employee Id') }}" />
                <x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            {{-- <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> --}}

            <div class="flex items-center justify-end mt-4" >
               {{--  @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif --}}

                <x-button class="ml-4 btn btn-primary">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
        
    </x-authentication-card>
    
</x-guest-layout>
