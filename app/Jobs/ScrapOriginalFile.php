<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ScrapOriginalFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The model holding the files.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * The attribute pointing to the file.
     * @var string
     */
    public $attribute = '';

    /**
     * Name of the filesystem disk.
     *
     * @var string
     */
    public $disk;

    public function __construct($model, $attribute, $disk = 's3')
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->disk = $disk;
    }

    public function handle()
    {
        if ($path = $this->model->getOriginal($this->attribute)) {
            Storage::disk($this->disk)->delete($path);
        }
    }
}
