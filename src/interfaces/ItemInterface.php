<?php
/**
 * ItemInterface.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\interfaces
 */

namespace fractalCms\interfaces;

use fractalCms\models\ContentItem;
use fractalCms\models\Item;
use fractalCms\models\TagItem;

interface ItemInterface
{
    /**
     * Attach item
     *
     * @param Item $item
     * @return ContentItem|TagItem|null
     */
    public function attachItem(Item $item) : ContentItem | TagItem | null;

    /**
     * Detach item
     *
     * @param Item $item
     * @return int
     */
    public function detachItem(Item $item) : int;

    /**
     * Reorder item
     *
     * @return void
     */
    public function reOrderItems() : void;

    /**
     * Delete item
     *
     * @param Item $item
     * @return int
     */
    public function deleteItem(Item $item) : int;

    /**
     * Manage Item
     *
     * @param $deleteSource
     * @return void
     */
    public function manageItems($deleteSource = true) : void;

    /**
     * Get item with config
     *
     * @param int $configItemId
     * @return Item|null
     */
    public function getItemByConfigId(int $configItemId): Item | null;
}
