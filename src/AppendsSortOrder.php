<?php

namespace Makeable\ProductionSeeding;

use Illuminate\Support\Collection;

trait AppendsSortOrder
{
    /**
     * @return string
     */
    protected function getSortKey()
    {
        return property_exists($this, 'sortKey')
            ? $this->sortKey
            : 'order';
    }

    /**
     * @param  Collection  $rows
     * @return Collection
     */
    protected function sort(Collection $rows)
    {
        return $rows->map(function ($row, $index) {
            return array_merge($row, [$this->getSortKey() => $index]);
        });
    }
}
