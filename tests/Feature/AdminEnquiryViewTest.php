<?php

namespace Tests\Feature;

use App\Models\CateringEnquiry;
use App\Models\Package;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * AQL-51 — Tests for the admin view.
 *
 * Covers: who can reach the admin panel, the catering enquiry list and
 * detail pages (including the EP2 package row), status updates, and the
 * AQL-71 pagination fix.
 */
class AdminEnquiryViewTest extends TestCase
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

    private function makeEnquiry(array $overrides = []): CateringEnquiry
    {
        return CateringEnquiry::create(array_merge([
            'name' => 'Jane Guest',
            'email' => 'jane@example.com',
            'phone' => '0400000000',
            'event_date' => now()->addWeeks(2)->toDateString(),
            'guest_count' => 40,
            'event_type' => 'wedding',
            'message' => 'Looking for a spring wedding venue.',
            'status' => 'new',
        ], $overrides));
    }

    private function makePackage(): Package
    {
        return Package::create([
            'name' => 'Celebration Package',
            'subtitle' => 'Test subtitle',
            'slug' => 'celebration-package',
            'description' => 'Test description',
            'price' => 1500,
            'inclusions' => "Item one\nItem two",
            'is_active' => true,
            'is_popular' => false,
            'sort_order' => 1,
        ]);
    }

    // --- Access control -------------------------------------------------

    public function test_guest_is_redirected_to_admin_login(): void
    {
        // Regression test for the "Route [login] not defined" crash.
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_user_without_admin_role_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_open_the_dashboard(): void
    {
        $this->actingAs($this->admin())->get('/admin')->assertOk();
    }

    // --- Catering enquiries ---------------------------------------------

    public function test_admin_sees_enquiries_in_the_list(): void
    {
        $this->makeEnquiry(['name' => 'Jane Guest']);

        $this->actingAs($this->admin())
            ->get(route('admin.catering-enquiries.index'))
            ->assertOk()
            ->assertSee('Jane Guest');
    }

    public function test_detail_page_shows_the_chosen_package(): void
    {
        $package = $this->makePackage();
        $enquiry = $this->makeEnquiry(['package_id' => $package->id]);

        $this->actingAs($this->admin())
            ->get(route('admin.catering-enquiries.show', $enquiry))
            ->assertOk()
            ->assertSee('Jane Guest')
            ->assertSee('Celebration Package');
    }

    public function test_detail_page_shows_not_sure_yet_when_no_package(): void
    {
        $enquiry = $this->makeEnquiry(['package_id' => null]);

        $this->actingAs($this->admin())
            ->get(route('admin.catering-enquiries.show', $enquiry))
            ->assertOk()
            ->assertSee('Not sure yet');
    }

    public function test_admin_can_mark_an_enquiry_as_confirmed(): void
    {
        $enquiry = $this->makeEnquiry();

        $this->actingAs($this->admin())
            ->patch(route('admin.catering-enquiries.status', $enquiry), ['status' => 'confirmed'])
            ->assertRedirect();

        $this->assertDatabaseHas('catering_enquiries', [
            'id' => $enquiry->id,
            'status' => 'confirmed',
        ]);
    }

    // --- AQL-71 regression ----------------------------------------------

    public function test_menu_items_list_uses_bootstrap_pagination(): void
    {
        $this->seed(MenuSeeder::class); // 29 items, so the list has 2 pages

        $this->actingAs($this->admin())
            ->get('/admin/menu-items')
            ->assertOk()
            ->assertSee('<ul class="pagination">', false);
    }
}