<?php

class Face {
    public $left = 0;
    public $top = 0;
    public $width = 0;
    public $height = 0;

    function array () {

        $result = [];
        foreach ($this as $key => $value) {
            $result[$key] = $value;
        }
        return $result;
    }
}