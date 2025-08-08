<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\TimelineClip
 *
 * @property int $id
 * @property int $project_id
 * @property int $media_file_id
 * @property int $track
 * @property float $start_time
 * @property float $duration
 * @property float $media_start
 * @property float|null $media_end
 * @property array|null $properties
 * @property int $z_index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\MediaFile $mediaFile
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereMediaEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereMediaFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereMediaStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereTrack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip whereZIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimelineClip onTrack($track)
 * @method static \Database\Factories\TimelineClipFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class TimelineClip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'media_file_id',
        'track',
        'start_time',
        'duration',
        'media_start',
        'media_end',
        'properties',
        'z_index',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'track' => 'integer',
        'start_time' => 'float',
        'duration' => 'float',
        'media_start' => 'float',
        'media_end' => 'float',
        'properties' => 'array',
        'z_index' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the project that owns the timeline clip.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the media file for the timeline clip.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mediaFile(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class);
    }

    /**
     * Scope a query to only include clips on a specific track.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $track
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnTrack($query, $track)
    {
        return $query->where('track', $track);
    }
}