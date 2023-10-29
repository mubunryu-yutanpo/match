<!-- Vue側 -->
<template>
    <div class="p-counter">
        <label :for="name" class="c-label p-counter__label">*{{ label }}:</label>
        <textarea
        :name="name"
        :id="id"
        cols="30"
        rows="10"
        class="c-textarea p-counter__textarea"
        :class="{ 'c-error': count > max }"
        :autocomplete="autocomplete"
        :placeholder="placeholder"
        v-model="countText"
        @input="updatecount"
        required
        ></textarea>

        <p class="p-counter__text" :class="{'c-error c-error--text': count > max }">
        {{ count }}/{{ max }}
        </p>
    </div>
</template>

<script>
export default {
    props: {
        label: String,
        errors: Boolean,
        max: Number,
        name: String,
        placeholder: String,
        autocomplete: String,
        id: String,
        data: String,
    },

    data() {
        return {
            countText: this.data || '',
            count: 0,
            placeholderText: this.placeholder,
        };
    },

    methods: {

        // 入力文字数を反映
        updatecount() {
        this.count = this.countText.length;
        this.storeComment();
        },

        // 入力内容をセッションストレージに保存
        storeComment() {
            sessionStorage.setItem(this.id, this.countText);
        },

        // 入力内容をセッションストレージから取得し復元
        getComment() {
            const storedComment = sessionStorage.getItem(this.id);
            if (storedComment !== null) {
                this.countText = storedComment;
                this.count = storedComment.length;
            }
        },
    },

    mounted() {
        this.count = this.countText.length;
        this.getComment();
    },
};
</script>
