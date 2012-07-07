<?php if ($visuels): ?>
  <ul>
    <?php foreach ($visuels as $visuel): ?>
      <li>
        <img src="<?php echo $visuel ?>" alt="" />
      </li>
    <?php endforeach ?>
  </ul>
<?php endif; ?>
