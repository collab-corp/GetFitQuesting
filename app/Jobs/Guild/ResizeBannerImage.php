<?php

namespace App\Jobs\Guild;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ResizeBannerImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The guild holding the banner.
     *
     * @var \App\Guild
     */
    public $guild;

    public function __construct($guild)
    {
        $this->guild = $guild;
    }

    public function handle()
    {
        $path = $this->guild->getAttributes()['banner'];

        Storage::disk('s3')->put($path, (string) $this->banner(Storage::disk('s3')->get($path)));
    }

    protected function banner($image)
    {
        return Image::make($image)
            ->resize(1085, 175, $this->constraints($image))
            ->limitColors(255)
            ->encode();
    }

    protected function constraints($image)
    {
        return function ($constraints) use ($image) {
            [$width, $height] = getimagesizefromstring($image);
            
            if (($width / $height) >= 3.9) {
                $constraints->aspectRatio();
            }

            $constraints->upsize();
        };
    }
}
