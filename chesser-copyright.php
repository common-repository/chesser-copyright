<?php
/*
Plugin Name: Chesser Copyright
Plugin URI: http://chesser.ru/blog/wordpress-chesser-copyright-plugin-development/
Description: Insert any text string to a post when user visit the post page. Requires mbstring php extension.
Author: Chesser
Version: 1.0
Author URI: http://chesser.ru/blog/
*/
/*  Copyright 2009  Chesser  (email: chesser@inbox.ru)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function chesser_copyright($content) {

  $offset = get_option('chesser_copyright_offset');
  $len = mb_strlen($content,"UTF-8");

  if ($len < $offset)
    return $content;
    
  if ($p_pos = mb_strpos(mb_strtolower($content, 'UTF-8'), '</p>', $offset, 'UTF-8')) {
    $p_pos += 4; // offset for </p> tag
    $content = mb_substr($content, 0, $p_pos, 'UTF-8').get_option('chesser_copyright_text').mb_substr($content, $p_pos, $len, 'UTF-8');
  }

  return $content;
}

function chesser_options_page() {
?>
<div class="wrap">
<h2>Chesser Copyright options</h2>
<?php
  if($_SERVER['REQUEST_METHOD'] == 'POST') {

    update_option('chesser_copyright_text', stripslashes($_POST['chesser']['copyright']['text']));
    update_option('chesser_copyright_offset', $_POST['chesser']['copyright']['offset']);

    echo '<div class="updated"><p>The changes have been saved.</p></div>';
  }
  $copyright['text']   = get_option('chesser_copyright_text');
  $copyright['offset'] = get_option('chesser_copyright_offset');
?>
<form method="post">
<table cellspacing="5">
  <tr>
    <td valign="top">Offset:</td>
    <td><input type="text" name="chesser[copyright][offset]" value="<?=$copyright['offset']?>" /></td>
  </tr>
  <tr>
    <td valign="top">Copyright text:</td>
    <td><textarea name="chesser[copyright][text]" rows="8" cols="70"><?=$copyright['text']?></textarea></td>
  </tr>
</table>
<p/>
<input type="submit" value="Save Changes" />
</form>
</div>
<?php
}

function chesser_add_menu() {
  add_options_page('Chesser', 'Chesser', 8, __FILE__, 'chesser_options_page');
}

add_filter('the_content', 'chesser_copyright');
add_action('admin_menu', 'chesser_add_menu');
?>
