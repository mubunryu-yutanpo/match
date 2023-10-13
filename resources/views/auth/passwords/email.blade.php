@extends('layouts.parent')

@section('title', 'パスワードリセットリンク発行')

@section('main')
    
    <form action="{{ route('password.email') }}" method="post" class="p-reset-email c-box--form">
        @csrf

        <h2 class="p-reset-email__title c-title">
            <i class="fa-solid fa-envelope-circle-check c-icon c-icon--title"></i>
            パスワードリセット
        </h2>


        <div class="p-reset-email__container">
            <p class="p-reset-email__text c-text">以下のメールアドレスにパスワード再発行のためのリンクをお送りします。</p>
        </div>

        <div class="p-reset-email__container c-box--form-container">
            <label for="email" class="c-label">メールアドレス:</label>
            <input id="email" type="email" class="c-input @error('email') valid-error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="c-error c-error--text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-submit">
            <button type="submit" class="p-submit__button c-button">リンクを受け取る</button>
        </div>

    </form>


@endsection
