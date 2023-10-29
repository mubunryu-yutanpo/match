@extends('layouts.parent')

@section('title', '新規案件登録')

@section('header')
    @parent
@show

@section('main')

    <form action="{{ route('create') }}" method="post" class="p-create c-box--form" enctype="multipart/form-data">
        @csrf

        <h2 class="p-create__title c-title">
            <i class="fa-solid fa-pen c-icon c-icon--title"></i>案件登録
        </h2>

        <!-- 案件タイプ -->
        <div class="p-create__container c-box--form-container">
            <label for="type" class="c-label p-create__label">*案件の種類:</label>
                <select name="type" id="type" class="c-select p-create__select @error('type') c-error @enderror">

                    <option value="" hidden>選択してください</option>
                    
                    @foreach ($projectType as $type)
                        <option value="{{ $type['id'] }}" {{ old('type', $project->type ?? '') == $type['id'] ? 'selected' : '' }}>{{ $type['name'] }}</option>
                    @endforeach
                </select>

                @error('type')
                    <p class="c-error c-error--text p-create__error-text" role="alert">
                        {{ $message }}
                    </p>
                @enderror
        </div>


        <!-- 案件名 -->
        <div class="p-create__container c-box--form-container">
            <label for="title" class="c-label p-create__label">*タイトル:</label>
                <input id="title" type="text" class="c-input p-create__input @error('title') c-error @enderror" name="title" value="{{ old('title', $user->title) }}" required autocomplete="title" autofocus>
                @error('title')
                    <p class="c-error c-error--text p-create__error-text" role="alert">
                        {{ $message }}
                    </p>
                @enderror
        </div>

        <!-- 表示を切り替えるフォーム部分 -->
        <div id="priceFields" style="display: none;">

            <!-- 料金（上限） -->
            <div class="p-create__container c-box--form-container">
                <label for="upperPrice" class="c-label p-create__label">*料金の上限:</label>
                    <input id="upperPrice" type="number" class="c-input p-create__input @error('upperPrice') c-error @enderror" name="upperPrice" value="{{ old('upperPrice') }}" autofocus placeholder="〜99999（単位：千円）">
                    @error('upperPrice')
                        <p class="c-error c-error--text p-create__error-text" role="alert">
                            {{ $message }}
                        </p>
                    @enderror
            </div>

            <!-- 料金（下限） -->
            <div class="p-create__container c-box--form-container">
                <label for="lowerPrice" class="c-label p-create__label">*料金の下限:</label>
                    <input id="lowerPrice" type="number" class="c-input p-create__input @error('lowerPrice') c-error @enderror" name="lowerPrice" value="{{ old('lowerPrice') }}" autofocus placeholder="1〜（単位：千円）">
                    @error('lowerPrice')
                        <p class="c-error c-error--text p-create__error-text" role="alert">
                            {{ $message }}
                        </p>
                    @enderror
            </div>
        </div>

        <!-- サムネ画像 -->
        <div id="app">
            <thumbnail-preview-component :project_id="{{ $project->id ?? 'null' }}"></thumbnail-preview-component>
        </div>

        <!-- 案件内容 -->
        <div class="p-create__container c-box--form-container">
                <!-- テキストカウンター用コンポーネント -->
                <div id="counter">
                    <text-counter-component
                        label="案件の内容"
                        name="content"
                        id="content"
                        :max="2000"
                        autocomplete="content"
                        placeholder="2,000文字以内で入力してください"
                    ></text-counter-component>
                </div>
                @error('content')
                    <p class="c-error c-error--text p-create__error-text" role="alert">
                        {{ $message }}
                    </p>
                @enderror
        </div>

        <div class="p-submit">
            <button class="c-button p-submit__button">登録する！</button>
        </div>

    </form>


@endsection

@section('footer')
    @parent
@show

<script>
    // 料金部分の表示切り替え（レベニューシェア案件の場合は非表示に）
    document.addEventListener('DOMContentLoaded', () => {
        
        // セレクトボックスの要素を取得
        const typeSelect = document.getElementById('type');

        // セレクトボックスの値が変更されたときに実行される関数
        typeSelect.addEventListener('change', () => {
            
            // セレクトボックスの値を取得
            const selectedValue = typeSelect.value;

            // レベニューシェア案件の場合は非表示に
            if (selectedValue === '1') {
                priceFields.style.display = 'block';
            } else {
                priceFields.style.display = 'none';
            }
        });
    });
</script>





