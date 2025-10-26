<?php
/**
 * AuthorController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\console
 */
namespace fractalCms\console;

use Exception;
use fractalCms\components\Constant;
use fractalCms\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class AuthorController extends Controller
{
    /**
     * Create Author
     *
     * @return int|void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        try {
            $this->stdout('Create new auhtor'."\n");
            $email = $this->prompt("\t".'email :');
            $password = $this->prompt("\t".'password :');
            $firstname = $this->prompt("\t".'firstname :');
            $lastname = $this->prompt("\t".'lastname :');
            $author = Yii::createObject(User::class);
            $author->scenario = User::SCENARIO_CREATE;
            $author->email = $email;
            $author->tmpPassword = $password;
            $author->firstname = $firstname;
            $author->lastname = $lastname;
            if ($author->validate() === true) {
                $author->hashPassword();
                $author->save();
                $this->stdout('Save auhtor '.$author->email.' '.$author->tmpPassword."\n");
            } else {
                $this->stdout('auhtor is invalid'."\n");
                return ExitCode::UNSPECIFIED_ERROR;
            }
            $role = Yii::$app->authManager->getRole(Constant::ROLE_AUTHOR);
            if ($role !== null) {
                Yii::$app->authManager->assign($role, $author->id);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
