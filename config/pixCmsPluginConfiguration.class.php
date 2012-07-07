<?php

/**
 * pixCmsPlugin configuration.
 * 
 * @package    pixCmsPlugin
 * @subpackage config
 * @author     Nicolas Ricci <nr@agencepix.com>
 */
class pixCmsPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (sfConfig::get('app_pixCms_routes_register', true))
    {
        $enabledModules = sfConfig::get('sf_enabled_modules', array());
        if (in_array('pixPage', $enabledModules))
        {
          $this->dispatcher->connect('routing.load_configuration', array('pixCmsRouting', 'addRouteForFrontend'));
        }

        if (in_array('pixPageAdmin', $enabledModules))
        {
          $this->dispatcher->connect('routing.load_configuration', array('pixCmsRouting', 'addRouteForBackend'));
        }
    }
  }
}
