<?php

/**
 * PluginPage form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginPageForm extends BasePageForm
{
    public function setup()
    {
        parent::setup();

        unset($this['created_at'],
              $this['updated_at']);


        $this->widgetSchema['menu_id'] = new sfWidgetFormDoctrineChoice(array(
	      	  'model' => 'Menu',
              'order_by' => array('root_id, lft', ''),
              'method' => 'getIndentedName'
		));

		$this->validatorSchema['menu_id'] = new sfValidatorDoctrineChoice(array(
      		'model' => 'Menu',
		));

        $this->embedI18n(array_keys(sfConfig::get('app_pixPage_enabled_cultures')));
    }
}
