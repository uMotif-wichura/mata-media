<?php

namespace mata\media\tests;

use Codeception\Specify;
use yii\codeception\TestCase;
use yii\base\InvalidParamException;
use mata\media\models\Media;
use AspectMock\Test as test;
use mata\user\models\User;
use Codeception\Util\Stub;
use yii\web\Application;
use matacms\environment\models\ItemEnvironment;
use tests\codeception\fixtures\ItemEnvironmentFixture;
use tests\codeception\fixtures\RevisionFixture;
use tests\codeception\fixtures\MediaFixture;
use mata\arhistory\models\Revision;

class MediaTest extends TestCase
{
    private static $appCreated = false;

    public function fixtures() {
        return [
            'media' => [
                'class' => MediaFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/init_empty.php'
            ],

            'itemEnvironment' => [
                'class' => ItemEnvironmentFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/init_empty.php'
            ],

            'revision' => [
                'class' => RevisionFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/init_empty.php'
            ],
        ];
    }
      
      /**
       * @inheritdoc
       */
      protected function setUp()
      {

        $this->logIn();
        if (self::$appCreated == false) {
            $this->mockApplication();
            self::$appCreated = true;
        }

        $this->setBodyParams([]);
        $this->setQueryParams([]);
        $this->unloadFixtures();
        $this->loadFixtures();
      }

      /**
       * @inheritdoc
       */
      protected function tearDown() {}

    public function testSuccessfullySavedMediaCreatesRevision() {
       
        $this->assertEquals(0, count(Media::find()->all()));
        $this->assertEquals(0, count(Revision::find()->all()));

        $this->assertFalse(\Yii::$app->get('user', false)->isGuest);

        $media = new Media();
        $media->Name = "s";
        $media->URI = time() . "";
        $media->MimeType = "z";
        $media->For = time(). "";

        $this->assertTrue($media->save(), $media->getTopError());
        $this->assertEquals(1, count(Media::find()->all()));
        $this->assertEquals(1, count(Revision::find()->all()));

        $revision = Revision::find()->where(["DocumentId" => $media->getdocumentId()->getId()])->one();
        $this->assertNotNull($revision);
    }

    public function testSuccessfullySavedMediaCreatesEnvironmentRecord() {

        $environmentModule = \Yii::$app->getModule("environment");
        $expectedEnvironment = $environmentModule::DEFAULT_LIVE_ENVIRONMENT;
        $this->setBodyParams([ItemEnvironment::REQ_PARAM_ITEM_ENVIRONMENT => $expectedEnvironment]);

        $media = new Media();
        $media->Name = "s";
        $media->URI = time() . "";
        $media->MimeType = "z";
        $media->For = time(). "";

        $this->assertTrue($media->save(), $media->getTopError());

        $itemEnvironment = ItemEnvironment::find()->where(["DocumentId" => $media->getdocumentId()->getId()])->one();
        $this->assertNotNull($itemEnvironment);
        $this->assertEquals($expectedEnvironment, $itemEnvironment->Status);

    }

    public function testCorrectVersionIsReturnedForMedia() {

        $environmentModule = \Yii::$app->getModule("environment");
        $expectedEnvironment = $environmentModule::DEFAULT_LIVE_ENVIRONMENT;
        $this->setBodyParams([ItemEnvironment::REQ_PARAM_ITEM_ENVIRONMENT => $expectedEnvironment]);

        $media = new Media();
        $media->Name = "s";
        $media->URI = time() . "";
        $media->MimeType = "z";
        $media->For = time(). "";

        $this->assertTrue($media->save(), $media->getTopError());

        $media->MimeType = "y";

        $this->assertTrue($media->save(), $media->getTopError());

        $this->assertEquals(2, count(Revision::find()->all()));
        $this->assertEquals(2, count(ItemEnvironment::find()->all()));

        // Since we are logged into the CMS we should get the latest version
        $mediaFromDb = Media::find()->where(["Id" => $media->Id])->one();

        $this->assertNotNull($mediaFromDb);
        $this->assertEquals($mediaFromDb->MimeType, $media->MimeType);

    }

