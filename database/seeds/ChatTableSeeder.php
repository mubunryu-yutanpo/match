<?php

use Illuminate\Database\Seeder;

class ChatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chats = [
            [
                'user1_id'    => 1,
                'user2_id' => 2,
            ],
            [
                'user1_id'    => 1,
                'user2_id' => 3,
            ],
            [
                'user1_id'    => 2,
                'user2_id' => 3,
            ],


        ];

        // データベースにシーダーデータを挿入
        DB::table('chats')->insert($chats);
    }
}
