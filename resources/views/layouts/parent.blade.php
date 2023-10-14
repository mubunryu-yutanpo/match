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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@5.0.2/css/swiper.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@5.0.2/js/swiper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-awesome-swiper@3.1.3/dist/vue-awesome-swiper.min.js"></script>

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

        <header id="header" class="l-header">
            <div class="p-header">
                <!-- アプリタイトル -->
                <h1 class="p-header__title">
                    <a href="/" class="p-header__link">match</a>
                </h1>

                <!-- SP用メニューボタン -->
                <menu-component></menu-component>

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
        <footer id="footer" class="l-footer">
            <footer-component></footer-component>
        </footer>
    @show

<script>
    // 2.5秒後にフラッシュメッセージを非表示にする
    setTimeout(function() {
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            flashMessage.style.display = 'none';
        }
    }, 2500);

</script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>