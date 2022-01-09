<?php
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

        $result = self::transferImage($s3, $bucket, $keyname, $filename,
                $uploadId, $parts);
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

    static private function preparePart ($s3, $bucket, $keyname, $filename,
            $uploadId) {

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
            $result = $s3->abortMultipartUpload(
                    [
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

    static private function transferImage ($s3, $bucket, $keyname, $filename,
            $uploadId, $parts) {

        // Complete the multipart upload.
        $result = $s3->completeMultipartUpload(
                [
                        'Bucket' => $bucket,
                        'Key' => $keyname,
                        'UploadId' => $uploadId,
                        'MultipartUpload' => $parts
                ]);
        $url = $result['Location'];

        Util::print("Uploaded {$filename} to {$url}.");

        return $result;
    }
}