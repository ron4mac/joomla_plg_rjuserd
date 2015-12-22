<?php
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemRjuserd extends JPlugin
{

	public function onRjuserDatapath ()
	{
		return trim($this->params->get('datapath', 'userstor'), '/');
	}

	public function onUserAfterDelete ($user, $success, $msg)
	{
		if ($success) {
			$this->_delFolder('/@' . $user['id']);
		}
	}
	
	public function onUserAfterDeleteGroup ($group, $success, $msg)
	{
		if ($success) {
			$this->_delFolder('/_' . $group['id']);
		}
	}

	private function _delFolder ($fold)
	{
		if ($this->params->get('usrdeldata', '0') == '1') {
			jimport('joomla.filesystem.folder');
			$udf = JPATH_SITE .'/'. $this->onRjuserDatapath() . $fold;
			if (JFolder::exists($udf)) JFolder::delete($udf);
		}
	}

}