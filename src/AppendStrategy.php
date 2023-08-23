<?php

namespace Makeable\ProductionSeeding;

trait AppendStrategy
{
    use BaseStrategy;

    /**
     * @param $rows
     * @param $query
     * @param  null  $compareKey
     */
    protected function apply($rows, $query, $compareKey = null)
    {
        [$rows, $query, $compareKey] = $this->normalizeArgs($rows, $query, $compareKey);

        $this
            // Apply any generic behavior
            ->pipe($rows)

            // Create none-existing rows
            ->each(function ($row) use ($query, $compareKey) {
                $model = get_class($query->getModel());
                $model::unguarded(function () use ($query, $compareKey, $row) {
                    $query->firstOrNew([$compareKey => $row[$compareKey]])->fill($row)->save();
                });
            });
    }
}
