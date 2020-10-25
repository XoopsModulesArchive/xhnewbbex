<?php
// $Id: notification.inc.php,v 1.3 2006/03/28 03:58:46 mikhail Exp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System //
// Copyright (c) 2000 xoopscube.org //
// <http://www.xoopscube.org> //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation; either version 2 of the License, or //
// (at your option) any later version.  //
//   //
// You may not change or alter any portion of this comment or credits //
// of supporting developers from this source code or any supporting //
// source code which is considered copyrighted (c) material of the //
// original comment or credit authors.  //
//   //
// This program is distributed in the hope that it will be useful, //
// but WITHOUT ANY WARRANTY; without even the implied warranty of //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the //
// GNU General Public License for more details. //
//   //
// You should have received a copy of the GNU General Public License //
// along with this program; if not, write to the Free Software //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA //
// ------------------------------------------------------------------------ //
function xhnewbbex_notify_iteminfo($category, $item_id)
{
    $moduleHandler = xoops_getHandler('module');

    $module = $moduleHandler->getByDirname('xhnewbbex');

    if ('global' == $category) {
        $item['name'] = '';

        $item['url'] = '';

        return $item;
    }

    global $xoopsDB;

    if ('forum' == $category) {
        // Assume we have a valid forum id

        $sql = 'SELECT forum_name FROM ' . $xoopsDB->prefix('bbex_forums') . ' WHERE forum_id = ' . $item_id;

        $result = $xoopsDB->query($sql); // TODO: error check

        $result_array = $xoopsDB->fetchArray($result);

        $item['name'] = $result_array['forum_name'];

        $item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/viewforum.php?forum=' . $item_id;

        return $item;
    }

    if ('thread' == $category) {
        // Assume we have a valid topid id

        $sql = 'SELECT t.topic_title,f.forum_id,f.forum_name FROM ' . $xoopsDB->prefix('bbex_topics') . ' t, ' . $xoopsDB->prefix('bbex_forums') . ' f WHERE t.forum_id = f.forum_id AND t.topic_id = ' . $item_id . ' limit 1';

        $result = $xoopsDB->query($sql); // TODO: error check

        $result_array = $xoopsDB->fetchArray($result);

        $item['name'] = $result_array['topic_title'];

        $item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/viewtopic.php?forum=' . $result_array['forum_id'] . '&topic_id=' . $item_id;

        return $item;
    }

    if ('post' == $category) {
        // Assume we have a valid post id

        $sql = 'SELECT subject,topic_id,forum_id FROM ' . $xoopsDB->prefix('bbex_posts') . ' WHERE post_id = ' . $item_id . ' LIMIT 1';

        $result = $xoopsDB->query($sql);

        $result_array = $xoopsDB->fetchArray($result);

        $item['name'] = $result_array['subject'];

        $item['url'] = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/viewtopic.php?forum= ' . $result_array['forum_id'] . '&amp;topic_id=' . $result_array['topic_id'] . '#forumpost' . $item_id;

        return $item;
    }
}
