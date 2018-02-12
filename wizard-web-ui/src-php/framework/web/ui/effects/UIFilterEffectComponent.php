<?php
namespace framework\web\ui\effects;

use php\lib\str;

/**
 * Class UIFilterEffectComponent
 * @package framework\web\ui\effects
 */
abstract class UIFilterEffectComponent extends UIEffectComponent
{
    private $currentFilter = '';

    abstract protected function makeCssFilter(): string;

    /**
     * @event apply
     */
    protected function handleApply()
    {
        $filter = $this->owner->css('filter');

        if ($this->currentFilter) {
            $filter = str::replace($filter, $this->currentFilter, '');
        }

        $filter = str::split($filter, ' ');
        $filter = flow($filter)->find(function ($value) { return (bool) trim($value); })->toArray();

        $filter[] = $this->currentFilter = $this->makeCssFilter();

        $value = str::join($filter, ' ');
        $this->owner->css(['filter' => $value, '-webkit-filter' => $value]);
    }

    /**
     * @event reset
     */
    protected function handleReset()
    {
        $filter = $this->owner->css('filter');

        if ($this->currentFilter) {
            $filter = str::replace($filter, $this->currentFilter, '');
        }

        $filter = str::split($filter, ' ');
        $filter = flow($filter)->find(function ($value) { return (bool) trim($value); })->toArray();

        $value = str::join($filter, ' ') ?: null;

        $this->owner->css(['filter' => $value, '-webkit-filter' => $value]);
    }
}