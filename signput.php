<?php
/**
 * OpenDrop.
 *
 * Based on Carson McDonald's direct-browser-s3-upload-example.
 * https://github.com/carsonmcdonald/direct-browser-s3-upload-example
 *
 * @author   Patrick Schroen / https://github.com/pschroen
 * @license  MIT Licensed
 */

// Change the following settings
$S3_KEY = '';
$S3_SECRET = '';
$S3_BUCKET = '/opendrop'; // Make sure to leave the / on the front of the bucket here
$EXPIRE_TIME = 60*5; // 5 minutes
$S3_URL = 'http://s3.amazonaws.com';

$objectName = '/'.$_GET['box'].'/'.rawurlencode($_GET['name']);
$mimeType = $_GET['type'];
$expires = time()+$EXPIRE_TIME;
$amzHeaders = 'x-amz-acl:public-read';
$stringToSign = "PUT\n\n$mimeType\n$expires\n$amzHeaders\n$S3_BUCKET$objectName";
$sig = rawurlencode(base64_encode(hash_hmac('sha1', $stringToSign, $S3_SECRET, true)));
$url = rawurlencode("$S3_URL$S3_BUCKET$objectName?AWSAccessKeyId=$S3_KEY&Expires=$expires&Signature=$sig");
echo $url;
?>
