<?php

namespace Luchavez\AwsSesBounce\Http\Controllers;

use App\Http\Controllers\Controller;
use Luchavez\AwsSesBounce\Events\EmailAddress\EmailAddressArchivedEvent;
use Luchavez\AwsSesBounce\Events\EmailAddress\EmailAddressCollectedEvent;
use Luchavez\AwsSesBounce\Events\EmailAddress\EmailAddressCreatedEvent;
use Luchavez\AwsSesBounce\Events\EmailAddress\EmailAddressRestoredEvent;
// Model
use Luchavez\AwsSesBounce\Events\EmailAddress\EmailAddressShownEvent;
// Requests
use Luchavez\AwsSesBounce\Events\EmailAddress\EmailAddressUpdatedEvent;
use Luchavez\AwsSesBounce\Http\Requests\EmailAddress\BlockEmailAddressRequest;
use Luchavez\AwsSesBounce\Http\Requests\EmailAddress\DeleteEmailAddressRequest;
use Luchavez\AwsSesBounce\Http\Requests\EmailAddress\IndexEmailAddressRequest;
use Luchavez\AwsSesBounce\Http\Requests\EmailAddress\RestoreEmailAddressRequest;
use Luchavez\AwsSesBounce\Http\Requests\EmailAddress\ShowEmailAddressRequest;
// Events
use Luchavez\AwsSesBounce\Http\Requests\EmailAddress\StoreEmailAddressRequest;
use Luchavez\AwsSesBounce\Http\Requests\EmailAddress\UnblockEmailAddressRequest;
use Luchavez\AwsSesBounce\Http\Requests\EmailAddress\UpdateEmailAddressRequest;
use Luchavez\AwsSesBounce\Models\EmailAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class EmailAddressController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class EmailAddressController extends Controller
{
    /**
     * EmailAddress List
     *
     * @group EmailAddress Management
     *
     * @param  IndexEmailAddressRequest  $request
     * @return JsonResponse
     */
    public function index(IndexEmailAddressRequest $request): JsonResponse
    {
        $data = new EmailAddress();

        if ($request->has('full_data') === true) {
            $data = $data->get();
        } else {
            $data = $data->simplePaginate($request->get('per_page', 15));
        }

        event(new EmailAddressCollectedEvent($data));

        return customResponse()
            ->data($data)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Create EmailAddress
     *
     * @group EmailAddress Management
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->all();

        $model = EmailAddress::create($data)->fresh();

        event(new EmailAddressCreatedEvent($model));

        return customResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Store EmailAddress
     *
     * @group EmailAddress Management
     *
     * @param  StoreEmailAddressRequest  $request
     * @return JsonResponse
     */
    public function store(StoreEmailAddressRequest $request): JsonResponse
    {
        $data = $request->all();

        $model = EmailAddress::create($data)->fresh();

        event(new EmailAddressCreatedEvent($model));

        return customResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Show EmailAddress
     *
     * @group EmailAddress Management
     *
     * @param  ShowEmailAddressRequest  $request
     * @param  EmailAddress  $emailAddress
     * @return JsonResponse
     */
    public function show(ShowEmailAddressRequest $request, EmailAddress $emailAddress): JsonResponse
    {
        event(new EmailAddressShownEvent($emailAddress));

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Edit EmailAddress
     *
     * @group EmailAddress Management
     *
     * @param  Request  $request
     * @param  EmailAddress  $emailAddress
     * @return JsonResponse
     */
    public function edit(Request $request, EmailAddress $emailAddress): JsonResponse
    {
        // Logic here...

        event(new EmailAddressUpdatedEvent($emailAddress));

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Update EmailAddress
     *
     * @group EmailAddress Management
     *
     * @param  UpdateEmailAddressRequest  $request
     * @param  EmailAddress  $emailAddress
     * @return JsonResponse
     */
    public function update(UpdateEmailAddressRequest $request, EmailAddress $emailAddress): JsonResponse
    {
        // Logic here...

        event(new EmailAddressUpdatedEvent($emailAddress));

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Archive EmailAddress
     *
     * @group EmailAddress Management
     *
     * @param  DeleteEmailAddressRequest  $request
     * @param  EmailAddress  $emailAddress
     * @return JsonResponse
     */
    public function destroy(DeleteEmailAddressRequest $request, EmailAddress $emailAddress): JsonResponse
    {
        $emailAddress->delete();

        event(new EmailAddressArchivedEvent($emailAddress));

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully archived record.')
            ->success()
            ->generate();
    }

    /**
     * Restore EmailAddress
     *
     * @group EmailAddress Management
     *
     * @param  RestoreEmailAddressRequest  $request
     * @param $emailAddress
     * @return JsonResponse
     */
    public function restore(RestoreEmailAddressRequest $request, $emailAddress): JsonResponse
    {
        $data = EmailAddress::withTrashed()->find($emailAddress)->restore();

        event(new EmailAddressRestoredEvent($data));

        return customResponse()
            ->data($data)
            ->message('Successfully restored record.')
            ->success()
            ->generate();
    }

    /***** BLOCK / UNBLOCK *****/

    /**
     * Block EmailAddress
     *
     * @group EmailAddress Management
     *
     * @param  BlockEmailAddressRequest  $request
     * @param $emailAddress
     * @return JsonResponse
     */
    public function block(BlockEmailAddressRequest $request, $emailAddress): JsonResponse
    {
        if ($user = $request->user()) {
            $reason = 'Manually blocked by a user: '.$user->id;
        } else {
            $reason = 'Manually blocked.';
        }

        $emailAddress = awsSesBounce()->block($emailAddress, $reason);

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully blocked an email address.')
            ->success()
            ->generate();
    }

    /**
     * Unblock EmailAddress
     *
     * @group EmailAddress Management
     *
     * @param  UnblockEmailAddressRequest  $request
     * @param  EmailAddress  $emailAddress
     * @return JsonResponse
     */
    public function unblock(UnblockEmailAddressRequest $request, EmailAddress $emailAddress): JsonResponse
    {
        if ($user = $request->user()) {
            $reason = 'Manually unblocked by a user: '.$user->id;
        } else {
            $reason = 'Manually unblocked.';
        }

        $emailAddress = awsSesBounce()->unblock($emailAddress, $reason);

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully unblocked an email address.')
            ->success()
            ->generate();
    }

    /***** SES FEEDBACKS *****/

    /**
     * Bounce Notifications by Email Address
     *
     * @group EmailAddress Management
     *
     * @param  Request  $request
     * @param  EmailAddress  $emailAddress
     * @return JsonResponse
     */
    public function bounces(Request $request, EmailAddress $emailAddress): JsonResponse
    {
        $relationships = ['bounceNotifications'];

        $emailAddress->loadCount($relationships)->load($relationships);

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Complaint Notifications by Email Address
     *
     * @group EmailAddress Management
     *
     * @param  Request  $request
     * @param  EmailAddress  $emailAddress
     * @return JsonResponse
     */
    public function complaints(Request $request, EmailAddress $emailAddress): JsonResponse
    {
        $relationships = ['complaintNotifications'];

        $emailAddress->loadCount($relationships)->load($relationships);

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Delivery Notifications by Email Address
     *
     * @group EmailAddress Management
     *
     * @param  Request  $request
     * @param  EmailAddress  $emailAddress
     * @return JsonResponse
     */
    public function deliveries(Request $request, EmailAddress $emailAddress): JsonResponse
    {
        $relationships = ['deliveryNotifications'];

        $emailAddress->loadCount($relationships)->load($relationships);

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * All Notifications by Email Address
     *
     * @group EmailAddress Management
     *
     * @param  Request  $request
     * @param  EmailAddress  $emailAddress
     * @return JsonResponse
     */
    public function notifications(Request $request, EmailAddress $emailAddress): JsonResponse
    {
        $relationships = ['bounceNotifications', 'complaintNotifications', 'deliveryNotifications'];

        $emailAddress->loadCount($relationships)->load($relationships);

        return customResponse()
            ->data($emailAddress)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }
}
