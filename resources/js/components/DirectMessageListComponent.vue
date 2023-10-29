<template>

    <div class="p-list">
        <h2 class="p-list__title c-title">
            <i class="fa-solid fa-comments c-icon c-icon--title"></i>
            メッセージ一覧
        </h2>

        <!-- 一覧 -->
        <section class="p-list__wrap c-box--flex c-box--flex-column">
            
            <div class="p-list__none" v-if="messages === null || messages.length === 0">
                まだメッセージはありません
            </div>

            <!-- 各チャットの最新の1件のみ表示 -->
            <div class="p-message-list c-box--message" v-for=" dm in PaginatedMessages" :key="dm.id">
                
                <!-- ユーザー情報 -->
                <p class="p-message-list__text c-text">【 メッセージの相手 】</p>
                <div class="p-message-list__container c-box--flex c-box--flex-1">
                    <p class="p-message-list__user-name">{{ dm.other_user.name }}</p>
                    <div class="p-message-list__user-image c-box--avatar">
                        <img :src="dm.other_user.avatar" class="p-message-list__user-image-item c-image">
                    </div>
                </div>

                <!-- 日付 -->
                <p class="p-message-list__text c-text">【 日付 】</p>
                <p class="p-message-list__text c-text">{{ formatDate(dm.message.created_at) }}</p>

                <!-- 既読の状態 -->
                <p class="p-message-list__text c-text">【 状態 】</p>
                <p class="p-message-list__text c-text" v-if="dm.isRead">既読</p>
                <p class="p-message-list__text c-text c-text--attention" v-else>メッセージを確認してください</p>

                <!-- 内容 -->
                <p class="p-message-list__text c-text">【 最新のコメント 】</p>
                <p class="p-message-list__text c-text">{{ dm.message.comment }}</p>
                
                <div class="p-message-list__link-box c-box--link">
                    <a @click="markAsRead(dm.message.chat_id, dm.other_user.id, user_id)" class="c-link p-message-list__link">このメッセージへ</a>
                </div>

            </div>
        </section>

        <!-- ページネーション -->
        <section class="p-pagination">
            <!-- 最初のページへ -->
            <button
                v-if="currentPage > 1"
                class="p-pagination__button c-button"
                @click="changePage(1)"
            >
                ＜
            </button>

            <!-- 前のページボタン -->
            <button
                v-if="currentPage > 2"
                class="p-pagination__button c-button"
                @click="changePage(currentPage - 1)"
            >
                prev
            </button>

            <!-- 動的に変化するページボタン -->
            <button
                v-for="pageNumber in visiblePageNumbers"
                :key="pageNumber"
                class="p-pagination__button c-button"
                :class="{ active: currentPage === pageNumber }"
                @click="changePage(pageNumber)"
            >
                {{ pageNumber }}
            </button>

            <!-- 次のページボタン -->
            <button
                v-if="currentPage < totalPages - 1"
                class="p-pagination__button c-button"
                @click="changePage(currentPage + 1)"
            >
                next
            </button>

            <!-- 最終ページ -->
            <button
                v-if="currentPage < totalPages"
                class="p-pagination__button c-button"
                @click="changePage(totalPages)"
            >
                ＞
            </button>
        </section>
    </div>
</template>

<script>
import axios from 'axios';

export default {

    props: ['user_id'],
    
    data() {
        return {
            messages: [],
            currentPage: 1,
            messagesPerPage: 3, // ページネーションのテスト用にとりあえず3に
        };
    },

    async mounted() {
        await this.getDirectMessageList(); // 投稿した案件情報を取得
        this.filterAndPaginateMessages(); // 初回データ取得後にページネーションを適用
    },

    computed: {

        // 現在のページの開始インデックスと終了インデックスを計算する
        startIndex() {
            return (this.currentPage - 1) * this.messagesPerPage;
        },

        endIndex() {
            return this.startIndex + this.messagesPerPage;
        },

                // フィルタリングとページネーションを適用したプロジェクトリスト
        PaginatedMessages: {

            get() {
                const messages = this.messages;

                // ページネーション
                const startIndex = this.startIndex;
                const endIndex = this.endIndex;
                return messages.slice(startIndex, endIndex);
            },
            set(value) {
                // このセッターは読み取り専用のため、何も行わない
            },
        },

        // フィルタリングされた案件の長さとmessagesPerPageを元に、総ページ数を計算する
        totalPages() {
            return Math.ceil(this.messages.length / this.messagesPerPage);
        },

        // ページネーションで表示するページ番号のリストを取得
        visiblePageNumbers() {
            const totalPages = this.totalPages;
            const currentPage = this.currentPage;
            const maxVisiblePages = 3;

            if (totalPages <= maxVisiblePages) {
                return Array.from({ length: totalPages }, (_, i) => i + 1);
            } else {
                // 真ん中のボタン
                const middlePage = Math.floor(maxVisiblePages / 2) + 1;

                if (currentPage <= middlePage) {
                    return Array.from({ length: maxVisiblePages }, (_, i) => i + 1);
                } else if (currentPage >= totalPages - middlePage + 1) {
                    return Array.from({ length: maxVisiblePages }, (_, i) => totalPages - maxVisiblePages + i + 1);
                } else {
                    return Array.from({ length: maxVisiblePages }, (_, i) => currentPage - middlePage + i);
                }
            }
        },
    },

    methods: {

        // 案件情報取得
        getDirectMessageList() {
            axios
                .get('/api/' + this.user_id + '/directMessageList')
                .then((response) => {
                    this.messages = response.data.directMessageList;
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        // ページネーションのページ変更を処理する
        changePage(pageNumber) {
            this.currentPage = pageNumber;
        },

        // ページネーションを適用したリストを取得
        filterAndPaginateMessages() {
            const startIndex = this.startIndex;
            const endIndex = this.endIndex;
            this.PaginatedMessages = this.messages.slice(startIndex, endIndex);
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
};
</script>
