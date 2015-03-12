<?php

namespace mata\media\controllers;

use yii\filters\AccessControl;
use mata\keyvalue\models\KeyValue;

class RedactorController extends \yii\web\Controller {

	// NOT THE RIGHT TO PLACE THESE - WHERE THOUGH?
	const S3_KEY = "S3_KEY";
	const S3_SECRET = "S3_SECRET";
	const S3_BUCKET = "S3_BUCKET";
	const S3_ENDPOINT = "S3_ENDPOINT";
	const S3_REDACTOR_FOLDER = "S3_REDACTOR_FOLDER";

	public function behaviors() {
		return [
		'access' => [
		'class' => AccessControl::className(),
		'rules' => [
		[
		'allow' => true,
		'roles' => ['@'],
		],
		],
		]
		];
	}

	public function actionS3() {

		$S3_KEY = trim(KeyValue::findbyKey(self::S3_KEY));
		$S3_SECRET = trim(KeyValue::findbyKey(self::S3_SECRET));
		$S3_BUCKET = "/" . KeyValue::findbyKey(self::S3_BUCKET);

		$S3_URL = KeyValue::findbyKey(self::S3_ENDPOINT);
		$S3_FOLDER = KeyValue::findbyKey(self::S3_REDACTOR_FOLDER);;

		if ($S3_KEY == null || 
			$S3_SECRET == null || 
			$S3_BUCKET == null || 
			$S3_URL == null) 
			throw new \yii\base\InvalidConfigException("No S3 configuration found.");

		// expiration date of query
		$EXPIRE_TIME = (60 * 5); // 5 minutes

		$name = $_GET["name"];
		$pathInfo = pathinfo($name);
		$extension = isset($pathInfo["extension"]) ? $pathInfo["extension"] : "";

		$objectName = "/" . $S3_FOLDER . '/' . md5(time() . $name) . ".$extension";
		$mimeType = $_GET['type'];
		$expires = time() + $EXPIRE_TIME;
		$amzHeaders = "x-amz-acl:public-read";
		$stringToSign = "PUT\n\n$mimeType\n$expires\n$amzHeaders\n$S3_BUCKET$objectName";

		$sig = urlencode(base64_encode(hash_hmac('sha1', $stringToSign, $S3_SECRET, true)));
		$url = urlencode("$S3_URL$S3_BUCKET$objectName?AWSAccessKeyId=$S3_KEY&Expires=$expires&Signature=$sig");

		echo $url;
	}
}