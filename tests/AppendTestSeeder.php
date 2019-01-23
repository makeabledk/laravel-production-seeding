<?php

namespace Makeable\ProductionSeeding\Tests;

use Illuminate\Database\Seeder;
use Makeable\ProductionSeeding\AppendStrategy;

class AppendTestSeeder extends Seeder
{
    use AppendStrategy;

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

    public function run()
    {
        $this->apply($this->rows, TestModel::class, 'slug');
    }
}
