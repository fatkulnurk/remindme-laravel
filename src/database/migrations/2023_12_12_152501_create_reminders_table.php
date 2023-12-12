<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @link https://github.com/riandyrn/remindme-laravel/blob/3f565565ca94205cffe01d3827c0478a1ae9db4c/docs/rest_api.md?plain=1#L174
     */
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('title')
                ->comment('title the reminder');
            $table->text('description')->nullable()
                ->comment('Description of the reminder.');
            $table->unsignedBigInteger('remind_at')->nullable()
                ->comment('Unix timestamp in seconds when the reminder should be reminded to the user.');
            $table->timestamp('remind_delivery_at')->nullable()
                ->comment('time to send the reminder to user');
            $table->unsignedBigInteger('event_at')
                ->comment('Unix timestamp in seconds when the event will occurs');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
