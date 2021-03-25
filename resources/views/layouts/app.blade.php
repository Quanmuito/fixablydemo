<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.head')
<body>
    @yield('header')
    <div id="app">
        @include('inc.navbar')
        @include('inc.messages')

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>
    @yield('script')
</body>
</html>
