@extends('layouts.parent')

@section('title', 'パスワード再発行')

@section('main')

    <form action="{{ route('password.update') }}" method="post" class="p-reset c-box--form">
        @csrf
        
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="p-reset__container c-box--form-container">
            <label for="email" class="c-label">メールアドレス:</label>
            <input id="email" type="email" class="c-input @error('email') valid-error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="c-error c-error--text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-reset__container c-box--form-container">
            <label for="password" class="c-label">新しいパスワード:</label>
            <input id="password" type="password" class="c-input @error('password') valid-error @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <span class="c-error c-error--text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-reset__container c-box--form-container">
            <label for="password-confirm" class="c-label">パスワード(再入力):</label>
            <input id="password-confirm" type="password" class="c-input @error('password-confirm') valid-error @enderror" name="password_confirmation" required autocomplete="new-password">
        </div>


        <div class="p-submit">
            <button type="submit" class="p-submit__button c-button">パスワードを変更して登録</button>
        </div>

    </form>
@endsection
