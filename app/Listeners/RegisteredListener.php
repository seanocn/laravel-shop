<?php

namespace App\Listeners;

use App\Notifications\EmailVerificationNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

//implements ShouldQueue 让这个监听器异步执行
class RegisteredListener implements ShouldQueue
{
    //当事件被触发时，对应事件的监听器的 handle() 方法会调用
    public function handle($event)
    {
        //获取到刚刚注册的用户
        $user = $event->user;
        //调用 notify 发送通知
        $user->notify(new EmailVerificationNotification());
    }
}
