<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;
use Cache;

//邮箱验证通知类
class EmailVerificationNotification extends Notification
{
    use Queueable;

    //我们只需要通过邮件通知，因此只需要一个 email 即可
    public function via($notifiable)
    {
        return ['mail'];
    }

    // 发送邮件时会调用此方法来构建邮箱内容，参数就是 App\Models\User 对象
    public function toMail($notifiable)
    {
        //使用 Laravel 内置的 Str 类生成随机字符串的函数，参数九四生成的字符串长度
        $token = Str::random(16);
        //忘缓存中写入这个随机字符串，有效时间为 30 分钟。
        Cache::set('email_verification_'.$notifiable->email, $token, 30);
        $url = route('email_verification.verify', ['email' => $notifiable->email, 'token' => $token]);
        return (new MailMessage)
                    ->greeting($notifiable->name.'您好：')  //设置邮件欢迎词
                    ->subject('注册成功，请验证您的邮箱')       //设置邮件的标题
                    ->line('请点击下方链接验证您的邮箱')           //添加一行文字
                    ->action('验证',$url);            //添加一个激活链接
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
