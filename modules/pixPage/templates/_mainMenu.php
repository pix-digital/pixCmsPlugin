<div id="nav" class="nav">
    <nav>
        <ul>
            <?php foreach($menu_level1 as $menu): ?>
            <li id="nav-item-<?=$menu->class;?>" class="nav-item">
                <a href="<?php echo url_for($menu->getRouteFromSlug(ESC_RAW)); ?>" >
                	<span>
                		<?php echo $menu->getLabel(ESC_RAW); ?>
                	</span>
                </a>
                
                <?php if($submenus = $menu->getNode()->getChildren()):?>
                    <ul class="subnav">
                        <?php foreach($submenus as $submenu): ?>
                            <?php if($submenu->isEnabled()): ?>
                                <li>
                                  <a href="<?php echo url_for($submenu->getRouteFromSlug(ESC_RAW)); ?>" >
	                                  <span>
	                                    <?php echo $submenu->getLabel(ESC_RAW); ?>
	                                  </span>
                                  </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>