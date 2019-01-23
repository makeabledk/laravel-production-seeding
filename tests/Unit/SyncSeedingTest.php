<?php

namespace Makeable\ProductionSeeding\Tests\Unit;

use Makeable\ProductionSeeding\Tests\TestCase;
use Makeable\ProductionSeeding\Tests\TestModel;
use Makeable\ProductionSeeding\Tests\SyncTestSeeder;

class SyncSeedingTest extends TestCase
{
    public function test_it_seeds_through_artisan()
    {
        $this->seed(SyncTestSeeder::class);
        $this->assertEquals(count((new SyncTestSeeder)->rows), TestModel::count());
    }

    public function test_it_deletes_rows_but_preserves_ids()
    {
        // Seed original
        $seeder = new SyncTestSeeder();
        $this->seedInline($seeder);

        // Remove second item and re-seed
        array_splice($seeder->rows, 1, 1);
        $this->seedInline($seeder);

        // Assert one less item
        $this->assertEquals(count((new SyncTestSeeder)->rows) - 1, TestModel::count());

        $this->assertNotNull(TestModel::find(1));
        $this->assertNull(TestModel::find(2));
        $this->assertNotNull(TestModel::find(3));
    }

    public function test_it_deletes_items_matching_the_compare_key()
    {
        // Seed original
        $seeder = new SyncTestSeeder();
        $this->seedInline($seeder);

        // Remove second item and re-seed
        $removed = array_splice($seeder->rows, 1, 1);
        $this->seedInline($seeder);

        $this->assertEquals(0, TestModel::where('slug', $removed[0]['slug'])->count());
    }

    public function test_it_only_overwrites_the_columns_given()
    {
        $seeder = new SyncTestSeeder;
        $original = $seeder->rows[1];

        $this->seedInline($seeder);
        unset($seeder->rows[1]['name']);
        $this->seedInline($seeder);

        $this->assertEquals($original['name'], TestModel::where('slug', $original['slug'])->first()->name);
    }
}
