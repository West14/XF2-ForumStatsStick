<?php
/**
 * Created by PhpStorm.
 * User: Andriy
 * Date: 11/15/2019
 * Time: 15:10
 * Made with <3 by West from TechGate Studio
 */

namespace West\ForumStatsStick\Repository;


use XF\Mvc\Entity\Repository;

class StickedItem extends Repository
{
    public function findStickedItemsForList()
    {
        return $this->finder('West\ForumStatsStick:StickedItem');
    }

    public function findExpiredStickedItems()
    {
        return $this->findStickedItemsForList()
            ->where([
                ['end_date', '<', \XF::$time],
                ['end_date', '!=', 0]
            ]);
    }
}