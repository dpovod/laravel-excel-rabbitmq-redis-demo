<html lang="ru">
<head>
    <title>Laravel Demo Project - @yield('title')</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('home')}}">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ \Route::currentRouteName() === 'showUploadForm' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('showUploadForm')}}">Upload</a>
            </li>
            <li class="nav-item {{ \Route::currentRouteName() === 'listRows' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('listRows')}}">Rows</a>
            </li>
        </ul>
    </div>
</nav>

<div id="app" class="container">
    @yield('content')
</div>

<script src="{{asset('js/app.js')}}" defer></script>
</body>
</html>