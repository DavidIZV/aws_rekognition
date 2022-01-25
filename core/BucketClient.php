<?php
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class BucketClient {

    static function createAwsClient ($region, $key, $secret, $token) {

        $s3 = new S3Client(
                [
                        'version' => 'latest',
                        'region' => $region,
                        'credentials' => [
                                'key' => $key,
                                'secret' => $secret,
                                'token' => $token
                        ]
                ]);

        return $s3;
    }

    static function upload ($s3, $bucket, $keyname, $filename) {

        $result = self::createMultipart($s3, $bucket, $keyname);
        Util::print($result);
        $uploadId = $result['UploadId'];

        $parts = self::preparePart($s3, $bucket, $keyname, $filename, $uploadId);
        Util::print($result);

        $result = self::transferImage($s3, $bucket, $keyname, $filename, $uploadId, $parts);
        Util::print($result);

        return $result;
    }

    static private function createMultipart ($s3, $bucket, $keyname) {

        $result = $s3->createMultipartUpload(
                [
                        'Bucket' => $bucket,
                        'Key' => $keyname,
                        'StorageClass' => 'REDUCED_REDUNDANCY'
                ]);

        return $result;
    }

    static private function preparePart ($s3, $bucket, $keyname, $filename, $uploadId) {

        // Upload the file in parts.
        $parts = null;

        try {
            $file = fopen($filename, 'r');
            $partNumber = 1;
            while (! feof($file)) {
                $result = $s3->uploadPart(
                        [
                                'Bucket' => $bucket,
                                'Key' => $keyname,
                                'UploadId' => $uploadId,
                                'PartNumber' => $partNumber,
                                'Body' => fread($file, 5 * 1024 * 1024)
                        ]);
                $parts['Parts'][$partNumber] = [
                        'PartNumber' => $partNumber,
                        'ETag' => $result['ETag']
                ];

                Util::print("Uploading part {$partNumber} of {$filename}.");
                $partNumber ++;
            }
        } catch (S3Exception $e) {
            $result = $s3->abortMultipartUpload([
                    'Bucket' => $bucket,
                    'Key' => $keyname,
                    'UploadId' => $uploadId
            ]);

            Util::print("Upload of {$filename} failed.");
        } finally {
            fclose($file);
        }

        return $parts;
    }

    static private function transferImage ($s3, $bucket, $keyname, $filename, $uploadId, $parts) {

        // Complete the multipart upload.
        $result = $s3->completeMultipartUpload(
                [
                        'Bucket' => $bucket,
                        'Key' => $keyname,
                        'UploadId' => $uploadId,
                        'MultipartUpload' => $parts
                ]);
        Util::print("Respuesta de la subida: " . json_encode($result));
        $url = $result['Location'];

        Util::print("Uploaded {$filename} to {$url}.");

        return $result;
    }

    static public function analyzeImage ($region, $filename, $key, $secret, $token, $bucket, $imageName) {

        return self::rekognition($region, $filename, $key, $secret, $token, $bucket, $imageName, 0);
    }

    static public function searchCelebrities ($region, $filename, $key, $secret, $token, $bucket, $imageName) {

        return self::rekognition($region, $filename, $key, $secret, $token, $bucket, $imageName, 1);
    }

    static private function createRekognitionCLient ($region, $key, $secret, $token) {

        $credentials = new Aws\Credentials\Credentials($key, $secret, $token);
        $options = [
                'region' => $region,
                'version' => 'latest',
                'credentials' => $credentials
        ];

        return new RekognitionClient($options);
    }

    static private function rekognition ($region, $filename, $key, $secret, $token, $bucket, $imageName, $service = 0) {

        $rekognitionClient = self::createRekognitionCLient($region, $key, $secret, $token);

        $request = array(
                'Image' => [
                        'S3Object' => [
                                'Bucket' => $bucket,
                                'Name' => $imageName
                        ]
                ],
                'Attributes' => array(
                        'ALL'
                )
        );

        return self::rekognitionServices($rekognitionClient, $request, $service);
    }

    static public function listCollections ($region, $key, $secret, $token) {

        $rekognitionClient = self::createRekognitionCLient($region, $key, $secret, $token);

        $request = array(
                'Attributes' => array(
                        'ALL'
                )
        );

        return self::rekognitionServices($rekognitionClient, $request, 2);
    }

    static public function createCollection ($region, $collectionName, $key, $secret, $token) {

        $rekognitionClient = self::createRekognitionCLient($region, $key, $secret, $token);

        $request = array(
                'CollectionId' => $collectionName
        );

        return self::rekognitionServices($rekognitionClient, $request, 3);
    }

    static public function deleteCollection ($region, $collectionName, $key, $secret, $token) {

        $rekognitionClient = self::createRekognitionCLient($region, $key, $secret, $token);

        $request = array(
                'CollectionId' => $collectionName
        );

        return self::rekognitionServices($rekognitionClient, $request, 4);
    }

    static public function indexFaces ($region, $bucket, $imageName, $collectionName, $key, $secret, $token) {

        $rekognitionClient = self::createRekognitionCLient($region, $key, $secret, $token);

        $request = array(
                'CollectionId' => $collectionName,
                'Image' => [
                        'S3Object' => [
                                'Bucket' => $bucket,
                                'Name' => $imageName
                        ]
                ],
                "DetectionAttributes" => array(
                        'ALL'
                ),
                "ExternalImageId" => $imageName
        );

        return self::rekognitionServices($rekognitionClient, $request, 5);
    }

    static public function searchFacesByImage ($region, $bucket, $imageName, $collectionName, $key, $secret, $token,
            $accurancity) {

        $rekognitionClient = self::createRekognitionCLient($region, $key, $secret, $token);

        $request = array(
                'CollectionId' => $collectionName,
                "FaceMatchThreshold" => floatval($accurancity),
                'Image' => [
                        'S3Object' => [
                                'Bucket' => $bucket,
                                'Name' => $imageName
                        ]
                ],
                "DetectionAttributes" => array(
                        'ALL'
                ),
                "MaxFaces" => 25,
                "QualityFilter" => "LOW"
        );

        return self::rekognitionServices($rekognitionClient, $request, 6);
    }

    static private function rekognitionServices (RekognitionClient $rekognitionClient, $request, $service) {

        switch ($service) {
            case 0:
                $result = $rekognitionClient->DetectFaces($request);
                break;
            case 1:
                $result = $rekognitionClient->recognizeCelebrities($request);
                break;
            case 2:
                $result = $rekognitionClient->listCollections($request);
                break;
            case 3:
                $result = $rekognitionClient->createCollection($request);
                break;
            case 4:
                $result = $rekognitionClient->deleteCollection($request);
                break;
            case 5:
                $result = $rekognitionClient->indexFaces($request);
                break;
            case 6:
                $result = $rekognitionClient->searchFacesByImage($request);
                break;
        }

        Util::print("Datos de salida: " . json_encode($result["FaceDetails"]));
        return $result;
    }
}