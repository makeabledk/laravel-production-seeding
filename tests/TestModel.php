<?php

namespace Makeable\ProductionSeeding\Tests;


class TestModel extends \Illuminate\Database\Eloquent\Model
{

    /**
     * @var string
     */
    protected $table = 'test_models';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

}
