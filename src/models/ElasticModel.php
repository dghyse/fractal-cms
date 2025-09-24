<?php
/**
 * ElasticModel.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <david.ghysefree.fr>
 * @version XXX
 * @package app\config
 */
namespace fractalCms\models;

use fractalCms\traits\Elastic;
use yii\base\Model;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ElasticModel extends Model
{
    private $_elasticData = [];
    public $config = [];
    public $elasticRules = [];
    public $filesAttributes = [];


    public function __construct($jsonConfig = [], $config = [])
    {
        parent::__construct($config);
        $this->config = $jsonConfig;
        $this->setElasticModel();
    }

    public function hasAttribute($name)
    {
        try {
            return in_array($name, array_keys($this->_elasticData));
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function __get($name)
    {
        try {
            $value = null;
            if ($this->hasAttribute($name)) {
                $value =  $this->_elasticData[$name];
            }
            return $value;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


    public function __set($name, $value)
    {
        try {
            if ($this->hasAttribute($name)) {
                $this->_elasticData[$name] = $value;
            } else {
                parent::__set($name, $value);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
    public function setAttributes($values, $safeOnly = true)
    {
        try {
            if (is_array($values) === true) {
                if (is_array($this->_elasticData) === false) {
                    $this->_elasticData = [];
                }
                $this->_elasticData = ArrayHelper::merge($this->_elasticData, $values);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function toJson()
    {
        try {
            return Json::encode($this->_elasticData);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    private function setElasticModel() : void
    {
        try {
            $config = $this->config;
            if (is_array($config) === true) {
                foreach ($config as $attribute => $options) {
                    $value = null;
                    $type = ($options['type']) ?? null;
                    if (isset($this->data[$attribute]) === true) {
                        $value = $this->data[$attribute];
                    }
                    $this->_elasticData[$attribute] = $value;
                    if (isset($options['rules']) === true) {
                        foreach ($options['rules'] as $rule) {
                            $message = ($rule['message']) ?? 'Erreur sur cet attribut';
                            $this->elasticRules[] = [[$attribute], $rule['name'], 'message' => $message];
                        }
                    }
                    if ($type === 'file') {
                        $this->filesAttributes[$attribute] = $options;
                    }
                }
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

}
