<?php

namespace Makeable\ProductionSeeding\Tests;


use Illuminate\Database\Seeder;
use Makeable\ProductionSeeding\SyncStrategy;

class TestSeeder extends Seeder
{

    use SyncStrategy;

    /**
     * @var array
     */
    public $rows = [
        [
            'slug' => 'foo',
            'name' => 'Foo',
        ],
        [
            'slug' => 'bar',
            'name' => 'Bar',
        ],
        [
            'slug' => 'foobar',
            'name' => 'FooBar',
        ],
    ];

    /**
     */
    public function run()
    {
        $this->apply($this->rows, TestModel::class, 'slug');
    }
}
