<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'match')</title>

    @hasSection('description')
        <meta name="description" content="@yield('description')">
    @else
        <meta name="description" content="matchは、技術の「欲しい」をつなぐサービスです。気軽に、エンジニア向けの単発案件やサービス立ち上げ案を投稿・応募できます。">
    @endif

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
    @section('header')

        <!-- フラッシュメッセージ -->
        @if (session('flash_message'))
        <div id="flash-message" class="p-alert 
            @if (session('flash_message_type') === 'error') is-error
            @elseif (session('flash_message_type') === 'success') is-success
            @endif" role="alert">
            {{ session('flash_message') }}
        </div>
        @endif

        <header class="l-header">
            <div class="p-header">
                <!-- アプリ名 -->
                <h1 class="p-header__title">match</h1>
                <!-- SP用メニューボタン -->
                <!-- NAVメニュー -->
                <nav class="p-nav">
                    <ul class="p-nav__list">

                        <li class="p-nav__item c-list-item">
                            <a href="/" class="p-nav__link c-link">HOME</a>
                        </li>
                            
                        <li class="p-nav__item c-list-item">
                            <a href="{{ route('list') }}" class="p-nav__link c-link">案件一覧</a>
                        </li>

                    @if(!Auth::check())
                            
                            <li class="p-nav__item c-list-item">
                                <a href="/login" class="p-nav__link c-link">ログイン</a>
                            </li>

                        @if(Route::has('register'))
                            <li class="p-nav__item c-list-item">
                                <a href="/register" class="p-nav__link c-link">会員登録</a>
                            </li>
                        @endif
                    @endif

                    @auth
                        <li class="p-nav__item c-list-item">
                            <a href="{{ route('mypage') }}" class="p-nav__link c-link">マイページ</a>
                        </li>
                        
                        <li class="p-nav__item c-list-item">
                            <a href="{{ route('new') }}" class="p-nav__link c-link">案件を投稿</a>
                        </li>

                        <li class="p-nav__item c-list-item">
                            <a href="{{ route('d_message', Auth::id() ) }}" class="p-nav__link c-link">メッセージBOX</a>
                        </li>
                        
                        <li class="p-nav__item c-list-item">
                            <a href="{{ route('prof', Auth::id() ) }}" class="p-nav__link c-link">プロフィール編集</a>
                        </li>

                        <li class="p-nav__item c-list-item">
                            <a href="{{ route('logout') }}" class="p-nav__link c-link">ログアウト</a>
                        </li>

                        @endauth
                    </ul>
                </nav>
            </div>
        </header>
    @show

    <main class="l-main">
        @yield('main')
    </main>

    @section('footer')
        <footer class="l-footer">
            <p class="p-footer">Copyright © match All Rights Reserved</p>
        </footer>
    @show

</body>
</html>