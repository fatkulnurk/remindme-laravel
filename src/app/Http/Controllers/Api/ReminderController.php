<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reminders\GetReminderRequest;
use App\Http\Requests\Reminders\StoreReminderRequest;
use App\Http\Requests\Reminders\UpdateReminderRequest;
use App\Services\Reminders\ReminderService;
use Illuminate\Http\Request;


/**
 * The ReminderController class provides methods for managing reminders.
 *
 * @package App\Http\Controllers\Api
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * */
class ReminderController extends Controller
{
    /**
     * Constructor for the class.
     *
     * @param ReminderService $reminderService
     */
    public function __construct(private readonly ReminderService $reminderService = new ReminderService())
    {
        $this->middleware(['ability:' . \App\Enums\Tokens\TokenAbility::REMINDER_VIEW->value])->only(['index', 'show']);
        $this->middleware(['ability:' . \App\Enums\Tokens\TokenAbility::REMINDER_CREATE->value])->only(['Store']);
        $this->middleware(['ability:' . \App\Enums\Tokens\TokenAbility::REMINDER_UPDATE->value])->only(['update']);
        $this->middleware(['ability:' . \App\Enums\Tokens\TokenAbility::REMINDER_DELETE->value])->only(['destroy']);
    }

    /**
     * Retrieves reminders based on the given request.
     *
     * @param GetReminderRequest $request the request object containing the user and optional limit and page parameters
     * @return \Illuminate\Http\JsonResponse the response containing the retrieved reminders
     * @throws \Exception if an error occurs while retrieving the reminders
     * @link https://github.com/riandyrn/remindme-laravel/blob/main/docs/rest_api.md#list-reminders
     */
    public function index(GetReminderRequest $request)
    {
        try {
            $data = $this->reminderService->get(
                user: $request->user(),
                limit: $request->query('limit', 10),
                page: $request->query('page', 1)
            );
        } catch (\Exception $exception) {
            return response()->jsonFailed($exception->getMessage());
        }

        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }

    /**
     * Store a new reminder.
     *
     * @param StoreReminderRequest $request the store reminder request
     * @throws \Exception if an error occurs during the creation of the reminder
     * @return \Illuminate\Http\JsonResponse the JSON response with the reminder data
     */
    public function store(StoreReminderRequest $request)
    {
        try {
            $data = $this->reminderService->create(
                user: $request->user(),
                data: $request->validated()
            );
        } catch (\Exception $exception) {
            return response()->jsonFailed($exception->getMessage());
        }

        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }

    /**
     * Retrieves the data for a specific resource.
     *
     * @param Request $request The HTTP request object.
     * @param string|int $id The unique identifier for the resource.
     * @throws \Exception If an error occurs while retrieving the data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data.
     */
    public function show(Request $request, string|int $id)
    {
        try {
            $data = $this->reminderService->view(
                user: $request->user(),
                modelKey: $id
            );
        } catch (\Exception $exception) {
            return response()->jsonFailed($exception->getMessage());
        }

        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }

    /**
     * Update a reminder.
     *
     * @param UpdateReminderRequest $request The update reminder request data.
     * @param string|int $id The ID of the reminder to update.
     * @throws \Exception If an exception occurs during the update process.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the result of the update.
     */
    public function update(UpdateReminderRequest $request, string|int $id)
    {
        try {
            $data = $this->reminderService->update(
                user: $request->user(),
                modelKey: $id,
                data: $request->validated()
            );
        } catch (\Exception $exception) {
            return response()->jsonFailed($exception->getMessage());
        }

        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }

    /**
     * Destroys a record by its ID.
     *
     * @param Request $request The HTTP request object.
     * @param string|int $id The ID of the record to be destroyed.
     * @throws \Exception If an error occurs during the deletion process.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the success or failure of the operation.
     */
    public function destroy(Request $request, string|int $id)
    {
        try {
            $data = $this->reminderService->delete(
                user: $request->user(),
                modelKey: $id
            );
        } catch (\Exception $exception) {
            return response()->jsonFailed($exception->getMessage());
        }

        return response()->json([
            'ok' => true
        ]);
    }
}
