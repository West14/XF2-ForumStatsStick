<?php

namespace West\ForumStatsStick\Admin\Controller;

use XF\Mvc\ParameterBag;

class StickedItem extends \XF\Admin\Controller\AbstractController {
	public function actionIndex(ParameterBag $params) {
		$page = $this->filterPage();
		$stickedItemFinder = \XF::finder('West\ForumStatsStick:StickedItem');
		$total = $stickedItemFinder->total();
		$perPage = 300;
		$this->assertValidPage($page, $perPage, $total, 'forum-stats-stick');
		$stickedItemFinder->limitByPage($page, $perPage);
		$viewParams = [
			'stickedItems' => $stickedItemFinder->fetch(),
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total
		];
		return $this->view(null, "w_fss_list", $viewParams);
	}

	public function actionAdd() {
		$stickedItem = $this->em()->create('West\ForumStatsStick:StickedItem');
		return $this->stickedItemAddEdit($stickedItem);
	}

	public function actionEdit(ParameterBag $params) {
		$stickedItem = $this->assertStickedItemExists($params->sticked_item_id);
		return $this->stickedItemAddEdit($stickedItem);
	}

	public function actionToggle() 
	{ 
		/** @var \XF\ControllerPlugin\Toggle $plugin */ 
		$plugin = $this->plugin('XF:Toggle'); 
		return $plugin->actionToggle('West\ForumStatsStick:StickedItem'); 
	}

	public function actionDelete(ParameterBag $params) {
		$stickedItem = $this->assertStickedItemExists($params->sticked_item_id);
		if (!$stickedItem->preDelete())
		{
			return $this->error($stickedItem->getErrors());
		}

		if ($this->isPost())
		{
			$stickedItem->delete();
			return $this->redirect($this->buildLink('forum-stats-stick'));
		}
		else
		{
			$viewParams = [
				'stickedItem' => $stickedItem
			];
			return $this->view(null, 'w_fss_sticked_item_delete', $viewParams);
		}
	}

	protected function stickedItemAddEdit(\West\ForumStatsStick\Entity\StickedItem $stickedItem) {
		$viewParams = [
			'stickedItem' => $stickedItem
		];
		return $this->view(null, "w_fss_edit", $viewParams);
	}

	protected function assertStickedItemExists($id, $with = null, $phraseKey = null){
		return $this->assertRecordExists('West\ForumStatsStick:StickedItem', $id, $with, $phraseKey);
	}

	protected function stickedItemSaveProcess(\West\ForumStatsStick\Entity\StickedItem $stickedItem) {
		$form = $this->formAction();
		$input = $this->filter([
			'name' => 'str',
			'link' => 'str',
			'display_order' => 'uint',
			'end_date' => 'datetime',
			'active' => 'bool'
		]);
		$form->basicEntitySave($stickedItem, $input);

		return $form;
	}

	public function actionSave(ParameterBag $params) {
		$this->assertPostOnly();
		if ($params->sticked_item_id) {
			$stickedItem = $this->assertStickedItemExists($params->sticked_item_id);
		}
		else {
			$stickedItem = $this->em()->create('West\ForumStatsStick:StickedItem');
		}
		$this->stickedItemSaveProcess($stickedItem)->run();
		if ($this->request->exists('exit')) {
			$redirect = $this->buildLinkHash($stickedItem->sticked_item_id);
		}
		else {
			$redirect = $this->buildLink('forum-stats-stick');
		}

		if ($this->request->exists('exit'))
		{
			$redirect = $this->buildLink('forum-stats-stick') . $this->buildLinkHash($params->sticked_item_id);
		}
		else
		{
			$redirect = $this->buildLink('forum-stats-stick/edit', $stickedItem);
		}
		return $this->redirect($redirect, 'This is a redirect message.', 'temporary');
	}
}