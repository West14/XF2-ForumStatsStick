<?php

namespace West\ForumStatsStick\Cron;

class StickedItem
{
	public static function disableExpiredStickedItems()
    {
        /** @var \West\ForumStatsStick\Repository\StickedItem $repo */
        $repo = \XF::repository('West\ForumStatsStick:StickedItem');

		/** @var \West\ForumStatsStick\Entity\StickedItem $item */
        foreach ($repo->findExpiredStickedItems()->fetch() as $item)
        {
            $item->fastUpdate('active', 0);
        }
	}
}