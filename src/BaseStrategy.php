<?php

namespace Makeable\ProductionSeeding;

use Illuminate\Support\Collection;

trait BaseStrategy
{
    /**
     * some description.
     *
     * @param Collection $rows
     *
     * @return mixed
     */
    protected function pipe(Collection $rows)
    {
        return $rows
            // Sort if applicable
            ->when(method_exists($this, 'sort'), function ($rows) {
                return $this->sort($rows);
            });
    }

    /**
     * @param $rows
     * @param $class
     * @param null $compareKey
     *
     * @return array
     */
    protected function normalizeArgs($rows, $class, $compareKey = null)
    {
        return [
            $rows instanceof Collection ? $rows : collect($rows),
            $class,
            $compareKey ?: (new $class())->getKeyName(),
        ];
    }
}
