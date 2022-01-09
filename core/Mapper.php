<?php

class Mapper {

    static function translateDataAws ($data) {

        $result = [];

        $faces = $data["FaceDetails"];

        foreach ($faces as $face) {
            $newFace = new Face();
            $newFace->left = $face["BoundingBox"]["Left"];
            $newFace->top = $face["BoundingBox"]["Top"];
            $newFace->width = $face["BoundingBox"]["Width"];
            $newFace->height = $face["BoundingBox"]["Height"];
            array_push($result, $newFace);
        }
        return $result;
    }
}