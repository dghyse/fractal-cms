<?php
/**
 * ControllerInterface.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\interfaces
 */

namespace fractalCms\interfaces;

use fractalCms\models\Content;

interface ControllerInterface
{
    /**
     * Get content
     *
     * @return Content|null
     */
    public function getContent() : Content | null;
}
