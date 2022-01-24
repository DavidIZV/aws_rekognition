<?php

class Mapper {

    static function translateAwsFacesData ($response) {

        $result = [];

        $datas = $response["FaceDetails"];

        foreach ($datas as $data) {
            $newFace = new Face();
            $newFace->lowAge = $data["AgeRange"]["Low"];
            $newFace->highAge = $data["AgeRange"]["High"];
            $newFace->left = $data["BoundingBox"]["Left"];
            $newFace->top = $data["BoundingBox"]["Top"];
            $newFace->width = $data["BoundingBox"]["Width"];
            $newFace->height = $data["BoundingBox"]["Height"];
            array_push($result, $newFace);
        }

        return [
                "faces" => $result
        ];
    }

    static function translateAwsCelebritiesData ($response) {

        $result = [];

        $datas = $response["CelebrityFaces"];

        foreach ($datas as $data) {
            $newCelebrity = new Celebrity();
            $newCelebrity->name = $data["Name"];
            $newCelebrity->url = $data["Urls"][0];
            $newFace = new Face();
            $newFace->left = $data["Face"]["BoundingBox"]["Left"];
            $newFace->top = $data["Face"]["BoundingBox"]["Top"];
            $newFace->width = $data["Face"]["BoundingBox"]["Width"];
            $newFace->height = $data["Face"]["BoundingBox"]["Height"];
            $newCelebrity->face = $newFace;
            array_push($result, $newCelebrity);
        }

        return [
                "celebrities" => $result
        ];
    }
}