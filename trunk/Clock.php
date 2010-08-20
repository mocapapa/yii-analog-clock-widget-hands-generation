<?php
  // @version $Id$

class Clock extends CPortlet
{
	public $title='Clock';

	public function init()
	{
		parent::init();
		Yii::app()->clientScript->registerCoreScript('jquery');
	}

	protected function renderContent()
	{
		$this->render('analog-clock');
	}
}
