<?php
// $Id: edit.php,v 1.3 2006/03/28 03:58:44 mikhail Exp $
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
require __DIR__ . '/header.php';
foreach (['forum', 'topic_id', 'post_id', 'order', 'pid'] as $getint) {
    ${$getint} = isset($_GET[$getint]) ? (int)$_GET[$getint] : 0;
}
$viewmode = (isset($_GET['viewmode']) && 'flat' != $_GET['viewmode']) ? 'thread' : 'flat';
if (empty($forum)) {
    redirect_header('index.php', 2, _MDEX_ERRORFORUM);

    exit();
} elseif (empty($post_id)) {
    redirect_header("viewforum.php?forum=$forum", 2, _MDEX_ERRORPOST);

    exit();
}  
    $sql = sprintf('SELECT forum_type, forum_name, forum_access, allow_html, allow_sig, posts_per_page, hot_threshold, topics_per_page FROM %s WHERE forum_id = %u', $xoopsDB->prefix('bbex_forums'), $forum);
    if (!$result = $xoopsDB->query($sql)) {
        redirect_header('index.php', 2, _MDEX_ERROROCCURED);

        exit();
    }
    $forumdata = $xoopsDB->fetchArray($result);
    $myts = MyTextSanitizer::getInstance();
    if (1 == $forumdata['forum_type']) {
        // To get here, we have a logged-in user. So, check whether that user is allowed to post in
        // this private forum.
        $accesserror = 0; //initialize
        if (is_object($xoopsUser)) {
            if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
                if (!check_priv_forum_auth($xoopsUser->uid(), $forum, true)) {
                    $accesserror = 1;
                }
            }
        } else {
            $accesserror = 1;
        }

        if (1 == $accesserror) {
            redirect_header("viewtopic.php?topic_id=$topic_id&post_id=$post_id&order=$order&viewmode=$viewmode&pid=$pid&forum=$forum", 2, _MDEX_NORIGHTTOPOST);

            exit();
        }
    } else {
        $accesserror = 0;

        if (3 == $forumdata['forum_access']) {
            if (is_object($xoopsUser)) {
                if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
                    if (!is_moderator($forum, $xoopsUser->uid())) {
                        $accesserror = 1;
                    }
                }
            } else {
                $accesserror = 1;
            }
        } elseif (1 == $forumdata['forum_access'] && !is_object($xoopsUser)) {
            $accesserror = 1;
        }

        if (1 == $accesserror) {
            redirect_header("viewtopic.php?topic_id=$topic_id&post_id=$post_id&order=$order&viewmode=$viewmode&pid=$pid&forum=$forum", 2, _MDEX_NORIGHTTOPOST);

            exit();
        }
    }
    require XOOPS_ROOT_PATH . '/header.php';
    require_once __DIR__ . '/class/class.forumposts.php';
    $forumpost = new ForumPosts($post_id);
    $editerror = false;
    if ($forumpost->islocked()) {
        if (is_object($xoopsUser)) {
            if (!$xoopsUser->isAdmin($xoopsModule->mid()) || !is_moderator($forum, $xoopsUser->uid())) {
                $editerror = true;
            }
        } else {
            $editerror = true;
        }
    }
    if ($editerror) {
        redirect_header("viewtopic.php?topic_id=$topic_id&post_id=$post_id&order=$order&viewmode=$viewmode&pid=$pid&forum=$forum", 2, _MDEX_NORIGHTTOPOST);

        exit();
    }
    $nohtml = $forumpost->nohtml();
    $nosmiley = $forumpost->nosmiley();
    $icon = $forumpost->icon();
    $attachsig = $forumpost->attachsig();
    $topic_id = $forumpost->topic();
    if ($forumpost->istopic()) {
        $istopic = 1;
    } else {
        $istopic = 0;
    }
    $subject = $forumpost->subject('Edit');
    $message = $forumpost->text('Edit');
    $hidden = '';
    $myts = MyTextSanitizer::getInstance();
    $viewmode = htmlspecialchars($viewmode, ENT_QUOTES | ENT_HTML5);
    require __DIR__ . '/include/forumform.inc.php';
    require XOOPS_ROOT_PATH . '/footer.php';

