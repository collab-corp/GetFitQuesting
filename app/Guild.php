<?php

namespace App;

use App\Jobs\Guild\ResizeBannerImage;
use App\Jobs\ScrapOriginalFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;
use Watson\Rememberable\Rememberable;

class Guild extends Model implements HasMediaConversions
{
    use Searchable, Rememberable, HasMediaTrait, SoftDeletes;

    protected $fillable = [
        'members_count', 'creator_id', 'name', 'description', 'banner'
    ];

    /**
     * Boot up the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($guild) {
            if ($guild->isDirty('banner')) {
                ResizeBannerImage::dispatch($guild);

                if (is_string($guild->getOriginal('banner'))) {
                    ScrapOriginalFile::dispatch($guild, 'banner');
                }
            }
        });
    }

    /**
     * Get the guilds created by given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $user
     * @return void
     */
    public function scopeCreatedBy($query, $user)
    {
        $query->whereHas('creator_id', is_scalar($user) ? $user : data_get($user, 'id'));
    }

    /**
     * The guild creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * The guild members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(GuildMember::class);
    }

    /**
     * A guild may have teams composed of members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get the url to the banner or placeholder image.
     *
     * @param  null | string $value
     * @return string
     */
    public function getBannerAttribute($value = null)
    {
        if ($value) {
            return Storage::url($value);
        }

        return 'http://via.placeholder.com/1085x175';
    }

    /**
     * Add media files to the guild.
     *
     * @param mixed  $files
     * @param string $collection
     */
    public function addMediaFiles($files, string $collection = 'default')
    {
        collect($files)->each(function ($file) {
            if (is_string($file) && Str::startsWith($file, ['http', 'https'])) {
                $this->addMediaFromUrl($file)
                     ->toMediaCollection($collection);
            } else {
                $this->addMedia($file)
                     ->toMediaCollection($collection);
            }
        });
    }

    /**
     * Convert given media
     *
     * @param  Media|null $media
     * @return void
     */
    public function registerMediaConversions(Media $media = null)
    {
        //
    }
    
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
           'members_count' => $this->members_count,
           'description' => $this->description,
           'creator' => $this->creator->name,
           'name' => $this->name,
        ];
    }
}
