<!DOCTYPE html>
<html lang="en">
    @include('partials.head', ['type' => $type ?? 'gwb'])
<body>
    @include('partials.navbar', ['type' => $type ?? 'gwb'])

    @yield('content')

    @include('partials.footer', ['type' => $type ?? 'gwb'])
</body>
</html>
