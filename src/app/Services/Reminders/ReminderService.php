<?php

namespace App\Services\Reminders;

use App\Enums\Errors\CommonError;
use App\Mail\Reminders\SendReminder;
use App\Models\Reminder;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * The ReminderService class provides methods for managing reminders.
 *
 * @package App\Services\Reminders
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#list-reminders
 * */
class ReminderService
{
    private array $defaultColumns = [
        'id', 'title', 'description', 'remind_at', 'event_at'
    ];

    /**
     * Retrieves an array of reminders for a given user.
     *
     * @param Model|Authenticatable $user The user for whom to retrieve the reminders.
     * @param int $limit The maximum number of reminders to retrieve. Defaults to 10.
     * @param int $page The page number of the reminders to retrieve. Defaults to 1.
     * @return array The array of reminders and limit information.
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#list-reminders
     */
    public function get(Model|Authenticatable $user, int $limit = 10, int $page = 1): array
    {
        $reminders = Reminder::query()
            ->select(['id', 'title', 'description', 'remind_at', 'event_at'])
            ->where('user_id', $user->getKey())
            ->orderBy('remind_at', 'asc')
            ->simplePaginate(
                perPage: $limit,
                page: $page
            );

        return [
            'reminders' => $reminders->items(),
            'limit' => $limit
        ];
    }

    /**
     * Creates a new reminder for a user.
     *
     * @param Model|Authenticatable $user The user for whom the reminder is being created.
     * @param array $data An array of data containing the details of the reminder.
     * @return array The details of the created reminder.
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#create-reminder
     */
    public function create(Model|Authenticatable $user, array $data): array
    {
        $data['user_id'] = $user->getKey();
        $data['remind_at'] = to_timestamp_second($data['remind_at']);
        $data['event_at'] = to_timestamp_second($data['event_at']);
        $reminder = Reminder::query()->create($data);
        $reminder->refresh();

        return $reminder->only($this->defaultColumns);
    }

    /**
     * Load a model by the given user and model key.
     *
     * @param Model|Authenticatable $user The user instance or authenticatable model.
     * @param int $modelKey The model key.
     * @throws \Exception When the reminder is not found.
     * @return Model The loaded model.
     */
    private function loadModel(Model|Authenticatable $user, int $modelKey): Model
    {
        $reminder = Reminder::query()
            ->select($this->defaultColumns)
            ->where('user_id', $user->getKey())
            ->where('id', $modelKey)
            ->first();

        if (blank($reminder)) {
            throw new \Exception(CommonError::ERR_NOT_FOUND->value, 404);
        }

        return $reminder;
    }

    /**
     * Retrieves an array containing the specified user's model data.
     *
     * @param Model|Authenticatable $user The user model or authenticatable instance.
     * @param int $modelKey The key of the model.
     * @return array An array containing the model data.
     * @throws \Exception
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#view-reminder
     */
    public function view(Model|Authenticatable $user, int $modelKey): array
    {
        return $this->loadModel($user, $modelKey)->only($this->defaultColumns) ?? [];
    }

    /**
     * Update the given user's model with the provided data.
     *
     * @param Model|Authenticatable $user The user model to update.
     * @param int $modelKey The key of the model to update.
     * @param array $data The data to update the model with.
     * @return array The updated model data.
     * @throws \Exception
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#edit-reminder
     */
    public function update(Model|Authenticatable $user, int $modelKey, array $data): array
    {
        $reminder = $this->loadModel($user, $modelKey);
        $data = Arr::whereNotNull($data);

        if (filled($data)) {
            $oldReminder = $reminder->only($this->defaultColumns);

            $data['remind_at'] = isset($data['remind_at']) ? to_timestamp_second($data['remind_at']) : $reminder->remind_at;
            $data['event_at'] = isset($data['remind_at']) ? to_timestamp_second($data['event_at']) : $reminder->event_at;
            $reminder->update($data);
            $reminder->refresh();

            Log::info('update reminder', [
                'old' => $oldReminder,
                'new' => $reminder->only($this->defaultColumns)
            ]);
        }

        return $reminder->only($this->defaultColumns) ?? [];
    }

    /**
     * Deletes a reminder for a user.
     *
     * @param Model|Authenticatable $user The user model.
     * @param int $modelKey The model key.
     * @return bool The result of the deletion.
     * @throws \Exception If the reminder is not found.
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#delete-reminder
     */
    public function delete(Model|Authenticatable $user, int $modelKey): bool
    {
        $reminder = $this->loadModel($user, $modelKey);

        if (blank($reminder)) {
            throw new \Exception(CommonError::ERR_NOT_FOUND->value, 404);
        }

        Log::info('delete reminder', [
            'user' => $user->only(['id', 'name']),
            'reminder' => $reminder->toArray()
        ]);

        // delete reminder
        $reminder->delete();

        return true;
    }


    public function sendReminders(): void
    {
        Log::info('send reminders');

        // in milliseconds
        $unixEpoch = CarbonImmutable::now()->timestamp * 1000;
        Reminder::query()
            ->with(['user:id,name,email'])
            ->whereNull('remind_delivery_at')
            ->where('remind_at', '<=', $unixEpoch)
            ->orderBy('remind_at', 'asc')
            ->chunk(25, function (Collection $reminders) {
                $reminderKeys = [];
                foreach ($reminders as $reminder) {
                    try {
                        Mail::to($reminder->user->email)->send(new SendReminder($reminder));
                        $reminderKeys[] = $reminder->id;
                    } catch (\Exception $exception) {
                        Log::error('Send Reminder: ' . $exception->getMessage());
                    }
                }

                Reminder::query()->whereIn('id', $reminderKeys)->update([
                    'remind_delivery_at' => CarbonImmutable::now()
                ]);
            });
    }
}
