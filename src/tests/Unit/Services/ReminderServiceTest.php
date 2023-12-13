<?php

namespace Tests\Unit\Services;

use App\Models\Reminder;
use App\Models\User;
use App\Services\Reminders\ReminderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\CreatesApplication;

/**
 * @package Tests\Unit\Services
 * @author  Fatkul Nur Koirudin <https://github.com/fatkulnurk>
 * */
class ReminderServiceTest extends TestCase
{
    use CreatesApplication;

    private Model $user;


    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::query()->first();
    }

    public static function additionalProvider(): array
    {
        return [
            [
                [
                    'title' => 'Memancing Ikan - fatkul nur koirudin',
                    'description' => 'memancing ikan di sungai belakang rumah',
                    'remind_at' => '1702311229',
                    'event_at' => '1822310229'
                ]
            ],
            [
                [
                    'title' => 'Ternak Ikan - fatkul nur koirudin',
                    'description' => 'ternak ikan di sungai belakang rumah',
                    'remind_at' => '1702319229',
                    'event_at' => '1822310229'
                ]
            ],
            [
                [
                    'title' => 'Makan Ikan - fatkul nur koirudin',
                    'description' => 'makan ikan di sungai belakang rumah',
                    'remind_at' => '1701311229',
                    'event_at' => '1722310229'
                ]
            ]
        ];
    }

    public function test_instance_type_correct()
    {
        $reminderService = (new ReminderService());

        $this->assertEquals($reminderService::class, get_class($reminderService));
        $this->assertInstanceOf(ReminderService::class, $reminderService);
    }

    #[DataProvider('additionalProvider')]
    public function test_validate_provider_dataset($data)
    {
        $this->assertIsArray($data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('remind_at', $data);
        $this->assertArrayHasKey('event_at', $data);
    }

    public function test_get_reminder_passed(): void
    {
        $limit = 10;
        $reminders = (new ReminderService())->get(
            user: $this->user,
            limit: $limit,
        );

        // check if data is returned
        $this->assertIsArray($reminders);
        $this->assertArrayHasKey('reminders', $reminders);
        $this->assertArrayHasKey('limit', $reminders);
        $this->assertEquals($limit, $reminders['limit']);
    }

    public function test_get_reminder_failed(): void
    {
        $this->expectException(QueryException::class);
        $limit = -10;
        (new ReminderService())->get(
            user: $this->user,
            limit: $limit,
        );
    }

    #[DataProvider('additionalProvider')]
    public function test_create_reminder_passed($data)
    {
        $reminder = (new ReminderService())->create(
            user: $this->user,
            data: $data
        );

        // check if data is returned
        $this->assertIsArray($reminder);
        $this->assertArrayHasKey('id', $reminder);
        $this->assertArrayHasKey('title', $reminder);
        $this->assertArrayHasKey('description', $reminder);
        $this->assertArrayHasKey('remind_at', $reminder);
        $this->assertArrayHasKey('event_at', $reminder);
        $this->assertArrayNotHasKey('remind_delivery_at', $reminder);
        $this->assertArrayNotHasKey('created_at', $reminder);
        $this->assertArrayNotHasKey('updated_at', $reminder);
        $this->assertArrayNotHasKey('deleted_at', $reminder);

        // check if data is correct
        $this->assertEquals($data['title'], $reminder['title']);
        $this->assertEquals($data['description'], $reminder['description']);
        $this->assertEquals($data['remind_at'], $reminder['remind_at']);
        $this->assertEquals($data['event_at'], $reminder['event_at']);
    }

    #[DataProvider('additionalProvider')]
    public function test_create_reminder_failed($data)
    {
        unset($data['title']);

        $this->assertArrayNotHasKey('title', $data);
        $this->expectException(QueryException::class);

        (new ReminderService())->create(
            user: $this->user,
            data: $data
        );
    }

    public function test_view_reminder_by_id_passed()
    {
        $reminderKey = Reminder::query()->inRandomOrder()->first()->getKey();
        $reminder = (new ReminderService())->view(
            user: $this->user,
            modelKey: $reminderKey
        );

        // check if data is returned
        $this->assertIsArray($reminder);
        $this->assertArrayHasKey('id', $reminder);
        $this->assertArrayHasKey('title', $reminder);
        $this->assertArrayHasKey('description', $reminder);
        $this->assertArrayHasKey('remind_at', $reminder);
        $this->assertArrayHasKey('event_at', $reminder);
        $this->assertArrayNotHasKey('remind_delivery_at', $reminder);
        $this->assertArrayNotHasKey('created_at', $reminder);
        $this->assertArrayNotHasKey('updated_at', $reminder);
        $this->assertArrayNotHasKey('deleted_at', $reminder);

        $this->assertEquals($reminderKey, $reminder['id']);
    }

    public function test_view_reminder_by_id_failed()
    {
        $this->expectException(\Exception::class);

        $reminderKey = PHP_INT_MAX;
        (new ReminderService())->view(
            user: $this->user,
            modelKey: $reminderKey
        );
    }

    #[DataProvider('additionalProvider')]
    public function test_update_reminder_by_id_passed($data)
    {
        $reminderKey = Reminder::query()->inRandomOrder()->first()->getKey();
        $reminder = (new ReminderService())->update(
            user: $this->user,
            modelKey: $reminderKey,
            data: $data
        );

        // check if data is returned
        $this->assertIsArray($reminder);
        $this->assertArrayHasKey('id', $reminder);
        $this->assertArrayHasKey('title', $reminder);
        $this->assertArrayHasKey('description', $reminder);
        $this->assertArrayHasKey('remind_at', $reminder);
        $this->assertArrayHasKey('event_at', $reminder);
        $this->assertArrayNotHasKey('remind_delivery_at', $reminder);
        $this->assertArrayNotHasKey('created_at', $reminder);
        $this->assertArrayNotHasKey('updated_at', $reminder);
        $this->assertArrayNotHasKey('deleted_at', $reminder);

        // check if data is updated
        $this->assertEquals($data['title'], $reminder['title']);
        $this->assertEquals($data['description'], $reminder['description']);
        $this->assertEquals($data['remind_at'], $reminder['remind_at']);
        $this->assertEquals($data['event_at'], $reminder['event_at']);
    }

    #[DataProvider('additionalProvider')]
    public function test_update_reminder_by_id_failed($data)
    {
        $this->expectException(\Exception::class);

        $reminderKey = PHP_INT_MAX;
        $reminder = (new ReminderService())->update(
            user: $this->user,
            modelKey: $reminderKey,
            data: $data
        );
    }

    #[DataProvider('additionalProvider')]
    public function test_update_reminder_by_id_in_remind_not_same_with_old_value_passed($data)
    {
        $reminderModel = Reminder::query()->inRandomOrder()->first();
        $reminderKey = $reminderModel->getKey();
        $data['remind_at'] = $data['remind_at'] + 1;
        $reminder = (new ReminderService())->update(
            user: $this->user,
            modelKey: $reminderKey,
            data: $data
        );

        // check if data is returned
        $this->assertIsArray($reminder);
        $this->assertArrayHasKey('id', $reminder);
        $this->assertArrayHasKey('title', $reminder);
        $this->assertArrayHasKey('description', $reminder);
        $this->assertArrayHasKey('remind_at', $reminder);
        $this->assertArrayHasKey('event_at', $reminder);
        $this->assertArrayNotHasKey('remind_delivery_at', $reminder);
        $this->assertArrayNotHasKey('created_at', $reminder);
        $this->assertArrayNotHasKey('updated_at', $reminder);
        $this->assertArrayNotHasKey('deleted_at', $reminder);

        // check if data is updated
        $this->assertEquals($data['title'], $reminder['title']);
        $this->assertEquals($data['description'], $reminder['description']);
        $this->assertEquals($data['remind_at'], $reminder['remind_at']);
        $this->assertEquals($data['event_at'], $reminder['event_at']);

        $reminderModel->refresh();
        $this->assertNull($reminderModel->remind_delivery_at);
    }


    #[DataProvider('additionalProvider')]
    public function test_update_reminder_by_id_with_empty_value_passed($data)
    {
        $reminderModel = Reminder::query()->inRandomOrder()->first();
        $reminderKey = $reminderModel->getKey();
        $data['remind_at'] = null;

        $this->assertNull($data['remind_at']);

        $reminder = (new ReminderService())->update(
            user: $this->user,
            modelKey: $reminderKey,
            data: $data
        );

        // check if data is returned
        $this->assertIsArray($reminder);
        $this->assertArrayHasKey('id', $reminder);
        $this->assertArrayHasKey('title', $reminder);
        $this->assertArrayHasKey('description', $reminder);
        $this->assertArrayHasKey('remind_at', $reminder);
        $this->assertArrayHasKey('event_at', $reminder);
        $this->assertArrayNotHasKey('remind_delivery_at', $reminder);
        $this->assertArrayNotHasKey('created_at', $reminder);
        $this->assertArrayNotHasKey('updated_at', $reminder);
        $this->assertArrayNotHasKey('deleted_at', $reminder);

        // check if data is updated
        $this->assertEquals($data['title'], $reminder['title']);
        $this->assertEquals($data['description'], $reminder['description']);
        $this->assertNotEquals($data['remind_at'], $reminder['remind_at']);
        $this->assertEquals($data['event_at'], $reminder['event_at']);
    }

    public function test_delete_reminder_by_id_passed()
    {
        $reminder = Reminder::query()->inRandomOrder()->first();

        if (filled($reminder)) {
            $result = (new ReminderService())->delete(
                user: User::query()->first(),
                modelKey: $reminder->getKey()
            );

            // check if data is returned
            $this->assertIsBool($result);

            // check if data is soft deleted
            $this->assertSoftDeleted($reminder);
        }

        $this->assertTrue(true);
    }

    public function test_delete_reminder_by_id_failed()
    {
        $this->expectException(\Exception::class);
        $reminderKey = PHP_INT_MAX;
        $result = (new ReminderService())->delete(
            user: User::query()->first(),
            modelKey: $reminderKey
        );
    }

    public function test_delete_all_reminders_passed()
    {
        Reminder::query()->where('id', '>', 0)->delete();

        $this->assertEquals(0, Reminder::query()->count());
    }


    public function test_send_reminders_for_schedule()
    {
        $this->assertNull((new ReminderService())->sendReminders());
    }
}
