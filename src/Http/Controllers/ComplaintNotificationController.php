<?php

namespace Luchavez\AwsSesBounce\Http\Controllers;

use App\Http\Controllers\Controller;
use Luchavez\AwsSesBounce\Events\ComplaintNotification\ComplaintNotificationArchivedEvent;
use Luchavez\AwsSesBounce\Events\ComplaintNotification\ComplaintNotificationCollectedEvent;
// Model
use Luchavez\AwsSesBounce\Events\ComplaintNotification\ComplaintNotificationCreatedEvent;
// Requests
use Luchavez\AwsSesBounce\Events\ComplaintNotification\ComplaintNotificationRestoredEvent;
use Luchavez\AwsSesBounce\Events\ComplaintNotification\ComplaintNotificationShownEvent;
use Luchavez\AwsSesBounce\Events\ComplaintNotification\ComplaintNotificationUpdatedEvent;
use Luchavez\AwsSesBounce\Http\Requests\ComplaintNotification\DeleteComplaintNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\ComplaintNotification\IndexComplaintNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\ComplaintNotification\RestoreComplaintNotificationRequest;
// Events
use Luchavez\AwsSesBounce\Http\Requests\ComplaintNotification\ShowComplaintNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\ComplaintNotification\StoreComplaintNotificationRequest;
use Luchavez\AwsSesBounce\Http\Requests\ComplaintNotification\UpdateComplaintNotificationRequest;
use Luchavez\AwsSesBounce\Models\ComplaintNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ComplaintNotificationController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ComplaintNotificationController extends Controller
{
    /**
     * ComplaintNotification List
     *
     * @group ComplaintNotification Management
     *
     * @param  IndexComplaintNotificationRequest  $request
     * @return JsonResponse
     */
    public function index(IndexComplaintNotificationRequest $request): JsonResponse
    {
        $data = new ComplaintNotification();

        if ($request->has('full_data') === true) {
            $data = $data->get();
        } else {
            $data = $data->simplePaginate($request->get('per_page', 15));
        }

        event(new ComplaintNotificationCollectedEvent($data));

        return customResponse()
            ->data($data)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Create ComplaintNotification
     *
     * @group ComplaintNotification Management
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->all();

        $model = ComplaintNotification::create($data)->fresh();

        event(new ComplaintNotificationCreatedEvent($model));

        return customResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Store ComplaintNotification
     *
     * @group ComplaintNotification Management
     *
     * @param  StoreComplaintNotificationRequest  $request
     * @return JsonResponse
     */
    public function store(StoreComplaintNotificationRequest $request): JsonResponse
    {
        $data = $request->all();

        $model = ComplaintNotification::create($data)->fresh();

        event(new ComplaintNotificationCreatedEvent($model));

        return customResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Show ComplaintNotification
     *
     * @group ComplaintNotification Management
     *
     * @param  ShowComplaintNotificationRequest  $request
     * @param  ComplaintNotification  $complaintNotification
     * @return JsonResponse
     */
    public function show(ShowComplaintNotificationRequest $request, ComplaintNotification $complaintNotification): JsonResponse
    {
        event(new ComplaintNotificationShownEvent($complaintNotification));

        return customResponse()
            ->data($complaintNotification)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Edit ComplaintNotification
     *
     * @group ComplaintNotification Management
     *
     * @param  Request  $request
     * @param  ComplaintNotification  $complaintNotification
     * @return JsonResponse
     */
    public function edit(Request $request, ComplaintNotification $complaintNotification): JsonResponse
    {
        // Logic here...

        event(new ComplaintNotificationUpdatedEvent($complaintNotification));

        return customResponse()
            ->data($complaintNotification)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Update ComplaintNotification
     *
     * @group ComplaintNotification Management
     *
     * @param  UpdateComplaintNotificationRequest  $request
     * @param  ComplaintNotification  $complaintNotification
     * @return JsonResponse
     */
    public function update(UpdateComplaintNotificationRequest $request, ComplaintNotification $complaintNotification): JsonResponse
    {
        // Logic here...

        event(new ComplaintNotificationUpdatedEvent($complaintNotification));

        return customResponse()
            ->data($complaintNotification)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Archive ComplaintNotification
     *
     * @group ComplaintNotification Management
     *
     * @param  DeleteComplaintNotificationRequest  $request
     * @param  ComplaintNotification  $complaintNotification
     * @return JsonResponse
     */
    public function destroy(DeleteComplaintNotificationRequest $request, ComplaintNotification $complaintNotification): JsonResponse
    {
        $complaintNotification->delete();

        event(new ComplaintNotificationArchivedEvent($complaintNotification));

        return customResponse()
            ->data($complaintNotification)
            ->message('Successfully archived record.')
            ->success()
            ->generate();
    }

    /**
     * Restore ComplaintNotification
     *
     * @group ComplaintNotification Management
     *
     * @param  RestoreComplaintNotificationRequest  $request
     * @param $complaintNotification
     * @return JsonResponse
     */
    public function restore(RestoreComplaintNotificationRequest $request, $complaintNotification): JsonResponse
    {
        $data = ComplaintNotification::withTrashed()->find($complaintNotification)->restore();

        event(new ComplaintNotificationRestoredEvent($data));

        return customResponse()
            ->data($data)
            ->message('Successfully restored record.')
            ->success()
            ->generate();
    }
}
