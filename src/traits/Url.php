<?php
/**
 * Url.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\traits
 */
namespace fractalCms\traits;

use Exception;
use Yii;

trait Url
{

    /**
     * Get route
     *
     * @param string|array $route
     * @return void
     * @throws Exception
     */
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
