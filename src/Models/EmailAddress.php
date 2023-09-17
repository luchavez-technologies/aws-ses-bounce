<?php

namespace Luchavez\AwsSesBounce\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Luchavez\AwsSesBounce\Traits\HasEmailAddressFactoryTrait;
use Luchavez\StarterKit\Traits\UsesUUIDTrait;

/**
 * Class EmailAddress
 *
 * @method static Builder blocked(bool $bool = true) Get blocked email addresses.
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class EmailAddress extends Model
{
    use UsesUUIDTrait;
    use SoftDeletes;
    use HasEmailAddressFactoryTrait;

    /**
     * @var string[]
     */
    protected $appends = [
        'is_blocked',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'email_address';
    }

    /******** RELATIONSHIPS ********/

    /**
     * @return HasMany
     */
    public function bounceNotifications(): HasMany
    {
        return $this->hasMany(BounceNotification::class)->latest('id');
    }

    /**
     * @return HasMany
     */
    public function complaintNotifications(): HasMany
    {
        return $this->hasMany(ComplaintNotification::class)->latest('id');
    }

    /**
     * @return HasMany
     */
    public function deliveryNotifications(): HasMany
    {
        return $this->hasMany(DeliveryNotification::class)->latest('id');
    }

    /***** SCOPES *****/

    /**
     * @param  Builder  $builder
     * @param  bool  $bool
     * @return Builder
     */
    public function scopeBlocked(Builder $builder, bool $bool = true): Builder
    {
        return $bool ? $builder->whereNotNull('blocked_at') : $builder->whereNull('blocked_at');
    }

    /***** ACCESSORS & MUTATORS *****/

    /**
     * @return bool
     */
    public function getIsBlockedAttribute(): bool
    {
        return $this->isBlocked();
    }

    /******** OTHER METHODS ********/

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return boolval($this->blocked_at);
    }

    /**
     * @param  string  $reason
     * @return void
     */
    public function block(string $reason): void
    {
        if (! $this->isBlocked()) {
            // Update block/unblock fields
            $this->block_reason = $reason;
            $this->unblock_reason = null;
            $this->blocked_at = now();
            $this->unblocked_at = null;
            $this->save();
        }
    }

    /**
     * @param  string  $reason
     * @return void
     */
    public function unblock(string $reason): void
    {
        if ($this->isBlocked()) {
            // Clear bounces and complaints
            if (awsSesBounce()->shouldSoftDeleteNotifications()) {
                $this->complaintNotifications()->delete();
                $this->bounceNotifications()->delete();
            } else {
                $this->complaintNotifications()->forceDelete();
                $this->bounceNotifications()->forceDelete();
            }

            // Update block/unblock fields
            $this->block_reason = null;
            $this->unblock_reason = $reason;
            $this->blocked_at = null;
            $this->unblocked_at = now();
            $this->save();
        }
    }
}
