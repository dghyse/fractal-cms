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
            $administrator = User::createUser(
                Constant::ROLE_ADMIN,
                $email,
                $password,
                $firstname,
                $lastname
            );

            if ($administrator->hasErrors() === false) {
                $this->stdout('Save administrator '.$email.' '.$password."\n");
            } else {
                $this->stdout('Administrator is invalid : '.Json::encode($administrator->errors)."\n");
                return ExitCode::UNSPECIFIED_ERROR;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
