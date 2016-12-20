<html>
<head>
    <title>App Name - @yield('title')</title>
</head>
<body>
{{ trans_choice('foo.bar', 10) }}

<div class="container">
    @yield('content')
</div>
</body>
</html>
