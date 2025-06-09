<?php

namespace App\Jobs;

use App\Models\Notification as CustomNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCustomNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $notificationData;

    /**
     * Create a new job instance.
     */
    public function __construct($user, array $notificationData)
    {
        $this->user = $user;
        $this->notificationData = $notificationData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        CustomNotification::create([
            'tenant_id' => $this->user->tenant_id ?? null,
            'user_id' => $this->user->id,
            'title' => $this->notificationData['title'] ?? null,
            'message' => $this->notificationData['body'] ?? null,
            'type' => $this->notificationData['type'] ?? 'import',
            'data' => $this->notificationData,
            'is_read' => false,
            'sent_at' => now(),
        ]);
    }
}
