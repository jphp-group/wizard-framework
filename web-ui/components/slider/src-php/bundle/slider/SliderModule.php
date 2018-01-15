<?php
namespace bundle\slider;

use framework\web\UIModule;

class SliderModule extends UIModule
{
    /**
     * @return array
     */
    public function getRequiredResources(): array
    {
        return [
            'res://js/slider/bootstrap-slider.min.js',
            'res://js/Slider.js',
            'res://css/slider/bootstrap-slider.min.css'
        ];
    }
}