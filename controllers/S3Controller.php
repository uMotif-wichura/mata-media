<?php 

namespace mata\media\controllers;

use Aws\S3\S3Client;
use mata\keyvalue\models\KeyValue;
use mata\media\models\Media;
use yii\helpers\Json;

class S3Controller extends \mata\web\Controller {

	// NOT THE RIGHT TO PLACE THESE - WHERE THOUGH?
	const S3_KEY = "S3_KEY";
	const S3_SECRET = "S3_SECRET";
	const S3_BUCKET = "S3_BUCKET";
	const S3_ENDPOINT = "S3_ENDPOINT";
	const S3_FOLDER = "S3_FOLDER";
	const S3_REGION = "S3_REGION";

	public function actionSignature() {

		$this->setResponseContentType("application/json");

		$s3Key = KeyValue::findbyKey(self::S3_KEY);
		$s3Secret = KeyValue::findbyKey(self::S3_SECRET);
		$s3Bucket = KeyValue::findbyKey(self::S3_BUCKET);

		// Instantiate the S3 client with your AWS credentials
		$s3Client = S3Client::factory(array(
			'key'    => $s3Key,
			'secret' => $s3Secret,
			'region' => KeyValue::findbyKey(self::S3_REGION)
			));

		$policyDocument = '{"expiration":"2100-01-01T00:00:00Z","conditions":[{"bucket": "' . $s3Bucket . '"},{"acl": "public-read"}, ["starts-with", "$key", ""], ["starts-with", "$Content-Type", ""], 
		["starts-with", "$success_action_status", ""], ["starts-with", "$x-amz-meta-qqfilename", ""]]}';

		$encodedPolicy = base64_encode($policyDocument);
		$signature = base64_encode(hash_hmac(
			'sha1',
			$encodedPolicy,
			$s3Secret,
			true
			));


		$response = array('policy' => $encodedPolicy, 'signature' => $signature);
		echo json_encode($response);
	}

	public function actionUploadSuccessful() {

		$s3Endpoint = KeyValue::findByKey(self::S3_ENDPOINT);
		$s3Bucket = KeyValue::findByKey(self::S3_BUCKET);

		$imageURL = $s3Endpoint .  $s3Bucket  . "/" . urlencode(\Yii::$app->getRequest()->post("key"));

		// $mediaWidth = null; 
		// $mediaHeight = null;
		// $mimeType = null;

		// $imageAttributes = getimagesize($imageURL);

		// if ($imageAttributes != null) {
		// 	$mediaWidth = $imageAttributes[0];
		// 	$mediaHeight = $imageAttributes[1];
		// 	$mimeType = $imageAttributes['mime'];
		// } else {
		// 	$ch = curl_init($imageURL);
		// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 	curl_exec($ch);
		// 	$mimeType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		// // }

		// 	print_r($mimeType);
		// 	exit;

		$model = new Media() ;
		$model->attributes = array(
			"Name" => \Yii::$app->getRequest()->post("name"),
			"DocumentId" => \Yii::$app->getRequest()->get("documentId"),
			"URI" => $imageURL,
			"Width" => 0,
			"Height" => 0,
			"MimeType" => "default"
			);

		if ($model->save() == false)
			throw new CHttpException(500, $model->getTopError());

		$this->setResponseContentType("application/json");
		echo Json::encode($model);
	}

}