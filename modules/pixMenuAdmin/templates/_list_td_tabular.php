<td class="sf_admin_boolean sf_admin_list_td_enabled">
  <?php echo get_partial('pixMenuAdmin/list_field_boolean', array('value' => $menu->getEnabled())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_label">
  <?php echo link_to($menu->getIndentedName(), 'pix_menu_admin_edit', $menu) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($menu->getUpdatedAt()) ? format_date($menu->getUpdatedAt(), "f") : '&nbsp;' ?>
</td>
