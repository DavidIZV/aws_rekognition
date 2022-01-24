<?php

class Celebrity {
    public $name = 0;
    public $url = 0;
    public $face = NULL;

    function array () {

        $result = [];
        foreach ($this as $key => $value) {
            $result[$key] = $value;
        }
        return $result;
    }
}