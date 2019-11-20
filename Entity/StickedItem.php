<?php

namespace West\ForumStatsStick\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int sticked_item_id
 * @property string name
 * @property string link
 * @property int display_order
 * @property int end_date
 * @property bool active
 */
class StickedItem extends Entity
{
	public static function getStructure(Structure $structure)
    {
		$structure->table = 'xf_fss_sticked_items';
		$structure->shortName = 'West\ForumStatsStick:StickedItem';
		$structure->primaryKey = 'sticked_item_id';
		$structure->columns = [
			'sticked_item_id' => ['type' => self::UINT, 'autoIncrement' => true],
			'name' => ['type' => self::STR, 'required' => true],
			'link' => ['type' => self::STR, 'required' => true],
			'display_order' => ['type' => self::UINT, 'default' => 0],
			'end_date' => ['type' => self::UINT, 'required' => true],
			'active' => ['type' => self::BOOL, 'default' => 1]
		];

		return $structure;
	}
}