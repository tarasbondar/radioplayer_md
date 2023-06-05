<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials/head')

<body class="body">
<div class="page-grid">

    <div class="page-main" id="appContainer">
        <picture>
            <source srcset="/img/bg-dark.png 1x, /img/bg-dark@2x.png 2x" media="(prefers-color-scheme: dark)">
            <source srcset="/img/bg-light.png 1x, /img/bg-light@2x.png 2x" media="(prefers-color-scheme: light) or (prefers-color-scheme: no-preference)">
            <img class="page-bg" src="/img/bg-light.png" alt="" width="586" height="586" loading="lazy">
        </picture>

        @include('partials.header')

        @yield('content')

    </div>
</div>

{{--@include('partials.scripts')--}}

<script src="{{ asset('js/ckeditor/build/ckeditor.js') }}"></script>

<script type="application/javascript">
    ClassicEditor.create( document.querySelector( '.ckeditor-custom' ), {
        autoParagraph: false,
        allowedContent: true
    } )
        .then( editor => {
            window.editor = editor;
        } )
        .catch( err => {
            console.error( err.stack );
        });
</script>

</body>
</html>

