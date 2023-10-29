@extends('layouts.parent')

@section('title', '案件の編集')

@section('header')
    @parent
@show

@section('main')
    <form action="{{ route('project.update', $project->id) }}" method="post" class="p-edit c-box--form" enctype="multipart/form-data">
        @csrf

        <h2 class="c-title p-edit__title">
            <i class="fa-solid fa-pen c-icon c-icon--title"></i>案件の編集
        </h2>

        <!-- 案件タイプ -->
        <div class="p-edit__container c-box--form-container">
            <label for="type" class="c-label p-edit__label">案件の種類:</label>
                <select name="type" id="type" class="c-select p-edit__select @error('type') valid-error @enderror" value="">

                    <option value="" hidden>選択してください</option>
                    
                    @foreach ($projectType as $type)
                        <option value="{{ $type['id'] }}" {{ old('type', $project->type ?? '') == $type['id'] ? 'selected' : '' }}>{{ $type['name'] }}</option>
                    @endforeach
                </select>

                @error('type')
                    <span class="c-error c-error--text p-edit__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>


        <!-- 案件名 -->
        <div class="p-edit__container c-box--form-container">
            <label for="title" class="c-label p-edit__label">*タイトル:</label>
                <input id="title" type="text" class="c-input p-edit__input @error('title') valid-error @enderror" name="title" value="{{ old('title', $project->title) }}" required autocomplete="title" autofocus>
                @error('title')
                    <span class="c-error c-error--text p-edit__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <!-- 表示を切り替えるフォーム部分 -->
        <div id="priceFields" style="display: none;">
            <!-- 料金（上限） -->
            <div class="p-edit__container c-box--form-container">
                <label for="upperPrice" class="c-label p-edit__label">*料金上限（単位：千円）:</label>
                    <input id="upperPrice" type="number" class="c-input p-edit__input @error('upperPrice') valid-error @enderror" name="upperPrice" value="{{ old('upperPrice', $savedUpperPrice ) }}" required autofocus placeholder="〜999999">
                    @error('upperPrice')
                        <span class="c-error c-error--text p-edit__error-text" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
            </div>

            <!-- 料金（下限） -->
            <div class="p-edit__container c-box--form-container">
                <label for="lowerPrice" class="c-label p-edit__label">*料金下限（単位：千円）:</label>
                    <input id="lowerPrice" type="number" class="c-input p-edit__input @error('lowerPrice') valid-error @enderror" name="lowerPrice" value="{{ old('lowerPrice', $savedLowerPrice ) }}" required autofocus placeholder="1〜">
                    @error('lowerPrice')
                        <span class="c-error c-error--text p-edit__error-text" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
            </div>

        </div>

        <!-- サムネ画像 -->
        <div id="app">
            <thumbnail-preview-component :project_id="{{ $project->id ?? 'null' }}"></thumbnail-preview-component>
        </div>

        <!-- 案件内容 -->
        <div class="p-edit__container c-box--form-container">
            <!-- テキストカウンター用コンポーネント -->
            <div id="counter">
                <text-counter-component
                    label="案件の内容"
                    name="content"
                    id="content"
                    :max="2000"
                    autocomplete="content"
                    placeholder="2,000文字以内で入力してください"
                    data="{{ $project->content }}"
                ></text-counter-component>
            </div>

            @error('content')
                <span class="c-error c-error--text p-edit__error-text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-submit">
            <button class="c-button p-submit__button">更新して保存</button>
        </div>

    </form>

    <form action="{{ route('project.delete', $project->id) }}" method="post" class="p-delete c-box--form">
        @csrf

        <div class="p-submit">
            <button class="p-submit__button--delete c-button" onclick="return confirm('この案件を削除します。よろしいですか？')">削除する</button>
        </div>
    </form>


@endsection

@section('footer')
    @parent
@show

<script>
    // 料金部分の表示切り替え
    document.addEventListener('DOMContentLoaded', () => {
        // セレクトボックスの要素を取得
        const typeSelect = document.getElementById('type');
        const priceFields = document.getElementById('priceFields'); // 金額部分の要素

        // 初期状態を確認
        if (typeSelect.value === '1') {
            priceFields.style.display = 'block';
        } else {
            priceFields.style.display = 'none';
        }

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
