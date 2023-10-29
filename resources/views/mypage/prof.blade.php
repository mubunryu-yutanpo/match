@extends('layouts.parent')

@section('title', 'プロフィール編集')

@section('header')
    @parent
@show

@section('main')

    <form action="{{ route('prof.update', $user->id) }}" method="post" class="p-profile c-box--form" enctype="multipart/form-data">
        @csrf

        <h2 class="c-title p-profile__title">
            <i class="fa-solid fa-user-pen c-icon c-icon--title"></i>
            プロフィール編集
        </h2>

        <!-- ユーザー名 -->
        <div class="p-profile__container c-box--form-container">
            <label for="name" class="c-label p-profile__label">*名前:</label>
                <input id="name" type="text" class="c-input p-profile__input @error('name') valid-error @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="c-error c-error--text p-profile__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <!-- メールアドレス -->
        <div class="p-profile__container c-box--form-container">
            <label for="email" class="c-label p-profile__label">*メールアドレス:</label>
                <input id="email" type="email" class="c-input p-profile__input @error('email') valid-error @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="c-error c-error--text p-profile__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <div id="app">
            <avatar-preview-component :user="{{ $user }}"></avatar-preview-component>
        </div>

        <!-- 自己紹介文 -->
        <div class="p-profile__container c-box--form-container">
            <!-- テキストカウンター用コンポーネント -->
            <div id="counter">
                <text-counter-component
                    label="自己紹介文"
                    name="introduction"
                    id="introduction"
                    :max="300"
                    autocomplete="introduction"
                    placeholder="300文字以内で入力してください"
                    data="{{ $user->introduction }}"
                ></text-counter-component>
            </div>

            @error('introduction')
                <span class="c-error c-error--text p-profile__error-text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-submit">
            <button class="c-button p-submit__button">更新して登録</button>
        </div>

    </form>

    <div class="c-box--link">
        <a href="{{ route('withdraw', $user->id) }}" class="c-link">退会はこちら</a>
    </div>

@endsection

@section('footer')
    @parent
@show