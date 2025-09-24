<?php
/**
 * Html.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\helpers;

use Exception;
use fractalCms\models\ElasticModel;
use fractalCms\Module;
use Yii;
use yii\web\HttpException;

class Html extends \yii\helpers\Html
{

    const CONFIG_TYPE_STRING = 'string';
    const CONFIG_TYPE_TEXT = 'text';
    const CONFIG_TYPE_FILE = 'file';
    const CONFIG_TYPE_FILES = 'files';
    const CONFIG_TYPE_RADIO = 'radio';
    const CONFIG_TYPE_CHECKBOX = 'checkbox';
    const CONFIG_TYPE_WYSIWYG = 'wysiwyg';
    const CONFIG_TYPE_LIST = 'list';

    public $cachePath = 'cache';

    public static function activeInput($type, $model, $attribute, $options = [])
    {
        if ($model->hasErrors($attribute) === true) {
            $classes = ($options['class']) ?? '';
            $classes .= ' border-danger text-danger';
            $options['class'] = $classes;
        }
        return parent::activeInput($type, $model, $attribute, $options);
    }

    public static function activeDropDownList($model, $attribute, $items, $options = [])
    {
        if ($model->hasErrors($attribute) === true) {
            $classes = ($options['class']) ?? '';
            $classes .= ' border-danger text-danger';
            $options['class'] = $classes;
        }
        return parent::activeDropDownList($model, $attribute, $items, $options);
    }

    public static function img($src, $options = [])
    {
        $relative = $src;
        if (preg_match('/^(@.\w+)(\/.+)/', $src, $matches) == 1) {
            //Get initial relative path
            $relative = $matches[2];
            $relativeCache = static::getImgCache($src, $options);
            if ($relativeCache !== false) {
                $relative = $relativeCache;
            }
        }
        return parent::img($relative, $options);
    }

    public static function buildTemplateView($config, ElasticModel $model)
    {
        try {
            return yii::$app->controller->renderPartial(
                '@fractalCms/views/templates/template-item',
                ['model' => $model, 'config' => $config]
            );
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public static function getImgCache($src, $options = [])
    {
        try {
            $relativeDirName = Module::getInstance()->relativeImgDirName;
            $relative = false;
            $path = Yii::getAlias($src);
            if (file_exists($path) === true) {
                //Get cache path
                $itemId = '';
                if (preg_match('/\/'.$relativeDirName.'\/?(.+)\//', $src, $matchesRelativ) !== false) {
                    $itemId = $matchesRelativ[1];
                }
                $mimeType = mime_content_type($path);
                if (strncmp('image/', $mimeType, 6) === 0 && strncmp('image/svg', $mimeType, 9) !== 0) {
                    $cachePath = static::prepareCacheDir($itemId);
                    //Get information
                    $pathInfo = pathinfo($path);
                    list($imgWidth, $imgHeight, $type, $attr) = getimagesize($path);
                    $width = ($options['width']) ?? null;
                    $height = ($options['height']) ?? null;
                    $newFilename = $pathInfo['filename'].'_'.$width.'_'.$height.'.'.$pathInfo['extension'];
                    $newFilePath  = $cachePath.'/'.$newFilename;
                    $cacheRelatif = '/cache/'.$relativeDirName.'/';
                    if ($itemId !== null) {
                        $cacheRelatif .= $itemId.'/';
                    }
                    $cacheRelatif .= $newFilename;
                    $existPath = Yii::getAlias('@webroot/'.$cacheRelatif);
                    if (file_exists($existPath) === false) {
                        static::resizeImage($path, $newFilePath, $width, $height);
                    }
                    $relative = $cacheRelatif;
                }
            }
            return $relative;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    protected static function prepareCacheDir($target = null)
    {
        try {
            $relativeDirNam = Module::getInstance()->relativeImgDirName;
            $cacheImgPath = '@webroot/'.Module::getInstance()->cacheImgPath;

            $cacheBasePath = Yii::getAlias($cacheImgPath);
            if (file_exists($cacheBasePath) === false) {
                //Create cache path
                mkdir($cacheBasePath);
            }

            $cachePath = Yii::getAlias($cacheImgPath.'/'.$relativeDirNam);
            if (file_exists($cachePath) === false) {
                //Create cache path
                mkdir($cachePath);
            }

            if ($target !== null) {
                $cachePath = Yii::getAlias($cacheImgPath.'/'.$relativeDirNam.'/'.$target);
                if (file_exists($cachePath) === false) {
                    //Create cache path
                    mkdir($cachePath);
                }
            }
            return $cachePath;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


    protected static function resizeImage($sourcePath, $destPath, $newWidth, $newHeight = null)
    {
        try {
            // Obtenir infos de l’image
            list($width, $height, $type) = getimagesize($sourcePath);
            $ratio = $width/$height;
            // Créer ressource source en fonction du type
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $src = imagecreatefromjpeg($sourcePath);
                    break;
                case IMAGETYPE_PNG:
                    $src = imagecreatefrompng($sourcePath);
                    break;
                case IMAGETYPE_GIF:
                    $src = imagecreatefromgif($sourcePath);
                    break;
                default:
                    throw new HttpException(404, 'Format d\'image non supporté');
            }
            if ($newHeight === null) {
                $newHeight = round($newWidth / $ratio);
            }
            if ($newWidth === null) {
                $newWidth = round($newHeight * $ratio);
            }

            // Créer une nouvelle image vide
            $dst = imagecreatetruecolor($newWidth, $newHeight);

            // Préserver transparence PNG/GIF
            if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
                imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }

            // Redimensionner avec interpolation
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            // Sauvegarder selon le format
            switch ($type) {
                case IMAGETYPE_JPEG:
                    imagejpeg($dst, $destPath, 90); // qualité 90%
                    break;
                case IMAGETYPE_PNG:
                    imagepng($dst, $destPath, 6);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($dst, $destPath);
                    break;
            }
            imagedestroy($src);
            imagedestroy($dst);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

}
