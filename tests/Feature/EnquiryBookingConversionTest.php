<?php

namespace Tests\Feature;

use App\Models\CateringEnquiry;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * AQL-35 — Enquiry-to-booking conversion.
 * A confirmed enquiry is a booked event: confirming records confirmed_at,
 * and a second event cannot be confirmed for an already-booked date.
 */
class EnquiryBookingConversionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->assignRole('Owner');

        return $user;
    }

    private function enquiry(array $overrides = []): CateringEnquiry
    {
        return CateringEnquiry::create(array_merge([
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'phone' => '0400000000',
            'event_date' => '2026-12-01',
            'guest_count' => 30,
            'event_type' => 'corporate',
            'status' => 'new',
        ], $overrides));
    }

    private function setStatus(User $admin, CateringEnquiry $enquiry, string $status)
    {
        return $this->actingAs($admin)
            ->from(route('admin.catering-enquiries.show', $enquiry))
            ->patch(route('admin.catering-enquiries.status', $enquiry), ['status' => $status]);
    }

    public function test_confirming_an_enquiry_turns_it_into_a_booking(): void
    {
        $enquiry = $this->enquiry();

        $this->setStatus($this->admin(), $enquiry, 'confirmed')->assertSessionHasNoErrors();

        $enquiry->refresh();
        $this->assertSame('confirmed', $enquiry->status);
        $this->assertNotNull($enquiry->confirmed_at);
        $this->assertTrue($enquiry->isBooked());
    }

    public function test_second_event_on_a_booked_date_cannot_be_confirmed(): void
    {
        $admin = $this->admin();
        $this->setStatus($admin, $this->enquiry(['name' => 'First']), 'confirmed');
        $second = $this->enquiry(['name' => 'Second']);

        $this->setStatus($admin, $second, 'confirmed')->assertSessionHasErrors('status');

        $second->refresh();
        $this->assertSame('new', $second->status);
        $this->assertNull($second->confirmed_at);
    }

    public function test_event_on_a_different_date_can_be_confirmed(): void
    {
        $admin = $this->admin();
        $this->setStatus($admin, $this->enquiry(), 'confirmed');
        $other = $this->enquiry(['event_date' => '2026-12-02']);

        $this->setStatus($admin, $other, 'confirmed')->assertSessionHasNoErrors();

        $this->assertSame('confirmed', $other->refresh()->status);
    }

    public function test_declining_a_booking_frees_the_date(): void
    {
        $admin = $this->admin();
        $first = $this->enquiry(['name' => 'First']);
        $this->setStatus($admin, $first, 'confirmed');
        $this->setStatus($admin, $first, 'declined');

        $this->assertNull($first->refresh()->confirmed_at);

        $second = $this->enquiry(['name' => 'Second']);
        $this->setStatus($admin, $second, 'confirmed')->assertSessionHasNoErrors();
        $this->assertSame('confirmed', $second->refresh()->status);
    }

    public function test_completing_a_booking_keeps_its_confirmation_time(): void
    {
        $admin = $this->admin();
        $enquiry = $this->enquiry();
        $this->setStatus($admin, $enquiry, 'confirmed');
        $confirmedAt = $enquiry->refresh()->confirmed_at;

        $this->setStatus($admin, $enquiry, 'completed');

        $this->assertEquals($confirmedAt, $enquiry->refresh()->confirmed_at);
    }
}