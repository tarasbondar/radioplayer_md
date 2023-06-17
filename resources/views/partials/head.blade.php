<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('meta_title', \App\Helpers\SiteHelper::getMetaTitle())</title>
<meta name="keywords" content="@yield('meta_keywords', \App\Helpers\SiteHelper::getMetaKeywords())">
<meta name="description" content="@yield('meta_description', \App\Helpers\SiteHelper::getMetaDescription())">
<meta name="color-scheme" content="dark light">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">

<script src="{{ asset('js/jquery-3.6.4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/ckeditor.js') }}" type="text/javascript"></script>

@vite(['resources/js/app.js'])

</head>
