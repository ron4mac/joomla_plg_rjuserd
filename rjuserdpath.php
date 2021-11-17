<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormRule;

class JFormRuleRjuserdPath extends FormRule
{

	public function test (SimpleXMLElement $element, $value, $group = null, Registry $input = null, JForm $form = null)
	{
		$app = Factory::getApplication();

		$newPath = trim($value);
		if (!preg_match('#^[a-zA-Z0-9\./_-]*$#', $newPath)) {
			$app->enqueueMessage(Text::_('PLG_SYSTEM_RJUSERD_BAD_MSG'));
			return false;
		}

		$plugin = JPluginHelper::getPlugin('system', 'rjuserd');
		if ($plugin) {
			// Get plugin params
			$pluginParams = new Registry($plugin->params);
		}
		$oldPath = $pluginParams->get('datapath', false);

		if ($oldPath && ($oldPath != $newPath)) {
			if (Folder::move(JPATH_ROOT.'/'.$oldPath, JPATH_ROOT.'/'.$newPath) !== true) {
				$app->enqueueMessage(Text::sprintf('PLG_SYSTEM_RJUSERD_FAILED_MSG', $oldPath, $newPath), 'error');
				return false;
			}
			$app->enqueueMessage(Text::sprintf('PLG_SYSTEM_RJUSERD_MOVED_MSG', $oldPath, $newPath));
		}

		return true;
	}

}