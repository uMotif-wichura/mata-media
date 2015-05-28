<?php
 
/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace mata\media;

use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\Model;
use yii\db\BaseActiveRecord;
use mata\media\models\Media;
use matacms\controllers\module\Controller;
use mata\arhistory\models\Revision;

class Bootstrap implements BootstrapInterface 
{

	public function bootstrap($app) 
	{

		if ($this->canRun($app) == false)
			return;
		
		Event::on(Controller::class, Controller::EVENT_MODEL_CREATED, function(\matacms\base\MessageEvent $event) {
			$this->updateDocumentIds($event->getMessage());
		});

		Event::on(Controller::class, Controller::EVENT_MODEL_UPDATED, function(\matacms\base\MessageEvent $event) {
			$this->updateDocumentIds($event->getMessage());
		});

		Event::on(Model::class, Model::EVENT_BEFORE_VALIDATE, function(\yii\base\ModelEvent $event) {
			if($event->sender instanceof \mata\db\ActiveRecord) {
				$activeValidators = $event->sender->getActiveValidators();

				foreach($activeValidators as $validator) {				
					if(get_class($validator) != 'mata\media\validators\MandatoryMediaValidator')
						continue;

					$event->sender->addAdditionalAttribute('Media');
				}
			}
		});
	}

	private function manageMediaDeletions($model) {

		$existingMedia = Media::find()->where(["LIKE", "For", $model->documentId->getId()])->all();

		foreach ($existingMedia as $media) {

			$documentId = $media->For;

			if (!in_array($documentId, $_POST["Media"])) {
				// Media has been removed from the core model, update version
				$revision = $media->getLatestRevision();
				$revisionAttributes = unserialize($revision->Attributes);

				foreach ($revisionAttributes as $k => $v) {
					if ($k == "Id")
						continue;
					
					$revisionAttributes[$k] = null;
				}

				// $revision->Attributes = serialize($revisionAttributes);
				$newRevision = new Revision();#
				$newRevision->DocumentId = $revision->DocumentId;
				$newRevision->Revision = $revision->Revision + 1;
				$newRevision->Attributes = serialize($revisionAttributes);

				if ($newRevision->save() == false)
					throw new \yii\web\HttpException(500, $media->getTopError());
			}
		}

	}

	private function updateDocumentIds($model) 
	{
		$mediaFromRequest = \Yii::$app->request->post('Media', []);
		foreach ($mediaFromRequest as $sourceWidgetId => $mediaPayload) {

			if (array_key_exists("delete", $mediaPayload)) {
				
				$documentId = $mediaPayload["delete"];
				$media = Media::find()->where(["For" => $documentId])->one();
				$media->URI = "DELETED";

				if ($media->save(false) == false)
					throw new \yii\web\HttpException(500, $media->getTopError());

			} else {
				$this->updateDocumentId($model, $mediaPayload);
			}
		}
	}

	private function updateDocumentId($model, $mediaPayload) {
	
		if (empty($mediaPayload["DocumentId"])) {
			print_r($mediaPayload);
			exit;
		}

		$documentId = $mediaPayload["DocumentId"];
		$attributeName = null;

		$attributePos = strpos($documentId, "::");

		if($attributePos)
			$attributeName = substr(substr($documentId, $attributePos), 2);

		if ($attributeName == null)
			throw new \yii\web\HttpException(500, "attributeName cannot be null");


		$documentId = $model->getDocumentId($attributeName)->getId();

		$media = Media::find()->where(["For" => $documentId])->returnEmpty()->one();

		if ($media == null)
			$media = new Media();

		$media->attributes = $mediaPayload;
		$media->For = $documentId;

		if ($media->save() == false)
			throw new \yii\web\HttpException(500, $media->getTopError());

	}

	private function canRun($app) {
		return is_a($app, "yii\console\Application") == false;
	}
}
