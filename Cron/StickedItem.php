<?php

namespace West\ForumStatsStick\Cron;

class StickedItem {
	public static function disableExpiredStickedItems() {
		$finder = \XF::finder('West\ForumStatsStick:StickedItem')
					->where('end_date', '<', \XF::$time)
					->where('end_date', '>', 0);
		$stickedItems = $finder->fetch();
		foreach ($stickedItems AS $stickedItem) {
			$stickedItem->fastUpdate('active', 0);
		}
	}
}