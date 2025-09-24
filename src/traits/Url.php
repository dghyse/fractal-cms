<?php

namespace fractalCms\traits;

use Exception;
use Yii;

trait Url
{

    public function getRoute(string | array $route)
    {
        try {
            if (is_string($route) === true) {
                $route = [$route];
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


}
