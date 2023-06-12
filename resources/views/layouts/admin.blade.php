<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') . ' Admin Panel' }}</title>
    @vite(['resources/js/admin/app.js'])
{{--    @vite(['resources/css/app.css'])--}}
    <!-- Scripts -->
    {{--<script src="{{ asset('js/app.js') }}"></script>--}}
    <script src="{{ asset('js/jquery-3.6.4.min.js') }}" type="text/javascript"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <!-- Styles -->
    {{--<link href="{{ asset('css/style.css') }}" rel="stylesheet">--}}
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <style>
        .ck-powered-by-balloon {
            display: none !important;
        }
        .input-group-append {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div id="app" class="admin-container">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto"></ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{--{{ Auth::user()->name }}--}} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="e.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <nav class="col-md-3 d-none d-md-block bg-light sidebar">
                    @include('partials.admin-sidebar')
                </nav>
                <div class="col-md-9">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</div>
<script src="{{ asset('js/ckeditor/build/ckeditor.js') }}"></script>

<script type="application/javascript">
    if ($( '.ckeditor-custom' ).length > 0) {
        ClassicEditor.create(document.querySelector('.ckeditor-custom'), {
            autoParagraph: false,
            allowedContent: true
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(err => {
            console.error(err.stack);
        });
    }
</script>
</body>
</html>


