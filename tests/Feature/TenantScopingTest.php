<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Restaurant;
use App\Models\RestaurantBranch;
use App\Models\MenuItem;
use Illuminate\Support\Str;

class TenantScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_data_is_isolated()
    {
        $tenant1 = Tenant::factory()->create(['name' => 'Tenant One']);
        $tenant2 = Tenant::factory()->create(['name' => 'Tenant Two']);

        $restaurant1 = $tenant1->restaurants()->create(['name' => 'Resto 1', 'tenant_id' => $tenant1->id]);
        $restaurant2 = $tenant2->restaurants()->create(['name' => 'Resto 2', 'tenant_id' => $tenant2->id]);

        $branch1 = $restaurant1->branches()->create(['name' => 'Branch 1', 'tenant_id' => $tenant1->id]);
        $branch2 = $restaurant2->branches()->create(['name' => 'Branch 2', 'tenant_id' => $tenant2->id]);

        $menuItem1 = MenuItem::create(['name' => 'Burger', 'tenant_id' => $tenant1->id, 'restaurant_id' => $restaurant1->id]);
        $menuItem2 = MenuItem::create(['name' => 'Pizza', 'tenant_id' => $tenant2->id, 'restaurant_id' => $restaurant2->id]);

        // Assert tenant1 only sees its own data
        $this->assertCount(1, $tenant1->restaurants);
        $this->assertEquals('Resto 1', $tenant1->restaurants->first()->name);
        $this->assertCount(1, $tenant1->restaurants->first()->branches);
        $this->assertEquals('Branch 1', $tenant1->restaurants->first()->branches->first()->name);
        $this->assertEquals('Burger', $tenant1->restaurants->first()->menuItems->first()->name);

        // Assert tenant2 only sees its own data
        $this->assertCount(1, $tenant2->restaurants);
        $this->assertEquals('Resto 2', $tenant2->restaurants->first()->name);
        $this->assertCount(1, $tenant2->restaurants->first()->branches);
        $this->assertEquals('Branch 2', $tenant2->restaurants->first()->branches->first()->name);
        $this->assertEquals('Pizza', $tenant2->restaurants->first()->menuItems->first()->name);
    }
}
