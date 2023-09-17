<?php

namespace Luchavez\AwsSesBounce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Luchavez\AwsSesBounce\Events\BounceNotification\BounceNotificationArchivedEvent;
use Luchavez\AwsSesBounce\Events\BounceNotification\BounceNotificationCollectedEvent;
use Luchavez\AwsSesBounce\Events\BounceNotification\BounceNotificationCreatedEvent;
use Luchavez\AwsSesBounce\Events\BounceNotification\BounceNotificationRestoredEvent;
use Luchavez\AwsSesBounce\Events\BounceNotification\BounceNotificationShownEvent;
use Luchavez\AwsSesBounce\Events\BounceNotification\BounceNotificationUpdatedEvent;
use Luchavez\AwsSesBounce\Http\Requests\BounceNotification\DeleteBounceNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\BounceNotification\IndexBounceNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\BounceNotification\RestoreBounceNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\BounceNotification\ShowBounceNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\BounceNotification\StoreBounceNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\BounceNotification\UpdateBounceNotificationRequest;
use Luchavez\AwsSesBounce\Models\BounceNotification;

/**
 * Class BounceNotificationController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class BounceNotificationController extends Controller
{
    /**
     * BounceNotification List
     *
     * @group BounceNotification Management
     *
     * @param  IndexBounceNotificationRequest  $request
     * @return JsonResponse
     */
    public function index(IndexBounceNotificationRequest $request): JsonResponse
    {
        $data = new BounceNotification();

        if ($request->has('full_data') === true) {
            $data = $data->get();
        } else {
            $data = $data->simplePaginate($request->get('per_page', 15));
        }

        event(new BounceNotificationCollectedEvent($data));

        return simpleResponse()
            ->data($data)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Create BounceNotification
     *
     * @group BounceNotification Management
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->all();

        $model = BounceNotification::create($data)->fresh();

        event(new BounceNotificationCreatedEvent($model));

        return simpleResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Store BounceNotification
     *
     * @group BounceNotification Management
     *
     * @param  StoreBounceNotificationRequest  $request
     * @return JsonResponse
     */
    public function store(StoreBounceNotificationRequest $request): JsonResponse
    {
        $data = $request->all();

        $model = BounceNotification::create($data)->fresh();

        event(new BounceNotificationCreatedEvent($model));

        return simpleResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Show BounceNotification
     *
     * @group BounceNotification Management
     *
     * @param  ShowBounceNotificationRequest  $request
     * @param  BounceNotification  $bounceNotification
     * @return JsonResponse
     */
    public function show(ShowBounceNotificationRequest $request, BounceNotification $bounceNotification): JsonResponse
    {
        event(new BounceNotificationShownEvent($bounceNotification));

        return simpleResponse()
            ->data($bounceNotification)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Edit BounceNotification
     *
     * @group BounceNotification Management
     *
     * @param  Request  $request
     * @param  BounceNotification  $bounceNotification
     * @return JsonResponse
     */
    public function edit(Request $request, BounceNotification $bounceNotification): JsonResponse
    {
        // Logic here...

        event(new BounceNotificationUpdatedEvent($bounceNotification));

        return simpleResponse()
            ->data($bounceNotification)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Update BounceNotification
     *
     * @group BounceNotification Management
     *
     * @param  UpdateBounceNotificationRequest  $request
     * @param  BounceNotification  $bounceNotification
     * @return JsonResponse
     */
    public function update(UpdateBounceNotificationRequest $request, BounceNotification $bounceNotification): JsonResponse
    {
        // Logic here...

        event(new BounceNotificationUpdatedEvent($bounceNotification));

        return simpleResponse()
            ->data($bounceNotification)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Archive BounceNotification
     *
     * @group BounceNotification Management
     *
     * @param  DeleteBounceNotificationRequest  $request
     * @param  BounceNotification  $bounceNotification
     * @return JsonResponse
     */
    public function destroy(DeleteBounceNotificationRequest $request, BounceNotification $bounceNotification): JsonResponse
    {
        $bounceNotification->delete();

        event(new BounceNotificationArchivedEvent($bounceNotification));

        return simpleResponse()
            ->data($bounceNotification)
            ->message('Successfully archived record.')
            ->success()
            ->generate();
    }

    /**
     * Restore BounceNotification
     *
     * @group BounceNotification Management
     *
     * @param  RestoreBounceNotificationRequest  $request
     * @param $bounceNotification
     * @return JsonResponse
     */
    public function restore(RestoreBounceNotificationRequest $request, $bounceNotification): JsonResponse
    {
        $data = BounceNotification::withTrashed()->find($bounceNotification)->restore();

        event(new BounceNotificationRestoredEvent($data));

        return simpleResponse()
            ->data($data)
            ->message('Successfully restored record.')
            ->success()
            ->generate();
    }
}
