@extends('layouts.shop')
@section('title', 'Вхід — FreshMart')

@section('content')
<div class="container">
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-card__logo">🛒 FreshMart</div>
            <h1 class="auth-card__title">Вхід до акаунту</h1>

            @if(session('success'))
                <div class="alert alert--success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert--error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('shop.login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}"
                           placeholder="your@email.com" autofocus required>
                </div>
                <div class="form-group">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-input"
                           placeholder="Введіть пароль" required>
                </div>
                <div class="form-group form-group--row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember"> Запам'ятати мене
                    </label>
                </div>
                <button type="submit" class="btn btn--primary btn--block">Увійти</button>
            </form>

            <p class="auth-card__switch">
                Немає акаунту?
                <a href="{{ route('shop.register') }}">Зареєструватись</a>
            </p>
        </div>
    </div>
</div>
@endsection
