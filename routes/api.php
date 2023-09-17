<?php

use Illuminate\Support\Facades\Route;
use Luchavez\AwsSesBounce\Http\Controllers\AwsSesBounceController;
use Luchavez\AwsSesBounce\Http\Controllers\BounceNotificationController;
use Luchavez\AwsSesBounce\Http\Controllers\ComplaintNotificationController;
use Luchavez\AwsSesBounce\Http\Controllers\DeliveryNotificationController;
use Luchavez\AwsSesBounce\Http\Controllers\EmailAddressController;

Route::prefix('aws-ses')->name('aws-ses.')->group(function () {
    Route::prefix('webhook')->name('webhook.')->group(function () {
        Route::post('bounce', [AwsSesBounceController::class, 'bounce'])->name('bounce');
        Route::post('delivery', [AwsSesBounceController::class, 'delivery'])->name('delivery');
        Route::post('complaint', [AwsSesBounceController::class, 'complaint'])->name('complaint');
    });

    if (awsSesBounce()->isEmailTestApiEnabled()) {
        Route::post('test', [AwsSesBounceController::class, 'test'])->name('test')->withoutMiddleware('signed');
    }

    if (awsSesBounce()->isDumpApiEnabled()) {
        Route::post('dump', [AwsSesBounceController::class, 'dump'])->name('dump')->withoutMiddleware('signed');
    }

    Route::prefix('email-addresses')->name('email_addresses.')->middleware(awsSesBounce()->getApiMiddleware())->group(function () {
        Route::post('{email_address}/block', [EmailAddressController::class, 'block'])->name('block');
        Route::post('{email_address}/unblock', [EmailAddressController::class, 'unblock'])->name('unblock');
        Route::get('{email_address}/bounces', [EmailAddressController::class, 'bounces'])->name('bounces');
        Route::get('{email_address}/deliveries', [EmailAddressController::class, 'deliveries'])->name('deliveries');
        Route::get('{email_address}/complaints', [EmailAddressController::class, 'complaints'])->name('complaints');
        Route::get('{email_address}/notifications', [EmailAddressController::class, 'notifications'])->name('notifications');
    });

    Route::apiResources([
        'email-addresses' => EmailAddressController::class,
        'deliveries' => DeliveryNotificationController::class,
        'bounces' => BounceNotificationController::class,
        'complaints' => ComplaintNotificationController::class,
    ], [
        'only' => ['show', 'index'],
        'middleware' => awsSesBounce()->getApiMiddleware(),
    ]);
});
