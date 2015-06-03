<?php
namespace app\components;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{

	public static $emulateLoggedOut = false;

    // public $isGuest = function() {}
    public $id = 1;

    public static function findIdentity($id) {}

    public static function findIdentityByAccessToken($token, $type = null) {}

    public function getId() {
        return self::$emulateLoggedOut ? null : $this->id;
    }

    public function __get($name) {
    	if ($name == "isGuest")
    		return self::$emulateLoggedOut;

    	return parent::__get($name);
    }

    public function getAuthKey() {}
    public function validateAuthKey($authKey) {}
}
