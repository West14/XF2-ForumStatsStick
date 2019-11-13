<?php
	
namespace West\ForumStatsStick;

class Listener 
{
	public static function templaterTemplatePreRender(\XF\Template\Templater $templater, &$type, &$template, array &$params) 
    {
		switch ($template) 
        {
			case 'af_forumstats_new_posts':
			case 'af_forumstats_new_threads':
			case 'af_forumstats_hottest_threads':
			case 'af_forumstats_latest_forum_news':
			case 'af_forumstats_most_viewed_threads':
                $stickedItemFinder = \XF::finder('West\ForumStatsStick:StickedItem');
                $stickedItems = $stickedItemFinder->where('active', 1)->order('display_order')->fetch();
            
				$params = array_merge($params, 
                [
					'stickedItems' => $stickedItems
				]);	
				break;
		}
	}
}