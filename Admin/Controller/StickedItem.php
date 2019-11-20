<?php

namespace West\ForumStatsStick\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class StickedItem extends AbstractController
{
    /**
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex()
    {
        $page = $this->filterPage();
        $perPage = 25;

        $finder = $this->getStickedItemRepo()->findStickedItemsForList();
        $finder->limitByPage($page, $perPage);

        $total = $finder->total();
        $this->assertValidPage($page, $perPage, $total, 'w-afs-stick');

        return $this->view('West\ForumStatsStick:StickedItem\List', 'w_fss_sticked_item_list', [
            'items' => $finder->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $total
        ]);
	}


	public function actionAdd()
    {
        /** @var \West\ForumStatsStick\Entity\StickedItem $item */
		$item = $this->em()->create('West\ForumStatsStick:StickedItem');
		return $this->stickedItemAddEdit($item);
	}

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionEdit(ParameterBag $params)
    {
		$item = $this->assertStickedItemExists($params->sticked_item_id);
		return $this->stickedItemAddEdit($item);
	}

	public function actionToggle() 
	{ 
		/** @var \XF\ControllerPlugin\Toggle $plugin */ 
		$plugin = $this->plugin('XF:Toggle'); 
		return $plugin->actionToggle('West\ForumStatsStick:StickedItem'); 
	}

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionDelete(ParameterBag $params)
    {
        $item = $this->assertStickedItemExists($params->sticked_item_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');

        return $plugin->actionDelete(
            $item,
            $this->buildLink('w-afs-stick/delete', $item),
            $this->buildLink('w-afs-stick/edit', $item),
            $this->buildLink('w-afs-stick'),
            $item->name
        );
	}

	protected function stickedItemAddEdit(\West\ForumStatsStick\Entity\StickedItem $item)
    {
		return $this->view('West\ForumStatsStick:StickedItem\Edit', 'w_fss_sticked_item_edit', [
		    'item' => $item
        ]);
	}

    /**
     * @param $id
     * @param null $with
     * @param null $phraseKey
     * @return \XF\Mvc\Entity\Entity|\West\ForumStatsStick\Entity\StickedItem
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertStickedItemExists($id, $with = null, $phraseKey = null)
    {
		return $this->assertRecordExists('West\ForumStatsStick:StickedItem', $id, $with, $phraseKey);
	}

	protected function stickedItemSaveProcess(\West\ForumStatsStick\Entity\StickedItem $item)
    {
		return $this->formAction()
            ->basicEntitySave($item, $this->filter([
                'name' => 'str',
                'link' => 'str',
                'display_order' => 'uint',
                'end_date' => 'datetime',
                'active' => 'bool'
            ]));
	}

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect
     * @throws \XF\Mvc\Reply\Exception
     * @throws \XF\PrintableException
     */
    public function actionSave(ParameterBag $params)
    {
		$this->assertPostOnly();

		if ($params->sticked_item_id)
		{
			$item = $this->assertStickedItemExists($params->sticked_item_id);
		}
		else
        {
			$item = $this->em()->create('West\ForumStatsStick:StickedItem');
		}

		$this->stickedItemSaveProcess($item)->run();

		return $this->redirect($this->buildLink('w-afs-stick'));
	}

    /**
     * @return \XF\Mvc\Entity\Repository|\West\ForumStatsStick\Repository\StickedItem
     */
    protected function getStickedItemRepo()
    {
        return $this->repository('West\ForumStatsStick:StickedItem');
	}
}