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
use fractalCms\models\Content;
use fractalCms\models\Slug;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Json;

class InitController extends Controller
{
    /**
     * Init first content
     *
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        try {
            $this->stdout('Create home section '."\n");
            $content = Yii::createObject(Content::class);
            $content->scenario = Content::SCENARIO_INIT;
            $content->name = 'home';
            $content->type = 'section';
            $content->pathKey = '1';
            $content->active = 1;
            if ($content->validate() === true) {
                $slug = Yii::createObject(Slug::class);
                $slug->scenario = Slug::SCENARIO_CREATE;
                $slug->path = 'home';
                $slug->active = 1;
                if ($slug->save() === true) {
                    $content->slugId = $slug->id;
                    $content->save();
                    $this->stdout('Save home section '.$content->name.' '.$content->type."\n");
                }  else {
                    $this->stdout('Home section is invalid : '.Json::encode($slug->errors)."\n");
                    return ExitCode::UNSPECIFIED_ERROR;
                }

            } else {
                $this->stdout('Main section is invalid : '.Json::encode($content->errors)."\n");
                return ExitCode::UNSPECIFIED_ERROR;
            }
            return ExitCode::OK;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
