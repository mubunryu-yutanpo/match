@extends('layouts.parent')

@section('title', 'ログイン')

@section('header')
    @parent
@show

@section('main')

<section class="p-page-visual">
    <form action="{{ route('login') }}" method="post" class="p-login c-box c-box--form">
        @csrf

        <h2 class="p-login__title c-title">
            <i class="fa-solid fa-user-check c-icon c-icon--title"></i>
            ログイン
        </h2>

        <div class="p-login__container c-box--form-container">
            <label for="email" class="p-login__label c-label">*メールアドレス:</label>
            <input id="email" type="text" class="p-login__input c-input @error('email') c-error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <p class="c-error--text" role="alert">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="p-login__container c-box--form-container">
            <label for="password" class="p-login__label c-label">*パスワード:</label>
            <input id="password" type="password" class="p-login__input c-input @error('password') c-error @enderror" name="password" required autocomplete="current-password" placeholder="半角英数字8文字以上">
            @error('password')
                <p class="c-error--text" role="alert">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="p-login__container c-box--form-container">

            <div class="p-remember">
                <input class="c-input p-remember__input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="c-label p-remember__label" for="remember">
                    ログインを保持する
                </label>
            </div>
        </div>

        <div class="p-login__container c-box--form-container">

            @if (Route::has('password.request'))
                <div class="p-forgot">
                    <a class="p-forgot__link c-link" href="{{ route('password.request') }}">
                        パスワードをお忘れの方はこちら
                    </a>
                </div>
            @endif

        </div>

        <div class="p-submit">
            <button type="submit" class="c-button p-submit__button">ログインする</button>
        </div>


    </form>
</section>

@endsection

@section('footer')
    @parent
@show