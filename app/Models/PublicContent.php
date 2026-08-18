<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicContent extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Content Types
    |--------------------------------------------------------------------------
    */

    public const TYPE_NEWS = 'news';
    public const TYPE_NOTICE = 'notice';
    public const TYPE_ANNOUNCEMENT = 'announcement';
    public const TYPE_PRESS_RELEASE = 'press_release';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
    'title',
    'slug',
    'type',
    'summary',
    'content',
    'image_path',
    'attachment_path',
    'published_at',
    'display_until',
    'is_published',
    'is_featured',
    'is_ticker',
    'is_home_service',
    'sort_order',
    'created_by',
    'updated_by',
];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
    'published_at' => 'datetime',
    'display_until' => 'datetime',
    'is_published' => 'boolean',
    'is_featured' => 'boolean',
    'is_ticker' => 'boolean',
    'is_home_service' => 'boolean',
];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function (Builder $query) {
                $query
                    ->whereNull('display_until')
                    ->orWhere('display_until', '>=', now());
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeTicker(Builder $query): Builder
    {
        return $query->where('is_ticker', true);
    }

    public function scopeOfType(
        Builder $query,
        string $type
    ): Builder {
        return $query->where('type', $type);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isNews(): bool
    {
        return $this->type === self::TYPE_NEWS;
    }

    public function isNotice(): bool
    {
        return $this->type === self::TYPE_NOTICE;
    }

    public function isAnnouncement(): bool
    {
        return $this->type === self::TYPE_ANNOUNCEMENT;
    }

    public function isPressRelease(): bool
    {
        return $this->type === self::TYPE_PRESS_RELEASE;
    }

    public function isCurrentlyPublished(): bool
    {
        if (! $this->is_published) {
            return false;
        }

        if (
            $this->published_at !== null &&
            $this->published_at->isFuture()
        ) {
            return false;
        }

        if (
            $this->display_until !== null &&
            $this->display_until->isPast()
        ) {
            return false;
        }

        return true;
    }
}
