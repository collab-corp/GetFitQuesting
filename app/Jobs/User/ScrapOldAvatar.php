<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ScrapOldAvatar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The user model.
     * 
     * @var \App\User
     */
    public $user;

    /**
     * Name of the filesystem disk.
     * 
     * @var string
     */
    public $disk;

    public function __construct($user, $disk = 's3')
    {
        $this->user = $user;
        $this->disk = $disk;
    }

    public function handle()
    {
        $path = $this->user->getOriginal('avatar');

        if (is_url($path)) {
            return true;
        }

        Storage::disk($this->disk)->delete($path);
    }
}
