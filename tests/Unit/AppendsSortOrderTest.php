<?php

namespace Makeable\ProductionSeeding\Tests\Unit;

use DB;
use Illuminate\Database\Schema\Blueprint;
use Makeable\ProductionSeeding\AppendsSortOrder;
use Makeable\ProductionSeeding\Tests\SyncTestSeeder;
use Makeable\ProductionSeeding\Tests\TestCase;
use Makeable\ProductionSeeding\Tests\TestModel;

class AppendsSortOrderTest extends TestCase
{
    public function test_it_seeds_rows_and_appends_sort_order()
    {
        $this->seedInline(new class extends SyncTestSeeder
        {
            use AppendsSortOrder;
        });
        $this->assertEquals(0, TestModel::first()->order);
        $this->assertEquals(count((new SyncTestSeeder)->rows), TestModel::count());
    }

    public function test_it_deletes_rows_and_regenerates_order()
    {
        $seeder = new class extends SyncTestSeeder
        {
            use AppendsSortOrder;
        };

        // Seed original
        $this->seedInline($seeder);

        // Remove second item and re-seed
        array_splice($seeder->rows, 1, 1);
        $this->seedInline($seeder);

        // Assert it re-indexed order
        $this->assertEquals(0, TestModel::find(1)->order);
        $this->assertEquals(1, TestModel::find(3)->order);
    }

    public function test_you_can_overwrite_order_key()
    {
        DB::connection()->getSchemaBuilder()->table('test_models', function (Blueprint $table) {
            $table->renameColumn('order', 'sortorder');
        });

        $seeder = new class extends SyncTestSeeder
        {
            use AppendsSortOrder;
            protected $sortKey = 'sortorder';
        };

        $this->seedInline($seeder);

        $this->assertEquals(2, TestModel::latest('id')->first()->sortorder);
    }
}
