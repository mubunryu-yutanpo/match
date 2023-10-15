<?php

use Illuminate\Database\Seeder;

class ApplyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applies = [
            [
                'user_id'    => 1,
                'project_id' => 2,
            ],
            [
                'user_id'    => 1,
                'project_id' => 3,
            ],
            [
                'user_id'    => 2,
                'project_id' => 1,
            ],
            [
                'user_id'    => 2,
                'project_id' => 3,
            ],
            [
                'user_id'    => 3,
                'project_id' => 1,
            ],
            [
                'user_id'    => 3,
                'project_id' => 2,
            ],

        ];

        // データベースにシーダーデータを挿入
        DB::table('apply')->insert($applies);

    }
}
