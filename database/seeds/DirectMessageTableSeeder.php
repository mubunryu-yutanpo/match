<?php

use Illuminate\Database\Seeder;

class DirectMessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $direct_messages = [
            [
                'sender_id' => 1,
                'chat_id' => 1,
                'comment' => '私はテストユーザー1です',
            ],
            [
                'sender_id' => 2,
                'chat_id' => 1,
                'comment' => '私はテストユーザー2です',
            ],
            [
                'sender_id' => 1,
                'chat_id' => 2,
                'comment' => 'こんにちは！こんばんは？',
            ],
            [
                'sender_id' => 3,
                'chat_id' => 2,
                'comment' => 'なんですと？',
            ],
            [
                'sender_id' => 2,
                'chat_id' => 3,
                'comment' => '私はあなたではありません',
            ],
            [
                'sender_id' => 3,
                'chat_id' => 3,
                'comment' => 'その通りですね',
            ],


        ];

        // データベースにシーダーデータを挿入
        DB::table('direct_messages')->insert($direct_messages);

    }
}
