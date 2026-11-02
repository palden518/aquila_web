<?php

namespace Tests\Feature;

use App\Mail\CateringEnquiryReceived;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * EP2 — Story: Package selection on booking form.
 *
 * Acceptance criteria: a guest can pick a specific admin-managed package
 * as part of the enquiry; "Not sure yet" is allowed; inactive or unknown
 * packages are rejected; the admin alert email is actually sent.
 */
class EventEnquiryPackageTest extends TestCase
{
    use RefreshDatabase;

    private int $seq = 0;

    private function makePackage(array $overrides = []): Package
    {
        $this->seq++;

        return Package::create(array_merge([
            'name' => "Test Package {$this->seq}",
            'subtitle' => 'Test subtitle',
            'slug' => "test-package-{$this->seq}",
            'description' => 'Test description',
            'price' => 1000,
            'inclusions' => "Item one\nItem two",
            'is_active' => true,
            'is_popular' => false,
            'sort_order' => $this->seq,
        ], $overrides));
    }

    private function validEnquiry(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test Guest',
            'email' => 'guest@example.com',
            'phone' => '0400000000',
            'event_date' => now()->addWeek()->toDateString(),
            'guest_count' => 30,
            'event_type' => 'corporate',
            'message' => 'Test enquiry',
        ], $overrides);
    }

    public function test_enquiry_saves_the_chosen_active_package_and_alerts_admin(): void
    {
        Mail::fake();
        $package = $this->makePackage();

        $response = $this->post(route('events.enquiry.store'), $this->validEnquiry([
            'package_id' => $package->id,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('enquiry_success');
        $this->assertDatabaseHas('catering_enquiries', [
            'email' => 'guest@example.com',
            'package_id' => $package->id,
        ]);

        // Outcome check, not just "page loaded": the admin email was really sent,
        // and it carries the chosen package.
        Mail::assertSent(CateringEnquiryReceived::class, function ($mail) use ($package) {
            return $mail->enquiry->package_id === $package->id;
        });
    }

    public function test_not_sure_yet_saves_enquiry_without_a_package(): void
    {
        Mail::fake();
        $this->makePackage();

        $response = $this->post(route('events.enquiry.store'), $this->validEnquiry([
            'package_id' => '',
        ]));

        $response->assertSessionHas('enquiry_success');
        $this->assertDatabaseHas('catering_enquiries', [
            'email' => 'guest@example.com',
            'package_id' => null,
        ]);
    }

    public function test_inactive_package_is_rejected(): void
    {
        Mail::fake();
        $retired = $this->makePackage(['is_active' => false]);

        $response = $this->post(route('events.enquiry.store'), $this->validEnquiry([
            'package_id' => $retired->id,
        ]));

        $response->assertSessionHasErrorsIn('catering', 'package_id');
        $this->assertDatabaseCount('catering_enquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_unknown_package_is_rejected(): void
    {
        Mail::fake();

        $response = $this->post(route('events.enquiry.store'), $this->validEnquiry([
            'package_id' => 999,
        ]));

        $response->assertSessionHasErrorsIn('catering', 'package_id');
        $this->assertDatabaseCount('catering_enquiries', 0);
    }
}