<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>房子ROW</title>
        <!-- Scripts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cardo:ital,wght@0,400;0,700;1,400&family=Noto+Serif+SC:wght@200;300;400;500;600;700;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@200;300;400;500;600;700;900&display=swap" rel="stylesheet">
        {{-- <link rel="stylesheet" href="/css/app.css"> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="/css/app.css">
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @include('components.shop-cart')
            @include('components.header')
            <main x-data="{
                
            }" >
                {{ $slot }}
            </main>
        @include('components.footer')
        </div>

        <script src="/js/app.js"></script>
        <script src="/js/script.js"></script>
        @stack('scripts')
    </body>
</html>
