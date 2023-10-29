<template>

    <div class="p-list">
        <h2 class="p-list__title c-title">
        <i class="fa-solid fa-list c-icon c-icon--title"></i>
        メッセージをした案件一覧
        </h2>

        <!-- 一覧 -->
        <section class="p-list__container c-box--flex">
            
            <!-- 各案件 -->
            <div class="p-project" v-for="pub in paginatedProjects" :key="pub.id">
                
                <h4 class="p-project__title c-title">
                    <a :href="'/project/' + pub.project.id + '/detail'" class="p-project__link c-link">{{ pub.project.title }}</a>
                </h4>

                <div class="p-project__image">
                    <img :src="pub.project.thumbnail" class="p-project__image-item c-image">
                    <p class="p-project__type c-text--type">{{ pub.project.type.name }}</p>
                </div>

                <div class="p-project__content">
                    <p class="c-text">【 最新のメッセージ 】</p>
                    {{ pub.comment }}
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
            projects: [],
            currentPage: 1,
            projectsPerPage: 3, // ページネーションのテスト用にとりあえず3に
        };
    },

    async mounted() {
        await this.getPublicMessageList(); // 投稿した案件情報を取得
        this.filterAndPaginateProjects(); // 初回データ取得後にページネーションを適用
    },

    computed: {

        // 現在のページの開始インデックスと終了インデックスを計算する
        startIndex() {
            return (this.currentPage - 1) * this.projectsPerPage;
        },

        endIndex() {
            return this.startIndex + this.projectsPerPage;
        },

                // フィルタリングとページネーションを適用したプロジェクトリスト
        paginatedProjects: {

            get() {
                const projects = this.projects;

                // ページネーション
                const startIndex = this.startIndex;
                const endIndex = this.endIndex;
                return projects.slice(startIndex, endIndex);
            },
            set(value) {
                // このセッターは読み取り専用のため、何も行わない
            },
        },

        // フィルタリングされた案件の長さとprojectsPerPageを元に、総ページ数を計算する
        totalPages() {
            return Math.ceil(this.projects.length / this.projectsPerPage);
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
        getPublicMessageList() {
            axios
                .get('/api/' + this.user_id + '/publicMessageList')
                .then((response) => {
                    this.projects = response.data.publicMessageList;
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
        filterAndPaginateProjects() {
            const startIndex = this.startIndex;
            const endIndex = this.endIndex;
            this.paginatedProjects = this.projects.slice(startIndex, endIndex);
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
