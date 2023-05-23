@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="login">
            <div class="container">
                <div class="login__header">
                    <h2 class="h2 text-center">Регистрация</h2>
                </div>

                <div class="login__wrapper">
                    <form action="{{ route('register') }}" method='POST' id="apply-form" {{--data-validate--}}>

                        @csrf

                        <div class="input form-floating _icon">
                            <input type="text" class="form-control" placeholder="Имя" name="name" id="name" required/>
                            <label for="name">Имя</label>
                            <i class="form-icon">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </i>
                            <div class="messages">
                                {{--@error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror--}}
                            </div>
                        </div>

                        <div class="input form-floating _icon">
                            <input type="email" class="form-control" placeholder="Email" name="email" id="email" required/>
                            <label for="email">Email</label>
                            <i class="form-icon">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </i>
                            <div class="messages">
                                {{--@error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror--}}
                            </div>
                        </div>

                        <div class="input form-floating _icon">
                            <input type="password" class="form-control" placeholder="Пароль" name="password" id="password" required/>
                            <label for="password">Пароль</label>
                            <i class="form-icon">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M21.0003 2L19.0003 4M19.0003 4L22.0003 7L18.5003 10.5L15.5003 7.5M19.0003 4L15.5003 7.5M11.3903 11.61C11.9066 12.1195 12.3171 12.726 12.598 13.3948C12.879 14.0635 13.0249 14.7813 13.0273 15.5066C13.0297 16.232 12.8887 16.9507 12.6122 17.6213C12.3357 18.2919 11.9293 18.9012 11.4164 19.4141C10.9035 19.9271 10.2942 20.3334 9.62358 20.6099C8.95296 20.8864 8.23427 21.0275 7.50891 21.025C6.78354 21.0226 6.06582 20.8767 5.39707 20.5958C4.72831 20.3148 4.12174 19.9043 3.61227 19.388C2.6104 18.3507 2.05604 16.9614 2.06857 15.5193C2.0811 14.0772 2.65953 12.6977 3.67927 11.678C4.69902 10.6583 6.07849 10.0798 7.52057 10.0673C8.96265 10.0548 10.352 10.6091 11.3893 11.611L11.3903 11.61ZM11.3903 11.61L15.5003 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </i>

                            <div class="messages">
                                {{--@error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror--}}
                            </div>
                        </div>

                        <div class="input form-floating _icon">
                            <input type="password" class="form-control" placeholder="Повторите пароль" name="password_confirmation" id="password-confirm" required/>
                            <label for="password-confirm">Повторите пароль</label>
                            <i class="form-icon">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M21.0003 2L19.0003 4M19.0003 4L22.0003 7L18.5003 10.5L15.5003 7.5M19.0003 4L15.5003 7.5M11.3903 11.61C11.9066 12.1195 12.3171 12.726 12.598 13.3948C12.879 14.0635 13.0249 14.7813 13.0273 15.5066C13.0297 16.232 12.8887 16.9507 12.6122 17.6213C12.3357 18.2919 11.9293 18.9012 11.4164 19.4141C10.9035 19.9271 10.2942 20.3334 9.62358 20.6099C8.95296 20.8864 8.23427 21.0275 7.50891 21.025C6.78354 21.0226 6.06582 20.8767 5.39707 20.5958C4.72831 20.3148 4.12174 19.9043 3.61227 19.388C2.6104 18.3507 2.05604 16.9614 2.06857 15.5193C2.0811 14.0772 2.65953 12.6977 3.67927 11.678C4.69902 10.6583 6.07849 10.0798 7.52057 10.0673C8.96265 10.0548 10.352 10.6091 11.3893 11.611L11.3903 11.61ZM11.3903 11.61L15.5003 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </i>
                        </div>

                        <div class="input input__inner">
                            <input class="input__checkbox" type="checkbox" id="agreement">
                            <label class="input__label" for="agreement">
                                Я принимаю <a href="/privacy" class="link">условия</a> <a href="/privacy" class="link">пользовательского соглашения</a>
                            </label>
                            <svg class="icon">
                                <use href="/img/sprite.svg#check"></use>
                            </svg>
                            <div class="messages"></div>
                        </div>

                        <button class="btn btn_default btn_primary mb-24" type="submit">Зарегистрироваться</button>

                    </form>
                </div>

                <div class="login__wrapper-action">
                    <div class="text-center mb-24">
                        <span class="divide-text x-small">или</span>
                    </div>
                    <a href="#" class="btn btn_google-sign-in">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33" fill="none">
                            <path d="M32.5004 16.8759C32.5017 15.7515 32.4077 14.6291 32.2192 13.521H16.8242V19.8756H25.6418C25.4613 20.8905 25.0794 21.8579 24.5191 22.7197C23.9588 23.5814 23.2316 24.3195 22.3814 24.8895V29.0144H27.6438C30.7251 26.143 32.5004 21.8967 32.5004 16.8759Z" fill="#4285F4"/>
                            <path d="M16.8239 32.9998C21.2292 32.9998 24.9386 31.5378 27.6435 29.017L22.381 24.8922C20.9164 25.8959 19.03 26.4688 16.8239 26.4688C12.5659 26.4688 8.95178 23.5676 7.65941 19.6582H2.23828V23.9091C3.597 26.6417 5.68046 28.9389 8.25603 30.5442C10.8316 32.1495 13.7979 32.9996 16.8239 32.9998Z" fill="#34A853"/>
                            <path d="M7.65941 19.6583C6.97618 17.6097 6.97618 15.3913 7.65941 13.3427V9.0918H2.23828C1.09528 11.3906 0.5 13.9277 0.5 16.5005C0.5 19.0732 1.09528 21.6104 2.23828 23.9092L7.65941 19.6583Z" fill="#FBBC04"/>
                            <path d="M16.8239 6.53209C19.1519 6.49365 21.4014 7.38267 23.0862 9.00699L27.7455 4.2978C24.791 1.49308 20.8769 -0.0467635 16.8239 0.00108251C13.7979 0.0012219 10.8316 0.851384 8.25603 2.45665C5.68046 4.06192 3.597 6.35912 2.23828 9.09178L7.65941 13.3427C8.95178 9.43323 12.5659 6.53209 16.8239 6.53209Z" fill="#EA4335"/>
                        </svg>
                        <span>
                            Зарегистрироваться<br/> через Google
                        </span>
                    </a>

                    <div class="holder-action">
                        <h3 class="h3 text-center">У меня есть аккаунт</h3>
                        <a href="login.html" class="btn btn_default btn_primary">Войти</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
