@extends('layouts.parent')

@section('title', 'リンク有効期限切れ')

@section('main')
    <form action="{{ route('password.email') }}" method="post" class="p-reset-error c-box--form">
        @csrf

        <h2 class="p-reset-error c-title">
            リンクの有効期限切れ
        </h2>

        <div class="p-reset-error__container">
            <p class="p-reset-error__text c-text">リンクの有効期限が切れています。お手数ですがもう一度メールアドレスを入力し、リンクから再設定してください。</p>
        </div>

        <div class="p-reset-error__container c-box--form-container">
            <label for="email" class="c-label">メールアドレス:</label>
            <input id="email" type="email" class="c-input @error('email') valid-error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="c-error c-error--text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-submit">
            <button type="submit" class="p-submit__button c-button">再度リンクを受け取る</button>
        </div>

    </form>

@endsection
