<template>
    <div class="p-public-message">
        <h3 class="c-title p-public-message__title">コメント</h3>
        <!-- コメント表示/非表示ボタン -->
        <button class="p-public-message__toggle-button c-button" @click="toggleShowComment()">
            <span class="p-public-message__toggle-text" v-if="!showComment">コメントを表示</span>
            <span class="p-public-message__toggle-text" v-if="showComment">コメントを非表示</span>
             ▼
        </button>

        <!-- 全体のwrap -->
        <div class="p-public-message__container" v-if="showComment">
            
            <!-- メッセージを表示するエリア -->
            <div class="p-message">
                <div class="p-message__container" v-for="message in messageList" :key="message.id">
                    <!-- ユーザー -->
                    <div class="p-user c-box--flex" :class="{'is-me': seller_id === message.user.id , 'is-other': seller_id !== message.user.id}">
                        <div class="p-user__image c-box--avatar">
                            <img :src="message.user.avatar" class="p-user__image-item c-image">
                        </div>
                        <p class="p-user__name">
                            <a :href="'/user/info/' + message.user.id" class="p-user__name-link c-link">{{ message.user.name }}</a>
                        </p>
                    </div>
                    <!-- メッセージ -->
                    <div class="c-talk" :class="{'c-talk--me': seller_id === message.user.id , 'c-talk--other': seller_id !== message.user.id}">
                        <p class="c-talk__text">{{ message.comment }}</p>
                    </div>
                </div>
            </div>
            
            <!-- メッセージ入力＆送信エリア -->
            <form @submit.prevent="addMessage" class="p-message-form">
                <p class="p-message-form__description">この案件に対する質問などをコメントできます（※255文字以内）</p>
                <div class="p-message-form__container">
                    <textarea 
                        class="p-message-form__area c-textarea"
                        :class="{'c-error': countText > max }"
                        v-model="newMessage"
                        placeholder="メッセージを送信"
                        @input="updateCount"
                    ></textarea>
                    <button type="submit" class="c-button p-message-form__button">
                        <i class=" fa-solid fa-paper-plane"></i>
                    </button>
                </div>
                <p class="p-message-form__count-text" :class="{'c-error c-error--text': countText > max }"> {{ countText }} / {{ max }}</p>
            </form>
        </div>

    </div>

</template>

<script>
import axios from 'axios';

export default {
    props: [
        'project_id',
        'user_id',
    ],

    data(){
        return{
            messageList : [],
            seller_id: null,
            newMessage: '',
            showComment : true,
            max: 255,
            countText: 0, // 文字数カウントを初期化
        };
    },

    async mounted() {
        await this.getMessages(); // ページ読み込み時に案件情報を取得
    },

    methods: {

        // メッセージ情報取得
        getMessages(){
            axios.get('/api/' + this.project_id + '/publicMessages').
            then((response) => {
                this.messageList = response.data.messageList;
                this.seller_id = response.data.seller_id;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        // メッセージ追加
        addMessage() {
            axios
            .post('/api/project/' + this.project_id + '/' + this.user_id + '/publicMessage', { comment: this.newMessage })
            .then((response) => {
                // メッセージの送信後にメッセージを再取得する
                this.getMessages();
                this.newMessage = ''; // 送信後、入力欄をクリアする
                this.countText = 0;// カウンターをリセット
            })
            .catch((error) => {
                console.error(error);
            });
        },

        // コメント表示切り替え
        toggleShowComment(){
            this.showComment = !this.showComment;
        },

        // 入力文字数をカウント
        updateCount() {
            this.countText = this.newMessage.length;
        },


        // 日付の表示を変更
        formatDate(value) {
            const date = new Date(value);
            const year = date.getFullYear();
            const month = date.getMonth() + 1;
            const day = date.getDate();
            return `${year}.${month}.${day}`;
        },

    },

    filters: {

        // 値段の表記。コンマ区切りにする。
        numberWithCommas(value) {
            if (value === 0) {
                return '0';
            }
            if (!value) {
                return '';
            }
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
    },

}
</script>