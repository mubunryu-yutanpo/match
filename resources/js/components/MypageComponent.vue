<template>
    <div class="p-mypage">
        
        <h2 class="c-title c-title--page-title">
            <i class="fa-solid fa-user c-icon c-icon--title"></i>
            マイページ
        </h2>

        <!-- ユーザー情報 -->
        <section class="p-user c-box--flex c-box--flex-2">
            <p class="p-user__name c-text">{{ this.user.name }}</p>
            <div class="p-user__image c-box--avatar">
                <img :src="user.avatar" alt="" class="p-user__image-item c-image">
            </div>
        </section>

        <!-- 各リスト -->
        <section class="p-mypage__wrap">

            <!-- 投稿案件一覧 -->
            <div class="p-mypage-list">
                <h3 class="p-mypage-list__title c-title c-title--sub-title">投稿した案件一覧</h3>
                <div class="p-mypage-list__container c-box--flex" v-if="postList.length > 0" >

                    <div class="p-project" v-for="post in postList" :key="post.id">
                        <p class="p-project__title c-title">
                            <a :href="'/project/' + post.id + '/detail'" class="c-link p-project__link">{{ post.title }}</a>
                        </p>
                    
                        <div class="p-project__image">
                            <p class="p-project__type c-text--type">{{ post.type.name }}</p>
                            <img :src="post.thumbnail" alt="" class="c-image p-project__image-item">
                        </div>

                        <p class="p-project__content c-text">【 内容 】{{ post.content }}</p>
                    </div>
                    
                    <div class="p-mypage-list__container--link c-box--link">
                        <a :href="'/postList/'+ this.user.id" class="c-link p-mypage-list__link" v-if="postList.length > 2">全件表示する</a>
                    </div>
                </div>

                <div v-else class="p-mypage-list__none">
                    <strong>投稿した案件はまだありません</strong>
                </div>
            </div>

            <!-- 応募案件一覧 -->
            <div class="p-mypage-list">
                <h3 class="p-mypage-list__title c-title c-title--sub-title">応募した案件一覧</h3>
                <div class="p-mypage-list__container c-box--flex" v-if="applyList.length > 0" >

                    <div class="p-project" v-for="apply in applyList" :key="apply.id">
                        <p class="p-project__title c-title">
                            <a :href="'/project/' + apply.project.id + '/detail'" class="c-link p-project__link">{{ apply.project.title }}</a>
                        </p>
                    
                        <div class="p-project__image">
                            <p class="p-project__type c-text--type">{{ apply.project.type.name }}</p>
                            <img :src="apply.project.thumbnail" alt="" class="c-image p-project__image-item">
                        </div>

                        <p class="p-project__content c-text">【 内容 】{{ apply.project.content }}</p>
                    </div>

                    <div class="p-mypage-list__container--link c-box--link">
                        <a :href="'/applyList/'+ this.user.id" class="c-link p-mypage-list__link" v-if="applyList.length > 3 " >全件表示する</a>
                    </div>
                </div>

                <div v-else class="p-mypage-list__none">
                    <strong>応募した案件はまだありません</strong>
                </div>
            </div>

            <!-- パブリックM一覧 -->
            <div class="p-mypage-list">
                <h3 class="p-mypage-list__title c-title c-title--sub-title">パブリックメッセージ一覧</h3>
                <div class="p-mypage-list__container c-box--flex" v-if="publicMessageList.length > 0" >

                    <div class="p-project" v-for="pubMessage in publicMessageList" :key="pubMessage.id">
                        <p class="p-project__title c-title">
                            <a :href="'/project/' + pubMessage.project.id + '/detail'" class="c-link p-project__link">{{ pubMessage.project.title }}</a>
                        </p>
                    
                        <div class="p-project__image">
                            <p class="p-project__type c-text--type">{{ pubMessage.project.type.name }}</p>
                            <img :src="pubMessage.project.thumbnail" alt="" class="c-image p-project__image-item">
                        </div>

                        <p class="p-project__content c-text">【 最新のコメント 】{{ pubMessage.comment }}</p>
                    </div>

                    <div class="p-mypage-list__container--link c-box--link">
                        <a :href="'/publicMessageList/'+ this.user.id" class="c-link p-mypage-list__link"  v-if="publicMessageList.length > 3">全件表示する</a>
                    </div>

                </div>
                <div v-else class="p-mypage-list__none">
                    <strong>メッセージはまだありません</strong>
                </div>
            </div>

            <!-- DM一覧 -->
            <div class="p-mypage-list">
                <h3 class="p-mypage-list__title c-title c-title--sub-title">ダイレクトメッセージ一覧</h3>
                <div class="p-mypage-list__message c-box--flex c-box--flex-column" v-if="directMessageList.length > 0" >

                    <!-- 各チャットの最新の1件のみ表示 -->
                    <div class="p-message-list c-box--message" v-for=" dm in directMessageList" :key="dm.id">
                        
                        <p class="p-message-list__text c-text">【 メッセージの相手 】</p>
                        <div class="p-message-list__container c-box--flex c-box--flex-1">
                            <p class="p-message-list__user-name">{{ dm.other_user.name }}</p>
                            <div class="p-message-list__user-image c-box--avatar">
                                <img :src="dm.other_user.avatar" class="p-message-list__user-image-item c-image">
                            </div>
                        </div>

                        <p class="p-message-list__text c-text">【 状態 】</p>
                        <p class="p-message-list__text c-text" v-if="dm.isRead">既読</p>
                        <p class="p-message-list__text c-text c-text--attention" v-else>メッセージを確認してください</p>

                        <p class="p-message-list__text c-text">【 最新のコメント 】</p>
                        <p class="p-message-list__text c-text">{{ dm.message.comment }}</p>

                        
                        <div class="p-message-list__link-box c-box--link">
                            <a @click="markAsRead(dm.message.chat_id, dm.other_user.id, user.id)" class="c-link p-message-list__link">このメッセージへ</a>
                        </div>


                        
                    </div>

                    <div class="p-mypage-list__container--link c-box--link">
                        <a :href="'/directMessageList/'+ this.user.id" class="c-link p-mypage-list__link" v-if="directMessageList.length > 3">全件表示する</a>
                    </div>
                </div>
                <div v-else class="p-mypage-list__none">
                    <strong>メッセージはまだありません</strong>
                </div>
            </div>

        </section>

    </div>
</template>

<script>
import axios from 'axios';

export default {

    props: ['user'],

    data() {
        return {
            postList : [],
            applyList: [],
            publicMessageList: [],
            directMessageList: [],
        };
    },

    async mounted() {
        await this.getMypage(); // ページ読み込み時にマイページ情報を取得
    },

    methods: {

        // 案件詳細情報取得
        getMypage(){
            axios.get('/api/' + this.user.id + '/mypage').
            then((response) => {
                this.postList  = response.data.postList;
                this.applyList = response.data.applyList;
                this.publicMessageList = response.data.publicMessageList;
                this.directMessageList = response.data.directMessageList;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        markAsRead(chatId, senderId, receiverId) {
            // APIリクエストを送信して既読に設定
            axios.post('/api/markAsRead/' + chatId + '/' + senderId + '/' + receiverId)
            .then((response) => {
                // チャットへの遷移
                console.log('既読化処理成功');
                window.location.href = '/messages/' + receiverId + '/' + senderId;
            })
            .catch((error) => {
                console.error(error);
            });
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

