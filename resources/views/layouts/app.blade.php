<html lang="ru">
<head>
    <title>Laravel Demo Project - @yield('title')</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    @section('meta')

    @show
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('home')}}">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ \Route::currentRouteName() === 'web.showUploadForm' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('web.showUploadForm')}}">Upload</a>
            </li>
            <li class="nav-item {{ \Route::currentRouteName() === 'web.listRows' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('web.listRows')}}">Rows</a>
            </li>
        </ul>
    </div>
</nav>

<div id="app" class="container">
    @yield('content')
    <import-status-notifier></import-status-notifier>
</div>

<div id="loading" class="loading d-none">
    <div id="loader-img">
        <img src="/img/svg/loader.svg">
    </div>
</div>
<script src="{{asset('js/app.js')}}" defer></script>
</body>
</html>
