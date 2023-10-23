<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Luchavez\AwsSesBounce\Models\EmailAddress;

/**
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * Using UUID is highly recommended as identifier for a model.
     * By default, the uuid column is the generated model's default route key.
     * With this, the routes are protected from manually inputted incrementing id's.
     *
     * Adding `softDeletes()` is also another recommended feature.
     * With this, deleted models are getting archived instead of being deleted from database.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('complaint_notifications', static function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignIdFor(EmailAddress::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('user_agent');
            $table->string('feedback_type');
            $table->timestamp('arrival_date');
            $table->timestamp('complained_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_notifications');
    }
};
