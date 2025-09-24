<?php

namespace fractalCms\traits;

use Exception;
use fractalCms\models\ElasticModel;
use fractalCms\Module;
use Yii;
use yii\helpers\ArrayHelper;

trait Elastic
{
    public ?ElasticModel $elasticModel = null;



    public function __get($name)
    {
        try {
            if ($this->elasticModel instanceof  ElasticModel && $this->elasticModel->hasAttribute($name)) {
                return $this->elasticModel->__get($name);
            }
            return parent::__get($name);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


    public function __set($name, $value)
    {
        try {
            if ($this->elasticModel instanceof  ElasticModel && $this->elasticModel->hasAttribute($name)) {
                $this->elasticModel->__set($name, $value);
            } else {
                parent::__set($name, $value);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function setAttribute($attribute, $value)
    {
        try {
            if ($this->elasticModel instanceof  ElasticModel && $this->elasticModel->hasAttribute($attribute)) {
                $this->elasticModel->__set($attribute, $value);
            } else {
                parent::__set($attribute, $value);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function attributes()
    {
        try {
            $attributes = parent::attributes();
            $elasticAttributes = [];
            if ($this->elasticModel instanceof ElasticModel) {
                $elasticAttributes = array_keys($this->elasticModel->config);
            }
            return ArrayHelper::merge($attributes, $elasticAttributes);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function safeAttributes()
    {
        try {
            return $this->attributes();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function prepareData($data) : array
    {
        try {
            $dataFile = Module::getInstance()->filePath;
            $relativeDirNam = Module::getInstance()->relativeImgDirName;
            $destMainDir = Yii::getAlias($dataFile.'/'.$relativeDirNam);
            if (file_exists($destMainDir) === false) {
                mkdir($destMainDir);
            }

            if($this->elasticModel !== null && is_array($this->elasticModel->filesAttributes) === true) {
                foreach ($this->elasticModel->filesAttributes as $attribute => $options) {
                    if (key_exists($attribute, $data) === true && empty($data[$attribute]) === false) {
                        try {
                            $filePatch = Yii::getAlias($data[$attribute]);
                            if (file_exists($filePatch) === true) {
                                $info = pathinfo($filePatch);
                                $fileName = ($info['basename']) ?? null;
                                if ($fileName !== null) {
                                    $destDir = Yii::getAlias($dataFile.'/'.$relativeDirNam.'/'.$this->id);
                                    if (file_exists($destDir) === false) {
                                        mkdir($destDir);
                                    }
                                    $newPath = Yii::getAlias($dataFile.'/'.$relativeDirNam.'/'.$this->id.'/'.$fileName);
                                    $success = copy($filePatch, $newPath);
                                    if ($success === true) {
                                        unlink($filePatch);
                                        $data[$attribute] = $dataFile.'/'.$relativeDirNam.'/'.$this->id.'/'.$fileName;
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            Yii::error($e->getMessage(), __METHOD__);
                        }
                    }
                }
            }
            return $data;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function deletefilesDir() : void
    {
        try {
            $dataFile = Module::getInstance()->filePath;
            $relativeDirNam = Module::getInstance()->relativeImgDirName;
            $destMainDir = Yii::getAlias($dataFile.'/'.$relativeDirNam.'/'.$this->id);
            if (file_exists($destMainDir) === true) {
                foreach (scandir($destMainDir) as $value) {
                    $pathFile = Yii::getAlias($dataFile.'/'.$relativeDirNam.'/'.$this->id.'/'.$value);
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
