<?php
/**
 * AdminController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\console
 */
namespace fractalCms\console;

use Exception;
use fractalCms\components\Constant;
use fractalCms\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Json;

class AdminController extends Controller
{
    /**
     * Create Administrateur
     *
     * @return int|void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        try {
            $this->stdout('Create new administrator'."\n");
            $email = $this->prompt("\t".'email :');
            $password = $this->prompt("\t".'password :');
            $firstname = $this->prompt("\t".'firstname :');
            $lastname = $this->prompt("\t".'lastname :');
            $administrator = Yii::createObject(User::class);
            $administrator->scenario = User::SCENARIO_CREATE_ADMIN;
            $administrator->email = $email;
            $administrator->tmpPassword = $password;
            $administrator->firstname = $firstname;
            $administrator->lastname = $lastname;
            $administrator->active = true;
            if ($administrator->validate() === true) {
                $administrator->hashPassword();
                $administrator->save();
                $this->stdout('Save administrator '.$administrator->email.' '.$administrator->tmpPassword."\n");
            } else {
                $this->stdout('Administrator is invalid : '.Json::encode($administrator->errors)."\n");
                return ExitCode::UNSPECIFIED_ERROR;
            }
            $role = Yii::$app->authManager->getRole(Constant::ROLE_ADMIN);
            if ($role !== null) {
                Yii::$app->authManager->assign($role, $administrator->id);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
