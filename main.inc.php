<?php
/*
Plugin Name: Permalink Generator
Version: auto
Description: Automatic generation of your permalinks
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=
Author: plg
Author URI: http://piwigo.org
*/

if (!defined('PHPWG_ROOT_PATH'))
{
  die('Hacking attempt!');
}

define('PERMALINK_GENERATOR_PATH', PHPWG_PLUGINS_PATH.'permalink_generator/');

/* Plugin admin */
add_event_handler('get_admin_plugin_menu_links', 'pege_admin_menu');
function pege_admin_menu($menu)
{
  array_push(
    $menu,
    array(
      'NAME' => 'Permalink Generator',
      'URL'  => get_root_url().'admin.php?page=plugin-permalink_generator',
      )
    );

  return $menu;
}

add_event_handler('create_virtual_category', 'pege_create_virtual_category');
function pege_create_virtual_category($category)
{
  global $conf;

  if (!isset($conf['permalink_generator_autogen']) or !$conf['permalink_generator_autogen'])
  {
    return;
  }
  
  $query = '
SELECT
    id,
    permalink
  FROM '.CATEGORIES_TABLE.'
;';
  $active_permalinks = query2array($query, 'permalink', 'id');

  $query = '
SELECT
    cat_id,
    permalink
  FROM '.OLD_PERMALINKS_TABLE.'
;';
  $old_permalinks = query2array($query, 'permalink', 'cat_id');

  include_once(PHPWG_ROOT_PATH.'admin/include/functions_permalinks.php');
  include_once(PERMALINK_GENERATOR_PATH.'include/functions.inc.php');
  $permalink = pege_get_permalink_candidate($category['name'], $active_permalinks, $old_permalinks);

  set_cat_permalink($category['id'], $permalink, false);
}
?>
