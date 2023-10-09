<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = [
            [
                'user_id'    => 1,
                'title'      => 'バナー制作依頼',
                'type'       => 1,
                'thumbnail'  => 'uploads/img19.png',
                'upperPrice' => 30000,
                'lowerPrice' => 10000,
                'content'    => 'バナーを作って欲しいんだよね。',
            ],
            [
                'user_id'    => 2,
                'title'      => '【どんな相手でも対応可】魔法少女派遣します',
                'type'       => 1,
                'thumbnail'  => 'uploads/img18.png',
                'upperPrice' => 50000,
                'lowerPrice' => 4000,
                'content'    => 'うちの魔法少女は強いので誰が相手でも問題ありません',
            ],
            [
                'user_id'    => 3,
                'title'      => 'こども博士、創造計画',
                'type'       => 2,
                'thumbnail'  => 'uploads/image9.png',
                'upperPrice' => null,
                'lowerPrice' => null,
                'content'    => 'こどもの「好き」を伸ばします。こどもが楽しんで自主的に勉強できる環境を作りましょう',
            ],


        ];

        // データベースにシーダーデータを挿入
        DB::table('projects')->insert($projects);

    }
}
