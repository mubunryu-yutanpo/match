<?php

use Illuminate\Database\Seeder;

class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $notifications = [
            [
                'receiver_id' => 2,
                'sender_id' => 1,
                'chat_id' => 1,
                'read' => true,
                'content' => '新しいメッセージがあります'
            ],
            [
                'receiver_id' => 1,
                'sender_id' => 2,
                'chat_id' => 1,
                'read' => false,
                'content' => '新しいメッセージがあります'
            ],
            [
                'receiver_id' => 3,
                'sender_id' => 1,
                'chat_id' => 2,
                'read' => true,
                'content' => '新しいメッセージがあります'
            ],
            [
                'receiver_id' => 1,
                'sender_id' => 3,
                'chat_id' => 2,
                'read' => false,
                'content' => '新しいメッセージがあります'
            ],
            [
                'receiver_id' => 3,
                'sender_id' => 2,
                'chat_id' => 3,
                'read' => true,
                'content' => '新しいメッセージがあります'
            ],
            [
                'receiver_id' => 2,
                'sender_id' => 3,
                'chat_id' => 3,
                'read' => false,
                'content' => '新しいメッセージがあります'
            ],





        ];

        // データベースにシーダーデータを挿入
        DB::table('notifications')->insert($notifications);

    }
}
