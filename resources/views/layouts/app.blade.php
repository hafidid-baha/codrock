<!DOCTYPE html>
<html lang="en" dir="ltr">
{{-- include head here --}}
@include('partials.head')


<body>
    {{-- include header her --}}
    @include('partials.header')
    @yield('content')
    <!-- footer -->
    @include('partials.footer')
</body>

</html>
