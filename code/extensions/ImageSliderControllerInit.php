<?php

class ImageSliderControllerInit extends Extension
{
    /**
     * Retrieve the required javascripts
     *
     * @return array
     */
    public function getRequiredJavascript()
    {
        return [
            IMAGESLIDER_DIR . '/javascript/slick/slick.min.js',
            IMAGESLIDER_DIR . '/javascript/youtubebackground/jquery.youtubebackground.min.js',
            IMAGESLIDER_DIR . '/javascript/imageslider.js',
        ];
    }
}