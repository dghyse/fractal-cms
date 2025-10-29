<?php
/**
 * ControllerInterface.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\interfaces
 */

namespace fractalCms\interfaces;

use fractalCms\models\Content;
use fractalCms\models\Tag;

interface ControllerInterface
{
    /**
     * Get content
     *
     * @return Content|Tag|null
     */
    public function getTarget() : Content | Tag | null;
}