    /**
     * Publish the first version, draft the second one, make sure version one is returned
     */
    public function testCorrectVersionIsReturnedForMediaForLoggedOutUser() {

        $environmentModule = \Yii::$app->getModule("environment");
        $this->setBodyParams([ItemEnvironment::REQ_PARAM_ITEM_ENVIRONMENT => $environmentModule::DEFAULT_LIVE_ENVIRONMENT]);

        $media = new Media();
        $media->Name = "s";
        $media->URI = time() . "";
        $media->MimeType = "1stVersion";
        $media->For = time(). "";

        $this->assertTrue($media->save(), $media->getTopError());

        $this->setBodyParams([ItemEnvironment::REQ_PARAM_ITEM_ENVIRONMENT => $environmentModule::DEFAULT_STAGE_ENVIRONMENT]);

        $media->MimeType = "2ndVersion";

        $this->assertTrue($media->save(), $media->getTopError());
        $this->assertEquals("2ndVersion", Media::find()->where(["Id" => $media->Id])->one()->MimeType);

        $this->logOut();
        $this->assertTrue(\Yii::$app->user->isGuest);
        $this->assertEquals("1stVersion", Media::find()->where(["Id" => $media->Id])->one()->MimeType);

    }

    public function testCorrectVersionIsReturnedWhenAskingForASpecificOne() {

   
        $media = new Media();
        $media->Name = "s";
        $media->URI = time() . "";
        $media->MimeType = "1stVersion";
        $media->For = time(). "";

        $this->assertTrue($media->save(), $media->getTopError());
        $media->MimeType = "2ndVersion";

        $this->assertTrue($media->save(), $media->getTopError());
        $this->assertEquals("2ndVersion", Media::find()->where(["Id" => $media->Id])->one()->MimeType);

        $this->setQueryParams([ItemEnvironment::REQ_PARAM_REVISION => 1]);
        $this->assertEquals(1, \Yii::$app->getRequest()->get(ItemEnvironment::REQ_PARAM_REVISION));

        $this->assertEquals("1stVersion", Media::find()->where(["Id" => $media->Id])->one()->MimeType);

    }

    public function testModelGetsDeletedWhenFromForm() {

        $environmentModule = \Yii::$app->getModule("environment");
        
        $media = new Media();
        $media->Name = "s";
        $media->URI = time() . "";
        $media->MimeType = "1stVersion-published";
        $media->For = time(). "";

        $this->setBodyParams([ItemEnvironment::REQ_PARAM_ITEM_ENVIRONMENT => $environmentModule::DEFAULT_LIVE_ENVIRONMENT]);
        $this->assertTrue($media->save(), $media->getTopError());

        $this->setBodyParams([ItemEnvironment::REQ_PARAM_ITEM_ENVIRONMENT => $environmentModule::DEFAULT_STAGE_ENVIRONMENT]);
        $media->MimeType = "2ndVersion-saved";
        $this->assertTrue($media->save(), $media->getTopError());

        $media->MimeType = "3rdVersion-deleted";
        $this->assertTrue($media->save(), $media->getTopError());

        // hack for our testing
        $r = Revision::find()->where(["Revision" => 3])->one();
        $r->Status = 0;
        $r->save();

        // Logged in version should be null
        $this->assertNull(Media::find()->where(["Id" => $media->Id])->one()); 
         
        // Logged out user should get version 1 
        $this->logOut();
        $this->assertEquals("1stVersion-published", Media::find()->where(["Id" => $media->Id])->one()->MimeType);

    }

    private function logOut() {
       \app\components\User::$emulateLoggedOut = true;
    }     

    private function logIn() {
       \app\components\User::$emulateLoggedOut = false;
    }         

    // POST
    private function setBodyParams($params) {
        \Yii::$app->getRequest()->setBodyParams($params);
    }

    // GET
    private function setQueryParams($params) {
        \Yii::$app->getRequest()->setQueryParams($params);
    }
}

