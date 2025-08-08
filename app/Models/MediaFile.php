<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MediaFile
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $filename
 * @property string $path
 * @property string $type
 * @property string $mime_type
 * @property int $size
 * @property float|null $duration
 * @property int|null $width
 * @property int|null $height
 * @property string|null $thumbnail_path
 * @property array|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimelineClip> $timelineClips
 * @property-read int|null $timeline_clips_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereThumbnailPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MediaFile ofType($type)
 * @method static \Database\Factories\MediaFileFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class MediaFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'filename',
        'path',
        'type',
        'mime_type',
        'size',
        'duration',
        'width',
        'height',
        'thumbnail_path',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
        'duration' => 'float',
        'width' => 'integer',
        'height' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the media file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the timeline clips for the media file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timelineClips(): HasMany
    {
        return $this->hasMany(TimelineClip::class);
    }

    /**
     * Scope a query to only include files of a specific type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}