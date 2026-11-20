<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in | Turath Admin</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="login-wrap">
    <form class="card login-card" method="POST" action="{{ route('login.store') }}">
        @csrf
        <a class="brand" href="{{ route('home') }}">Turath<span>Admin</span></a>
        <h1 class="h-md">Sign in</h1>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            @error('email')<span class="error" role="alert">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" autocomplete="current-password" required>
        </div>
        <button class="btn btn-primary btn-block" type="submit">Sign in</button>
        <p class="muted center mt">Too many attempts will be temporarily blocked.</p>
    </form>
</div>
</body>
</html>
