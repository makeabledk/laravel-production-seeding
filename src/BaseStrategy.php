<?php

namespace Makeable\ProductionSeeding;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait BaseStrategy
{
    /**
     * some description.
     *
     * @param  Collection  $rows
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
     * @param $query
     * @param  null  $compareKey
     * @return array
     */
    protected function normalizeArgs($rows, $query, $compareKey = null)
    {
        /** @var $query Builder; */
        $query = is_string($query) ? $query::query() : $query;

        return [
            $rows instanceof Collection ? $rows : collect($rows),
            $query,
            $compareKey ?: $query->getModel()->getKeyName(),
        ];
    }
}
