<template>
    <div class="p-detail">
            
        <h2 class="p-detail__title c-title">
            <i class="fa-solid fa-magnifying-glass c-icon c-icon--title"></i>
            {{ project.title }}
        </h2>
            
        <!-- シェアとか -->
        <div class="p-detail__ c-box--flex">

            <div class="p-detail__dm">
                <button class="p-detail__dm-button c-button" v-if="this.user_id !== this.project.user_id" @click="toDirectMessage">
                    <i class="fa-solid fa-envelope c-icon"></i>
                    メッセージを送る
                </button>

            </div>
            <div class="p-detail__share">
                <button class="p-detail__share-button c-button" @click="twitterShare">
                    <i class="fa-brands fa-square-x-twitter c-icon"></i>
                    シェアする
                </button>
            </div>
        </div>

        <!-- 詳細のwrap -->
        <div class="p-detail__container">
            <div class="p-detail__thumbnail c-box--image">
                <img :src="project.thumbnail" alt="" class="p-detail__thumbnail--item c-image">
            </div>
            <div class="p-detail__type">
                <p class="c-text">【 案件種別 】</p>
                <p class="p-detail__type c-text">{{ project.type ? project.type.name : '未指定' }}</p>
            </div>
            <div class="p-detail__content">
                <p class="c-text">【 内容 】</p>
                    {{ project.content }}
            </div>
            <div class="p-detail__price" v-if="project.upperPrice !== null || project.lowerPrice !== null">
                <p class="c-text">【 料金 】 </p>
                <span class="p-detail__price-text c-text--price">{{ project.lowerPrice | numberWithCommas }}〜{{ project.upperPrice | numberWithCommas }} </span>
                円
            </div>
        </div>

        <!-- メッセ部分 -->
        <public-message-component :project_id="project_id" :user_id="user_id"></public-message-component>

    </div>

</template>

<script>
import axios from 'axios';

export default {

    props: [
        'project_id',
        'user_id', // Authユーザー
    ],

    data() {
        return {
            project : [],
        };
    },

    async mounted() {
        await this.getDetail(); // ページ読み込み時に案件情報を取得
    },

    methods: {

        // 案件詳細情報取得
        getDetail(){
            axios.get('/api/' + this.project_id + '/detail').
            then((response) => {
                this.project = response.data.project;
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

        // DM画面へ
        toDirectMessage(){
            window.location.href='/messages/' + this.user_id + '/' + this.project.user_id;
        },

        // Twitterにシェア
        twitterShare() {
            const shareURL = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent("案件名：" + this.project.title + " #match") + '&url=' + encodeURIComponent("https://yutanpo-output2.com/project/" + this.project_id + "/detail");
            window.open(shareURL, '_blank');
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