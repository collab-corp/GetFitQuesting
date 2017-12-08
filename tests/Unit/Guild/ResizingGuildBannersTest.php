<?php

namespace Tests\Unit\Guild;

use App\Guild;
use App\Jobs\Guild\ResizeBannerImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResizingGuildBannersTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function itResizesBannerImages()
    {
        Bus::fake();

        Storage::fake('s3');

        $guild = create(Guild::class, [
        	'banner' => File::image('banner.png', 1280, 1024)->storeAs('guild', 'banner.png', 's3')
        ]);

        Bus::assertDispatched(ResizeBannerImage::class, function ($job) use ($guild) {
            if ($job->guild->is($guild)) {
                $this->app->call([$job, 'handle']);

                return true;
            }

            return false;
        });

        $result = Storage::disk('s3')->get('guild/banner.png');
        [$width, $height] = getimagesizefromstring($result);
        
        $this->assertEquals(1085, $width);
        $this->assertEquals(175, $height);
    }
}
