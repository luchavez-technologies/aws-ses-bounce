<?php

namespace Luchavez\AwsSesBounce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Luchavez\AwsSesBounce\Traits\HasComplaintNotificationFactoryTrait;
use Luchavez\StarterKit\Traits\UsesUUIDTrait;

/**
 * Class ComplaintNotification
 *
 * Note:
 * By default, models and factories inside a package need to explicitly connect with each other.
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ComplaintNotification extends Model
{
    use UsesUUIDTrait;
    use SoftDeletes;
    use HasComplaintNotificationFactoryTrait;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /******** RELATIONSHIPS ********/

    /**
     * @return BelongsTo
     */
    public function emailAddress(): BelongsTo
    {
        return $this->belongsTo(EmailAddress::class);
    }

    /***** ACCESSORS & MUTATORS *****/

    //

    /******** OTHER METHODS ********/

    //
}
