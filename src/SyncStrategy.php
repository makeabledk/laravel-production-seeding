<?php

namespace Makeable\ProductionSeeding;

trait SyncStrategy
{
    use BaseStrategy;

    /**
     * @param  $rows
     * @param  $query
     * @param  null  $compareKey
     */
    protected function apply($rows, $query, $compareKey = null)
    {
        [$rows, $query, $compareKey] = $this->normalizeArgs($rows, $query, $compareKey);

        // Delete existing rows not in new rows list
        (clone $query)->whereNotIn($compareKey, $rows->pluck($compareKey))->delete();

        $this
            // Apply any generic behavior
            ->pipe($rows)

            // Create none-existing rows
            ->each(function ($row) use ($query, $compareKey) {
                $model = get_class($query->getModel());
                $model::unguarded(function () use ($query, $compareKey, $row) {
                    (clone $query)->firstOrNew([$compareKey => $row[$compareKey]])->fill($row)->save();
                });
            });
    }
}
