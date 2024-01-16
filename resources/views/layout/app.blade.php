<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
     
    @vite('resources/sass/app.scss','resources/js/app.js')
    <title>Document</title>
</head>
<body>
    
    {{-- ---- Header ---- --}}
    @include('layout.header')

  
    {{-- ---- Content ---- --}}
    @yield('content')  


    {{-- ---- Script ---- --}}    
    @yield('script')
</body>
</html>