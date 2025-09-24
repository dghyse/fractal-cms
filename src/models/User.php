<?php
/**
 * User.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\models
 */
namespace fractalCms\models;

use Exception;
use fractalCms\components\Constant;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $password
 * @property string|null $lastname
 * @property string|null $firstname
 * @property string|null $authKey
 * @property string|null $token
 * @property int|null $active
 * @property string|null $dateCreate
 * @property string|null $dateUpdate
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_CREATE_ADMIN = 'create-admin';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_MOT_PASSE = 'mot-de-passe';
    const LOGIN_DURATION = 3600;

    public $tmpPassword;
    public $tmpCheckPassword;

    public $authRules = [];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'dateCreate',
            'updatedAtAttribute' => 'dateUpdate',
            'value' => new Expression('NOW()'),
        ];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_CREATE] = [
            'email', 'password', 'lastname', 'firstname', 'dateCreate', 'dateUpdate', 'authKey', 'token', 'tmpPassword', 'active', 'tmpCheckPassword', 'authRules'
        ];
        $scenarios[static::SCENARIO_CREATE_ADMIN] = [
            'email', 'password', 'lastname', 'firstname', 'dateCreate', 'dateUpdate', 'authKey', 'token', 'tmpPassword', 'active', 'tmpCheckPassword', 'authRules'
        ];
        $scenarios[static::SCENARIO_UPDATE] = [
            'email', 'password', 'lastname', 'firstname', 'dateCreate', 'dateUpdate', 'authKey', 'token', 'tmpPassword', 'active', 'tmpCheckPassword', 'authRules'
        ];
        $scenarios[static::SCENARIO_LOGIN] = [
            'email', 'tmpPassword'
        ];
        $scenarios[static::SCENARIO_MOT_PASSE] = [
            'tmpCheckPassword', 'tmpPassword'
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password', 'lastname', 'firstname', 'dateCreate', 'dateUpdate', 'authKey', 'token'], 'default', 'value' => null],
            [['active'], 'default', 'value' => 0],
            [['active'], 'integer'],
            [['dateCreate', 'dateUpdate', 'authRules'], 'safe'],
            [['email', 'lastname', 'firstname'], 'string', 'max' => 255],
            [['password', 'tmpPassword'], 'string', 'max' => 64],
            [['email', 'lastname', 'firstname', 'tmpPassword'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_CREATE_ADMIN]],
            [['tmpCheckPassword'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['email'], 'email',  'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE, self::SCENARIO_CREATE_ADMIN], 'message' => 'L\'email doit être conforme'],
            [['email'], 'unique', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE, self::SCENARIO_CREATE_ADMIN], 'message' => 'L\'email doit être unique'],
            [['authKey'], 'unique', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE, self::SCENARIO_CREATE_ADMIN]],
            [['token'], 'unique', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE, self::SCENARIO_CREATE_ADMIN]],
            [['email', 'tmpPassword'], 'required', 'on' => [self::SCENARIO_LOGIN]],
            [['tmpCheckPassword', 'tmpPassword'], 'required', 'on' => [self::SCENARIO_MOT_PASSE], 'message' => 'Le nouveau mot de passe et la validation sont requises'],
            [[ 'tmpPassword'], 'match', 'pattern' => '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,16}$/', 'on' => [self::SCENARIO_MOT_PASSE, self::SCENARIO_CREATE_ADMIN], 'message' => 'Mot de passe : format invalide'],
            [[ 'tmpPassword'], 'compare', 'compareAttribute' => 'tmpCheckPassword', 'on' => [self::SCENARIO_MOT_PASSE, self::SCENARIO_CREATE], 'message' => 'les mots de passe ne correspondent pas'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
            'active' => 'Active',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
        ];
    }

    public function getInitials()
    {
        try {
            $initials = '';
            $initials .= (empty($this->firstname) === false) ? substr(ucfirst($this->firstname), 0, 1) : '';
            $initials .= (empty($this->lastname) === false) ? substr(ucfirst($this->lastname), 0, 1) : '';
            return $initials;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function manageAuthRules()
    {
        try {
            $auth = Yii::$app->authManager;
            if (empty($this->authRules) === false) {
                $auth->revokeAll($this->id);
                foreach ($this->authRules as $name => $rule) {
                    if ((boolean)$rule['value'] === true) {
                        $perm = $auth->getPermission($name);
                        if ($perm !== null) {
                            $auth->assign($perm, $this->id);
                        }
                    }
                    if (empty($rule['children']) === false) {
                        foreach ($rule['children'] as $nameChild => $child) {
                            if ((boolean)$child['value'] === true) {
                                $permChild = $auth->getPermission($nameChild);
                                if ($permChild !== null) {
                                    $auth->assign($permChild, $this->id);
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
    public function buildAuthRules()
    {
        try {
            $this->authRules = Constant::buildListRules($this);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function load($data, $formName = null)
    {
        if (isset($data[$this->formName()]['authRules']) === true) {
            $authRulesTmp = $data[$this->formName()]['authRules'];
            unset($data[$this->formName()]['authRules']);
            if (empty($authRulesTmp) === false) {
                foreach ($authRulesTmp as $name => $rule) {
                    $valueMain = $rule['value'];
                    $this->authRules[$name]['value'] = $valueMain;
                    if (empty($rule['children']) === false) {
                        foreach ($rule['children'] as $nameChild => $child) {
                            $valueChild = $child['value'];
                            $this->authRules[$name]['children'][$nameChild]['value'] = $valueChild;
                        }
                    }
                }
            }
        }
        return parent::load($data, $formName);
    }

    public function beforeValidate()
    {
        $this->active = (empty($this->active) === false) ? intval($this->active) : 0;
        return parent::beforeValidate();
    }

    public function beforeSave($insert) : bool
    {
        $this->hashPassword();
        if (empty($this->authKey) === true) {
            $this->authKey = Yii::$app->getSecurity()->generateRandomString();
        }
        return parent::beforeSave($insert);
    }

    public function hashPassword() : void
    {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->tmpPassword);
    }

    public function validatePassword($password) : bool
    {
        return (empty($password) === false) && Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getAuthKey() : string
    {
        return $this->authKey;
    }
    public function validateAuthKey($authKey) : bool
    {
        return empty($this->authKey) === false && $this->authKey === $authKey;
    }

    public static function findIdentity($id)
    {
        return static::find()->where(['id' => $id])->andWhere(['active' => true])->one();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['token' => $token])->andWhere(['active' => true])->one();
    }

}
