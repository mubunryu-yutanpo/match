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
                'thumbnail'  => '/storage/uploads/img19.png',
                'upperPrice' => 30000,
                'lowerPrice' => 10000,
                'content'    => 'バナーを作って欲しいんだよね。',
            ],
            [
                'user_id'    => 2,
                'title'      => '【どんな相手でも対応可】魔法少女派遣します',
                'type'       => 1,
                'thumbnail'  => '/storage/uploads/img18.png',
                'upperPrice' => 50000,
                'lowerPrice' => 4000,
                'content'    => 'うちの魔法少女は強いので誰が相手でも問題ありません',
            ],
            [
                'user_id'    => 3,
                'title'      => 'こども博士、創造計画',
                'type'       => 2,
                'thumbnail'  => '/storage/uploads/image9.png',
                'upperPrice' => null,
                'lowerPrice' => null,
                'content'    => 'こどもの「好き」を伸ばします。こどもが楽しんで自主的に勉強できる環境を作りましょう',
            ],
            [
                'user_id'    => 1,
                'title'      => '秋の大運動会開催決定！スポンサー求む',
                'type'       => 2,
                'thumbnail'  => '/storage/uploads/img23.jpg',
                'upperPrice' => null,
                'lowerPrice' => null,
                'content'    => 'ついに開催が決定しました！一緒に盛り上げてくれるスポンサー企業を募集します。',
            ],


        ];

        // データベースにシーダーデータを挿入
        DB::table('projects')->insert($projects);

    }
}
