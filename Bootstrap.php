<?php 

namespace mata\media;

use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use mata\media\models\Media;
use matacms\controllers\module\Controller;

class Bootstrap implements BootstrapInterface 
{

	public function bootstrap($app) 
	{

		if ($this->canRun($app) == false)
			return;
		
		Event::on(Controller::class, Controller::EVENT_MODEL_CREATED, function(\matacms\base\MessageEvent $event) {
			$this->updateDocumentIds($event->getMessage());
		});

	}

	private function updateDocumentIds($model) 
	{
		$tmpDocumentIds = \Yii::$app->request->post('Media');
		if(!empty($tmpDocumentIds)) {
			foreach ($tmpDocumentIds as $tmpDocumentId) {
				$this->updateDocumentId($model, $tmpDocumentId);
			}
		}
				
	}

	private function updateDocumentId($model, $tmpDocumentId)
	{
		$attributePos = strpos($tmpDocumentId, "::");
		if($attributePos)
			$attribute = substr(substr($tmpDocumentId, $attributePos), 2);

		$media = Media::find()->where(["DocumentId" => $tmpDocumentId])->one();

		if($tmpDocumentId && !empty($media) && $model->getDocumentId()) {
			$media->DocumentId = $model->getDocumentId($attribute);
			if ($media->save() == false)
				throw new \yii\web\HttpException(500, $media->getTopError());
		}
	}

	private function canRun($app) 
	{
		return is_a($app, "yii\console\Application") == false;
	}
	
}