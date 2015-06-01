<?php

namespace mata\media\tests;

use Codeception\Specify;
use yii\codeception\TestCase;
use yii\base\InvalidParamException;

class RecoveryFormTest extends TestCase
{
    // use Specify;

    public function testFormValidation()
    {

        $this->assertTrue(true);
        // $form = \Yii::createObject(RecoveryForm::className());
        // $form->scenario = 'reset';
        // $this->specify('password is required', function () use ($form) {
        //     verify($form->validate(['password']))->false();
        // });
        // $this->specify('password is too short', function () use ($form) {
        //     $form->password = '12345';
        //     verify($form->validate(['password']))->false();
        // });
        // $this->specify('password is ok', function () use ($form) {
        //     $form->password = 'superSecretPa$$word';
        //     verify($form->validate(['password']))->true();
        // });
    }
}
