<?php
require 'vendor/autoload.php'; // Load AWS SDK
use Aws\S3\S3Client;

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1', // Replace with your S3 bucket's region
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    $bucket = 'ziadproject'; // Replace with your bucket name
    $key = basename($file['name']);
    $allowedTypes = ['pdf', 'jpg', 'png', 'txt'];
    $fileExt = strtolower(pathinfo($key, PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedTypes)) {
        try {
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'SourceFile' => $file['tmp_name'],
                'ACL'    => 'public-read', // Optional: Adjust for security
            ]);
            echo "File uploaded successfully. Download link: <a href='{$result['ObjectURL']}'>{$result['ObjectURL']}</a>";
        } catch (Exception $e) {
            echo "Error uploading file: " . $e->getMessage();
        }
    } else {
        echo "Error: Only .pdf, .jpg, .png, and .txt files are allowed.";
    }
}
?>