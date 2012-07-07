<?php

class pixCmsFilter extends sfFilter{

    public function execute ($filterChain)
	{
        $request = $this->getContext()->getRequest();

        // notify event
        if($request->getParameter('site')){
            $dispatcher = $this->getContext()->getEventDispatcher();
            $dispatcher->notify(new sfEvent(null, 'page.load_site', array('site' => $request->getParameter('site'))));
        }

        // Execute next filter in the chain
		$filterChain->execute();
    }
}
