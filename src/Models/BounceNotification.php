<?php

namespace Luchavez\AwsSesBounce\Models;

use Luchavez\AwsSesBounce\Traits\HasBounceNotificationFactoryTrait;
use Luchavez\StarterKit\Traits\UsesUUIDTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BounceNotification
 *
 * Note:
 * By default, models and factories inside a package need to explicitly connect with each other.
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class BounceNotification extends Model
{
    use UsesUUIDTrait;
    use SoftDeletes;
    use HasBounceNotificationFactoryTrait;

    /**
     * @var string[]
     */
    protected $appends = [
        'reason',
    ];

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

    /**
     * @return array
     */
    public function getReasonAttribute(): array
    {
        return awsSesBounce()->getBounceReasons()->get($this->status_code, []);
    }

    /******** OTHER METHODS ********/

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isTypeUndetermined(): bool
    {
        return $this->type === 'Undetermined';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isTypePermanent(): bool
    {
        return $this->type === 'Permanent';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isTypeTransient(): bool
    {
        return $this->type === 'Transient';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isSubTypeUndetermined(): bool
    {
        return $this->sub_type === 'Undetermined';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isSubTypeGeneral(): bool
    {
        return $this->sub_type === 'General';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isSubTypeNoEmail(): bool
    {
        return $this->sub_type === 'NoEmail';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isSubTypeSuppressed(): bool
    {
        return $this->sub_type === 'Suppressed';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isSubTypeOnAccountSuppressionList(): bool
    {
        return $this->sub_type === 'OnAccountSuppressionList';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isSubTypeMailboxFull(): bool
    {
        return $this->sub_type === 'MailboxFull';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isSubTypeMessageTooLarge(): bool
    {
        return $this->sub_type === 'MessageTooLarge';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isSubTypeContentRejected(): bool
    {
        return $this->sub_type === 'ContentRejected';
    }

    /**
     * @return bool
     *
     * @link https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
     */
    public function isSubTypeAttachmentRejected(): bool
    {
        return $this->sub_type === 'AttachmentRejected';
    }
}
