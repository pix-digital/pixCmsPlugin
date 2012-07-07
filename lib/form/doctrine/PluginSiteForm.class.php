<?php

/**
 * PluginSite form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginSiteForm extends BaseSiteForm
{
    public function configure()
    {
        unset($this['created_at'],
              $this['updated_at'],
              $this['slug']);

    }
}
