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

/**
 * Get a permalink candidate to create. We consider the album has no
 * permalink yet.
 *
 */
function pege_get_permalink_candidate($name, $active_permalinks, $old_permalinks)
{
  $name = trim($name);
  $name = str_replace(' ', ' ', $name);
  $name = str_replace('…', '...', $name);

  $permalink = str_replace('_', '-', str2url($name));
  $permalink = str_replace('’', '-', $permalink);
  $permalink = trim($permalink);

  if (preg_match('/^\d/', $permalink))
  {
    $prefix = strtolower(str2url(l10n('Album')));
    if (!preg_match('/^[a-zA-Z0-9_]+/', $prefix))
    {
      $prefix = 'album';
    }
    $permalink = $prefix.'-'.$permalink;
  }

  $base_permalink = $permalink;
  $i = 2;
  while (
    isset($active_permalinks[$permalink])
    or (isset($old_permalinks[$permalink]) and $old_permalinks[$permalink] != $category['id'])
    )
  {
    $permalink = $base_permalink.'-'.($i++);
  }

  return $permalink;
}
?>