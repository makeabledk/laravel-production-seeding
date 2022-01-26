<?php

namespace Makeable\ProductionSeeding;

trait AppendStrategy
{
    use BaseStrategy;

    /**
     * @param $rows
     * @param $class
     * @param  null  $compareKey
     */
    protected function apply($rows, $class, $compareKey = null)
    {
        [$rows, $class, $compareKey] = $this->normalizeArgs($rows, $class, $compareKey);

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
