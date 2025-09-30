<?php

namespace fractalCms\traits;

use Exception;
use fractalCms\models\ElasticModel;
use fractalCms\Module;
use Yii;
use yii\helpers\ArrayHelper;

trait Upload
{

    public function saveFile($dataFile, $relativeDirName, $value) : string
    {
        try {
            $result = $value;
            $destMainDir = Yii::getAlias($dataFile.'/'.$relativeDirName);
            if (file_exists($destMainDir) === false) {
                mkdir($destMainDir);
            }
            try {
                $filePatch = Yii::getAlias($value);
                if (file_exists($filePatch) === true && preg_match('/^(@webroot)(.+)$/', $value, $matches) !== 1) {
                    $info = pathinfo($filePatch);
                    $fileName = ($info['basename']) ?? null;
                    if ($fileName !== null) {
                        $destDir = Yii::getAlias($dataFile.'/'.$relativeDirName.'/'.$this->id);
                        if (file_exists($destDir) === false) {
                            mkdir($destDir);
                        }
                        $newPath = Yii::getAlias($dataFile.'/'.$relativeDirName.'/'.$this->id.'/'.$fileName);
                        $success = copy($filePatch, $newPath);
                        if ($success === true) {
                            unlink($filePatch);
                            $result = $dataFile.'/'.$relativeDirName.'/'.$this->id.'/'.$fileName;
                        }
                    }
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), __METHOD__);
            }
            return $result;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function deleteDir($dataFile, $relativeDirName) : void
    {
        try {
            $destMainDir = Yii::getAlias($dataFile.'/'.$relativeDirName.'/'.$this->id);
            if (file_exists($destMainDir) === true) {
                foreach (scandir($destMainDir) as $value) {
                    $pathFile = Yii::getAlias($dataFile.'/'.$relativeDirName.'/'.$this->id.'/'.$value);
                    if (in_array($value, ['.', '..']) === false && is_file($pathFile) === true) {
                        unlink($pathFile);
                    }
                }
                try {
                    rmdir($destMainDir);
                } catch (Exception $e) {
                    Yii::error($e->getMessage(), __METHOD__);
                }
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
