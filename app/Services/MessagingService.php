<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceTable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use function PHPUnit\TestFixture\Generator\f;

class MessagingService
{
    public const TYPE_NOTIFICATION = 'notification';
    public const TYPE_EMAIL = 'email';

    private static function sendMail(string $address, string $subject, string $content)
    {
        try {
            Mail::html($content, function($message) use ($address, $subject) {
                $message->to($address);
                $message->subject($subject);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send email: ' . $e->getMessage());
        }
    }

    private static function sendNotification(User $user, $content)
    {
        $notification = new Notification();
        $notification->user_id = $user->id;
        $notification->value = $content;
        $notification->save();
    }
    public static function sendToUser(User $user, array $types, $content, $subject)
    {
        $address = $user->email;
        if (in_array(self::TYPE_EMAIL, $types)) {
            static::sendMail($address, $subject, $content);
        }
        if (in_array(self::TYPE_NOTIFICATION, $types)) {
            static::sendNotification($user, $content);
        }
    }

    public static function sendToWorkspace(Workspace $workspace, array $types, $content, $subject)
    {
        foreach ($workspace->users as $user) {
            static::sendToUser($user, $types, $content, $subject);
        }
    }
}
?>
