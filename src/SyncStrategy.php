<?php

namespace Makeable\ProductionSeeding;

trait SyncStrategy
{
    use BaseStrategy;

    /**
     * @param $rows
     * @param $class
     * @param null $compareKey
     */
    protected function apply($rows, $class, $compareKey = null)
    {
        [$rows, $class, $compareKey] = $this->normalizeArgs($rows, $class, $compareKey);

        // Delete existing rows not in new rows list
        $class::whereNotIn($compareKey, $rows->pluck($compareKey))->delete();

        $this
            // Apply any generic behavior
            ->pipe($rows)

            // Create none-existing rows
            ->each(function ($row) use ($class, $compareKey) {
                $class::unguarded(function () use ($class, $compareKey, $row) {
                    $class::firstOrNew([$compareKey => $row[$compareKey]])->fill($row)->save();
                });
            });
    }
}
