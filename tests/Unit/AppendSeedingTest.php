<?php

namespace Makeable\ProductionSeeding\Tests\Unit;

use Makeable\ProductionSeeding\Tests\AppendTestSeeder;
use Makeable\ProductionSeeding\Tests\TestCase;
use Makeable\ProductionSeeding\Tests\TestModel;

class AppendSeedingTest extends TestCase
{
    public function test_it_seeds_through_artisan()
    {
        $this->seed(AppendTestSeeder::class);
        $this->assertEquals(count((new AppendTestSeeder())->rows), TestModel::count());
    }

    public function test_it_doesnt_delete_rows()
    {
        $custom = TestModel::forceCreate([
            'slug' => 'some_other',
            'name' => 'Some other',
        ]);

        $this->seed(AppendTestSeeder::class);

        $this->assertEquals('some_other', TestModel::find($custom->id)->slug);
        $this->assertEquals(count((new AppendTestSeeder())->rows) + 1, TestModel::count());
    }
}
