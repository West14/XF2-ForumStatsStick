<?php
	
namespace West\ForumStatsStick;

class Listener 
{
	public static function templaterTemplatePreRender(\XF\Template\Templater $templater, &$type, &$template, array &$params) 
    {
        $templates = [
            'af_forumstats_new_posts',
            'af_forumstats_new_threads',
            'af_forumstats_hottest_threads',
            'af_forumstats_latest_forum_news',
            'af_forumstats_most_viewed_threads'
        ];

        if (in_array($template, $templates))
        {
            $params['wAfsStickedItems'] = \XF::finder('West\ForumStatsStick:StickedItem')
                ->where('active', 1)
                ->order('display_order')
                ->fetch();
        }
	}
}