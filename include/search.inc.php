<?php
// $Id: search.inc.php,v 1.3 2006/03/28 03:58:46 mikhail Exp $
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
// Author: Kazumi Ono (AKA onokazu)  //
// URL: http://www.myweb.ne.jp/, http://www.xoopscube.org/, http://jp.xoopscube.org/ //
// Project: The XOOPS Project  //
// ------------------------------------------------------------------------- //
require_once XOOPS_ROOT_PATH . '/modules/xhnewbbex/functions.php';
function xhnewbbex_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB, $xoopsUser, $xoopsModule;

    $searchparam = '';

    // Hack for viewing only authorized forums

    if (is_object($xoopsUser)) {
        $where = private_forums_list_cant_access($xoopsUser->getVar('uid'), 'f.');

        if (mb_strlen(trim($where)) > 0) {
            $where = ' WHERE (' . $where . ') ';
        } else {
            $where = ' WHERE 1=1 ';
        }
    } else { // Don't give any access to private forums for anonymous users
        $where = ' WHERE f.forum_type=0 ';
    }

    $sql = 'SELECT p.post_id,p.topic_id,p.forum_id,p.post_time,p.uid,p.subject FROM ' . $xoopsDB->prefix('bbex_posts') . ' p LEFT JOIN ' . $xoopsDB->prefix('bbex_posts_text') . ' t ON t.post_id=p.post_id LEFT JOIN ' . $xoopsDB->prefix('bbex_forums') . " f ON f.forum_id=p.forum_id $where";

    if (0 != $userid) {
        $sql .= ' AND (p.uid=' . $userid . ') ';
    }

    if (is_array($queryarray) && count($queryarray) > 0) {
        $searchparam = '&keywords=' . urlencode(trim(implode(' ', $queryarray)));
    }

    // because count() returns 1 even if a supplied variable

    // is not an array, we must check if $querryarray is really an array

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((p.subject LIKE '%$queryarray[0]%' OR t.post_text LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(p.subject LIKE '%$queryarray[$i]%' OR t.post_text LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= 'ORDER BY p.post_time DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $ret = [];

    $i = 0;

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['link'] = 'viewtopic.php?topic_id=' . $myrow['topic_id'] . '&amp;forum=' . $myrow['forum_id'] . $searchparam . '#forumpost' . $myrow['post_id'];

        $ret[$i]['title'] = $myrow['subject'];

        $ret[$i]['time'] = $myrow['post_time'];

        $ret[$i]['uid'] = $myrow['uid'];

        $i++;
    }

    return $ret;
}
