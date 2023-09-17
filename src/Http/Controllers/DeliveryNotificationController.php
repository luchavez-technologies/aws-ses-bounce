<?php

namespace Luchavez\AwsSesBounce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Luchavez\AwsSesBounce\Events\DeliveryNotification\DeliveryNotificationArchivedEvent;
use Luchavez\AwsSesBounce\Events\DeliveryNotification\DeliveryNotificationCollectedEvent;
use Luchavez\AwsSesBounce\Events\DeliveryNotification\DeliveryNotificationCreatedEvent;
use Luchavez\AwsSesBounce\Events\DeliveryNotification\DeliveryNotificationRestoredEvent;
use Luchavez\AwsSesBounce\Events\DeliveryNotification\DeliveryNotificationShownEvent;
use Luchavez\AwsSesBounce\Events\DeliveryNotification\DeliveryNotificationUpdatedEvent;
use Luchavez\AwsSesBounce\Http\Requests\DeliveryNotification\DeleteDeliveryNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\DeliveryNotification\IndexDeliveryNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\DeliveryNotification\RestoreDeliveryNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\DeliveryNotification\ShowDeliveryNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\DeliveryNotification\StoreDeliveryNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\DeliveryNotification\UpdateDeliveryNotificationRequest;
use Luchavez\AwsSesBounce\Models\DeliveryNotification;

/**
 * Class DeliveryNotificationController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class DeliveryNotificationController extends Controller
{
    /**
     * DeliveryNotification List
     *
     * @group DeliveryNotification Management
     *
     * @param  IndexDeliveryNotificationRequest  $request
     * @return JsonResponse
     */
    public function index(IndexDeliveryNotificationRequest $request): JsonResponse
    {
        $data = new DeliveryNotification();

        if ($request->has('full_data') === true) {
            $data = $data->get();
        } else {
            $data = $data->simplePaginate($request->get('per_page', 15));
        }

        event(new DeliveryNotificationCollectedEvent($data));

        return simpleResponse()
            ->data($data)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Create DeliveryNotification
     *
     * @group DeliveryNotification Management
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->all();

        $model = DeliveryNotification::create($data)->fresh();

        event(new DeliveryNotificationCreatedEvent($model));

        return simpleResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Store DeliveryNotification
     *
     * @group DeliveryNotification Management
     *
     * @param  StoreDeliveryNotificationRequest  $request
     * @return JsonResponse
     */
    public function store(StoreDeliveryNotificationRequest $request): JsonResponse
    {
        $data = $request->all();

        $model = DeliveryNotification::create($data)->fresh();

        event(new DeliveryNotificationCreatedEvent($model));

        return simpleResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Show DeliveryNotification
     *
     * @group DeliveryNotification Management
     *
     * @param  ShowDeliveryNotificationRequest  $request
     * @param  DeliveryNotification  $deliveryNotification
     * @return JsonResponse
     */
    public function show(ShowDeliveryNotificationRequest $request, DeliveryNotification $deliveryNotification): JsonResponse
    {
        event(new DeliveryNotificationShownEvent($deliveryNotification));

        return simpleResponse()
            ->data($deliveryNotification)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Edit DeliveryNotification
     *
     * @group DeliveryNotification Management
     *
     * @param  Request  $request
     * @param  DeliveryNotification  $deliveryNotification
     * @return JsonResponse
     */
    public function edit(Request $request, DeliveryNotification $deliveryNotification): JsonResponse
    {
        // Logic here...

        event(new DeliveryNotificationUpdatedEvent($deliveryNotification));

        return simpleResponse()
            ->data($deliveryNotification)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Update DeliveryNotification
     *
     * @group DeliveryNotification Management
     *
     * @param  UpdateDeliveryNotificationRequest  $request
     * @param  DeliveryNotification  $deliveryNotification
     * @return JsonResponse
     */
    public function update(UpdateDeliveryNotificationRequest $request, DeliveryNotification $deliveryNotification): JsonResponse
    {
        // Logic here...

        event(new DeliveryNotificationUpdatedEvent($deliveryNotification));

        return simpleResponse()
            ->data($deliveryNotification)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Archive DeliveryNotification
     *
     * @group DeliveryNotification Management
     *
     * @param  DeleteDeliveryNotificationRequest  $request
     * @param  DeliveryNotification  $deliveryNotification
     * @return JsonResponse
     */
    public function destroy(DeleteDeliveryNotificationRequest $request, DeliveryNotification $deliveryNotification): JsonResponse
    {
        $deliveryNotification->delete();

        event(new DeliveryNotificationArchivedEvent($deliveryNotification));

        return simpleResponse()
            ->data($deliveryNotification)
            ->message('Successfully archived record.')
            ->success()
            ->generate();
    }

    /**
     * Restore DeliveryNotification
     *
     * @group DeliveryNotification Management
     *
     * @param  RestoreDeliveryNotificationRequest  $request
     * @param $deliveryNotification
     * @return JsonResponse
     */
    public function restore(RestoreDeliveryNotificationRequest $request, $deliveryNotification): JsonResponse
    {
        $data = DeliveryNotification::withTrashed()->find($deliveryNotification)->restore();

        event(new DeliveryNotificationRestoredEvent($data));

        return simpleResponse()
            ->data($data)
            ->message('Successfully restored record.')
            ->success()
            ->generate();
    }
}
