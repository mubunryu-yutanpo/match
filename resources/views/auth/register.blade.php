@extends('layouts.parent')

@section('title', 'ユーザー登録')

@section('header')
    @parent
@show

@section('main')

<section class="p-page-visual">
    <form action="{{ route('register') }}" method="post" class="p-register c-box c-box--form">
        @csrf

        <h2 class="p-register__title c-title ">
            <i class="fa-solid fa-user-plus c-icon c-icon--title"></i>
            新規ユーザー登録
        </h2>

        <div class="p-register__container c-box--form-container">
            <label for="name" class="p-register__label c-label">*名前:</label>
            <input id="name" type="text" class="p-register__input c-input @error('name') c-error @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="20文字以内">
            @error('name')
                <p class="c-error--text" role="alert">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="p-register__container c-box--form-container">
            <label for="email" class="p-register__label c-label">*メールアドレス:</label>
            <input id="email" type="email" class="p-register__input c-input @error('email') c-error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <p class="c-error--text" role="alert">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="p-register__container c-box--form-container">
            <label for="password" class="p-register__label c-label">*パスワード:</label>
            <input id="password" type="password" class="p-register__input c-input @error('password') c-error @enderror" name="password" required autocomplete="current-password" placeholder="半角英数字8文字以上">
            @error('password')
                <p class="c-error--text" role="alert">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="p-register__container c-box--form-container">
            <label for="password-confirm" class="p-register__label c-label">*パスワード（再入力）:</label>
            <input id="password-confirm" type="password" class="p-register__input c-input @error('password-confirm') c-error @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="半角英数字8文字以上">
            @error('password-confirm')
                <p class="c-error--text" role="alert">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="p-submit">
            <button type="submit" class="c-button p-submit__button">登録する</button>
        </div>


    </form>
</section>

@endsection

@section('footer')
    @parent
@show