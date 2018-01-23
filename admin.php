<?php
// +-----------------------------------------------------------------------+
// | Piwigo - a PHP based photo gallery                                    |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2008-2017 Piwigo Team                  http://piwigo.org |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation                                          |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+

if( !defined("PHPWG_ROOT_PATH") )
{
  die ("Hacking attempt!");
}

include_once(PHPWG_ROOT_PATH.'admin/include/functions_permalinks.php');
include_once(PERMALINK_GENERATOR_PATH.'include/functions.inc.php');

// +-----------------------------------------------------------------------+
// | Check Access and exit when user status is not ok                      |
// +-----------------------------------------------------------------------+

check_status(ACCESS_WEBMASTER);

// +-----------------------------------------------------------------------+
// | Data preparation                                                      |
// +-----------------------------------------------------------------------+

$query = '
SELECT
    id,
    name,
    permalink
  FROM '.CATEGORIES_TABLE.'
;';
$categories = query2array($query);

$missing_permalinks = array();
foreach ($categories as $category)
{
  if (empty($category['permalink']))
  {
    $missing_permalinks[ $category['id'] ] = $category;
  }
  else
  {
    $active_permalinks[ $category['permalink'] ] = $category['id'];
  }
}

// load old_permalinks
$query = '
SELECT
    cat_id,
    permalink
  FROM '.OLD_PERMALINKS_TABLE.'
;';
$old_permalinks = query2array($query, 'permalink', 'cat_id');

// +-----------------------------------------------------------------------+
// |                            add permissions                            |
// +-----------------------------------------------------------------------+

if (isset($_POST['submit']))
{
  $nb_creation = 0;

  foreach ($missing_permalinks as $category)
  {
    $permalink = pege_get_permalink_candidate($category['name'], $active_permalinks, $old_permalinks);

    if (set_cat_permalink($category['id'], $permalink, false))
    {
      $active_permalinks[$permalink] = $category['id'];
      $nb_creation++;
      unset($missing_permalinks[ $category['id'] ]);
    }
  }

  if ($nb_creation > 0)
  {
    $page['infos'][] = sprintf('%d permalinks created', $nb_creation);
  }
}

// +-----------------------------------------------------------------------+
// |                             template init                             |
// +-----------------------------------------------------------------------+

$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__).'/admin.tpl'
    )
  );

$template->assign(
  array(
    'NB_MISSING' => count($missing_permalinks),
    'NB_PERMALINKS' => count($categories) - count($missing_permalinks),
    )
  );

// +-----------------------------------------------------------------------+
// |                           sending html code                           |
// +-----------------------------------------------------------------------+

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>
