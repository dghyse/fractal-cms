<?php

namespace fractalCms\traits;

use Exception;
use fractalCms\models\ElasticModel;
use fractalCms\Module;
use Yii;
use yii\helpers\ArrayHelper;

trait Elastic
{
    use Upload;
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

    public function prepareData($data, $deleteSource = true) : array
    {
        try {
            $dataFile = Module::getInstance()->filePath;
            $relativeDirName = Module::getInstance()->relativeItemImgDirName;
            if($this->elasticModel !== null && is_array($this->elasticModel->filesAttributes) === true) {
                foreach ($this->elasticModel->filesAttributes as $attribute => $options) {
                    if (key_exists($attribute, $data) === true && empty($data[$attribute]) === false) {
                        $data[$attribute] = $this->saveFile($dataFile, $relativeDirName, $data[$attribute], $deleteSource);
                    }
                }
            }
            return $data;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function deleteFilesDir() : void
    {
        try {
            $dataFile = Module::getInstance()->filePath;
            $relativeDirName = Module::getInstance()->relativeItemImgDirName;
            $this->deleteDir($dataFile, $relativeDirName);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
