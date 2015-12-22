<?php
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class JFormRuleRjuserdPath extends JFormRule
{
	protected $regex = '^[a-zA-Z0-9\./_-]*$';

	public function test (SimpleXMLElement $element, $value, $group = null, Registry $input = null, JForm $form = null)
	{
		$app = JFactory::getApplication();

		$newPath = trim($value/*, " /\t\n\r\0\x0B"*/);
	//	$input->set('params.datapath', $newPath);
		if (!preg_match('#'.$this->regex.'#', $newPath)) return false;

		$plugin = JPluginHelper::getPlugin('system', 'rjuserd');
		if ($plugin) {
			// Get plugin params
			$pluginParams = new JRegistry($plugin->params);
		}
		$oldPath = $pluginParams->get('datapath', false);

		//echo'<xmp>';var_dump($pluginParams,$element,$value,$group,$input,$form);jexit();
		if ($oldPath && ($oldPath != $newPath)) {
			jimport('joomla.filesystem.folder');
			if (JFolder::move(JPATH_ROOT.'/'.$oldPath, JPATH_ROOT.'/'.$newPath) !== true) {
				$app->enqueueMessage('Failed to move data from <u>'.$oldPath.'</u> to <u>'.$newPath.'</u>','error');
				return false;
			}
			$app->enqueueMessage('User data were moved from <u>'.$oldPath.'</u> to <u>'.$newPath.'</u>');
		}

		return true;
	}

}