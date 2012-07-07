<?php

/**
 * PluginPage form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginPageFormFilter extends BasePageFormFilter
{

    public function configure()
    {
        $this->widgetSchema['menu_id'] = new sfWidgetFormDoctrineChoice(array(
                                                                             'model' => 'Menu',
                                                                             'table_method' => 'retrieveMenusForActiveSite',
                                                                             'order_by' => array('root_id, lft', ''),
                                                                             'method' => 'getIndentedName'
                                                                        ));
    }
}
