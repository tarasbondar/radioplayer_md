@extends('layouts/client')

@section('content')
    <main class="main">
        <section class="login">
            <div class="container">
                <div class="login__header">
                    <h2 class="h2 text-center">{{ __('auth.forgotPasswordMessage') }}</h2>
                </div>
                <div class="login__wrapper">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <x-auth-session-status :status="session('status')" />
                        <div class="input form-floating _icon">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('auth.emailAddress') }}" name="email" id="email" value="{{ old('email') }}" autocomplete="email" autofocus/>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            <label for="email">{{ __('auth.emailAddress') }}</label>
                            <i class="form-icon">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </i>
                        </div>
                        <button class="btn btn_default btn_primary mb-24" type="submit">{{ __('auth.passwordResetLink') }}</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
