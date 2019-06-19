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
            "js/slider/bootstrap-slider.min.js" => [
                "type" => "text/javascript",
                "content" => "res://js/slider/bootstrap-slider.min.js"
            ],
            "js/Slider.js" => [
                "type" => "text/javascript",
                "content" => "res://js/Slider.js"
            ],
            "css/slider/bootstrap-slider.min.css" => [
                "type" => "text/css",
                "content" => "res://css/slider/bootstrap-slider.min.css"
            ]
        ];
    }
}