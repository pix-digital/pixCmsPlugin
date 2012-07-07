<?php

/**
 *
 * @package    pixCmsRouting
 * @subpackage plugin
 * @author     Nicolas Ricci
 */
class pixCmsRouting
{
    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function addRouteForFrontend(sfEvent $event)
    {
        $r = $event->getSubject();

        // append / preprend our routes
        $r->appendRoute('pix_page', new sfRoute('/:sf_culture/:slug',
                                                                array('module' => 'pixPage', 'action' => 'show'),
                                                                array(),
                                                                array(
                                                                    'requirements' => array(
                                                                        'sf_culture' => implode('|',array_keys(sfConfig::get('app_pixPage_enabled_cultures')))
                                                                    )
                                                                )
                                                            ));
        $r->prependRoute('pix_switch_lang', new sfRoute('/switch-lang/:l', array('module' => 'pixPage', 'action' => 'switchLanguage')));

    }

    static public function addRouteForBackend(sfEvent $event)
    {
        $r = $event->getSubject();

        // preprend our routes

        $r->prependRoute('pix_page_admin', new sfDoctrineRouteCollection(array(
                                                                              'name' => 'pix_page_admin',
                                                                              'model' => 'Page',
                                                                              'module' => 'pixPageAdmin',
                                                                              'prefix_path' => 'admin/page',
                                                                              'with_wildcard_routes' => true,
                                                                              'collection_actions' => array('filter' => 'post', 'batch' => 'post'),
                                                                              'requirements' => array(),
                                                                         )));

        $r->prependRoute('pix_menu_admin', new sfDoctrineRouteCollection(array(
                                                                              'name' => 'pix_menu_admin',
                                                                              'model' => 'Menu',
                                                                              'module' => 'pixMenuAdmin',
                                                                              'prefix_path' => 'admin/menu',
                                                                              'with_wildcard_routes' => true,
                                                                              'collection_actions' => array('filter' => 'post', 'batch' => 'post'),
                                                                              'requirements' => array(),
                                                                         )));



    }


}