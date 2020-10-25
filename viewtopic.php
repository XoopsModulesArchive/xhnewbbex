<?php
// $Id: viewtopic.php,v 1.3 2006/03/28 03:58:44 mikhail Exp $
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
require_once XOOPS_ROOT_PATH . '/modules/xhnewbbex/class/keyhighlighter.class.php';
$forum = isset($_GET['forum']) ? (int)$_GET['forum'] : 0;
$topic_id = isset($_GET['topic_id']) ? (int)$_GET['topic_id'] : 0;
if (empty($forum)) {
    redirect_header('index.php', 2, _MDEX_ERRORFORUM);

    exit();
} elseif (empty($topic_id)) {
    redirect_header('viewforum.php?forum=' . $forum, 2, _MDEX_ERRORTOPIC);

    exit();
}
$topic_time = (isset($_GET['topic_time'])) ? (int)$_GET['topic_time'] : 0;
$post_id = !empty($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
//use users preferences
if (is_object($xoopsUser)) {
    $viewmode = $xoopsUser->getVar('umode');

    $order = (1 == $xoopsUser->getVar('uorder')) ? 'DESC' : 'ASC';
} else {
    $viewmode = 'flat';

    $order = 'ASC';
}
$myts = MyTextSanitizer::getInstance();
// xhnewbb does not have nested mode
if ('nest' == $viewmode) {
    $viewmode = 'thread';
}
// override mode/order if any requested
if (isset($_GET['viewmode']) && ('flat' == $_GET['viewmode'] || 'thread' == $_GET['viewmode'])) {
    $viewmode = $_GET['viewmode'];
}
if (isset($_GET['order']) && ('ASC' == $_GET['order'] || 'DESC' == $_GET['order'])) {
    $order = $_GET['order'];
}
if ('flat' != $viewmode) {
    $GLOBALS['xoopsOption']['template_main'] = 'xhnewbbex_viewtopic_thread.html';
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'xhnewbbex_viewtopic_flat.html';
}
require XOOPS_ROOT_PATH . '/header.php';
require_once __DIR__ . '/class/class.forumposts.php';
if (isset($_GET['move']) && 'next' == $_GET['move']) {
    $sql = 'SELECT t.topic_id, t.topic_title, t.topic_time, t.topic_status, t.topic_sticky, t.topic_last_post_id, f.forum_id, f.forum_name, f.forum_access, f.forum_type, f.allow_html, f.allow_sig, f.posts_per_page, f.hot_threshold, f.topics_per_page FROM ' . $xoopsDB->prefix('bbex_topics') . ' t LEFT JOIN ' . $xoopsDB->prefix('bbex_forums') . ' f ON f.forum_id = t.forum_id WHERE t.topic_time > ' . $topic_time . ' AND t.forum_id = ' . $forum . ' ORDER BY t.topic_time ASC LIMIT 1';
} elseif (isset($_GET['move']) && 'prev' == $_GET['move']) {
    $sql = 'SELECT t.topic_id, t.topic_title, t.topic_time, t.topic_status, t.topic_sticky, t.topic_last_post_id, f.forum_id, f.forum_name, f.forum_access, f.forum_type, f.allow_html, f.allow_sig, f.posts_per_page, f.hot_threshold, f.topics_per_page FROM ' . $xoopsDB->prefix('bbex_topics') . ' t LEFT JOIN ' . $xoopsDB->prefix('bbex_forums') . ' f ON f.forum_id = t.forum_id WHERE t.topic_time < ' . $topic_time . ' AND t.forum_id = ' . $forum . ' ORDER BY t.topic_time DESC LIMIT 1';
} else {
    $sql = 'SELECT t.topic_id, t.topic_title, t.topic_time, t.topic_status, t.topic_sticky, t.topic_last_post_id, f.forum_id, f.forum_name, f.forum_access, f.forum_type, f.allow_html, f.allow_sig, f.posts_per_page, f.hot_threshold, f.topics_per_page FROM ' . $xoopsDB->prefix('bbex_topics') . ' t LEFT JOIN ' . $xoopsDB->prefix('bbex_forums') . ' f ON f.forum_id = t.forum_id WHERE t.topic_id = ' . $topic_id . ' AND t.forum_id = ' . $forum;
}
if (!$result = $xoopsDB->query($sql)) {
    redirect_header('viewforum.php?forum=' . $forum, 2, _MDEX_ERROROCCURED);

    exit();
}
if (!$forumdata = $xoopsDB->fetchArray($result)) {
    redirect_header('viewforum.php?forum=' . $forum, 2, _MDEX_FORUMNOEXIST);

    exit();
}
$xoopsTpl->assign('topic_id', $forumdata['topic_id']);
$topic_id = $forumdata['topic_id'];
$xoopsTpl->assign('forum_id', $forumdata['forum_id']);
$forum = $forumdata['forum_id'];
$can_post = 0;
$show_reg = 0;
if (1 == $forumdata['forum_type']) {
    // this is a private forum.

    $accesserror = 0;

    if (is_object($xoopsUser)) {
        if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
            if (!check_priv_forum_auth($xoopsUser->getVar('uid'), $forum, false)) {
                $accesserror = 1;
            }
        } else {
            $isadminormod = 1;
        }
    } else {
        $accesserror = 1;
    }

    if (1 == $accesserror) {
        redirect_header('index.php', 2, _MDEX_NORIGHTTOACCESS);

        exit();
    }

    $can_post = 1;

    $show_reg = 1;
} else {
    // this is not a priv forum

    if (1 == $forumdata['forum_access']) {
        // this is a reg user only forum

        if (is_object($xoopsUser)) {
            $can_post = 1;
        } else {
            $show_reg = 1;
        }
    } elseif (2 == $forumdata['forum_access']) {
        // this is an open forum

        $can_post = 1;
    } else {
        // this is an admin/moderator only forum

        if (is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin($xoopsModule->mid()) || is_moderator($forum, $xoopsUser->getVar('uid'))) {
                $can_post = 1;

                $isadminormod = 1;
            }
        }
    }
}
$myts = MyTextSanitizer::getInstance();
$forumdata['topic_title'] = htmlspecialchars($forumdata['topic_title'], ENT_QUOTES | ENT_HTML5);
$forumdata['forum_name'] = htmlspecialchars($forumdata['forum_name'], ENT_QUOTES | ENT_HTML5);
$xoopsTpl->assign(['topic_title' => '<a href="' . $bbUrl['root'] . 'viewtopic.php?viewmode=' . $viewmode . '&amp;topic_id=' . $topic_id . '&amp;forum=' . $forum . '">' . $forumdata['topic_title'] . '</a>', 'forum_name' => $forumdata['forum_name'], 'topic_time' => $forumdata['topic_time'], 'lang_nexttopic' => _MDEX_NEXTTOPIC, 'lang_prevtopic' => _MDEX_PREVTOPIC]);
// add image links to admin page if the user viewing this page is a forum admin
if (is_object($xoopsUser)) {
    $xoopsTpl->assign('viewer_userid', $xoopsUser->getVar('uid'));

    if (!empty($isadminormod) || $xoopsUser->isAdmin($xoopsModule->mid()) || is_moderator($forum, $xoopsUser->getVar('uid'))) {
        // yup, the user is admin

        // the forum is locked?

        if (1 != $forumdata['topic_status']) {
            // nope

            $xoopsTpl->assign('topic_lock_image', '<a href="' . $bbUrl['root'] . 'topicmanager.php?mode=lock&amp;topic_id=' . $topic_id . '&amp;forum=' . $forum . '"><img src="' . $bbImage['locktopic'] . '" alt="' . _MDEX_LOCKTOPIC . '"></a>');
        } else {
            // yup, it is..

            $xoopsTpl->assign('topic_lock_image', '<a href="' . $bbUrl['root'] . 'topicmanager.php?mode=unlock&amp;topic_id=' . $topic_id . '&amp;forum=' . $forum . '"><img src="' . $bbImage['unlocktopic'] . '" alt="' . _MDEX_UNLOCKTOPIC . '"></a>');
        }

        $xoopsTpl->assign('topic_move_image', '<a href="' . $bbUrl['root'] . 'topicmanager.php?mode=move&amp;topic_id=' . $topic_id . '&amp;forum=' . $forum . '"><img src="' . $bbImage['movetopic'] . '" alt="' . _MDEX_MOVETOPIC . '"></a>');

        $xoopsTpl->assign('topic_delete_image', '<a href="' . $bbUrl['root'] . 'topicmanager.php?mode=del&amp;topic_id=' . $topic_id . '&amp;forum=' . $forum . '"><img src="' . $bbImage['deltopic'] . '" alt="' . _MDEX_DELETETOPIC . '"></a>');

        // is the topic sticky?

        if (1 != $forumdata['topic_sticky']) {
            // nope, not yet..

            $xoopsTpl->assign('topic_sticky_image', '<a href="' . $bbUrl['root'] . 'topicmanager.php?mode=sticky&amp;topic_id=' . $topic_id . '&amp;forum=' . $forum . '"><img src="' . $bbImage['sticky'] . '" alt="' . _MDEX_STICKYTOPIC . '"></a>');
        } else {
            // yup it is sticking..

            $xoopsTpl->assign('topic_sticky_image', '<a href="' . $bbUrl['root'] . 'topicmanager.php?mode=unsticky&amp;topic_id=' . $topic_id . '&amp;forum=' . $forum . '"><img src="' . $bbImage['unsticky'] . '" alt="' . _MDEX_UNSTICKYTOPIC . '"></a>');
        }

        // need to set this also

        $xoopsTpl->assign('viewer_is_admin', true);
    } else {
        // nope, the user is not a forum admin..

        $xoopsTpl->assign('viewer_is_admin', false);
    }
} else {
    // nope, the user is not a forum admin, not even registered

    $xoopsTpl->assign(['viewer_is_admin' => false, 'viewer_userid' => 0]);
}
function showTree(&$arr, $current = 0, $key = 0, $prefix = '', $foundusers = [])
{
    global $xoopsConfig;

    global $forum; // Ajout Hervé

    if (0 != $key) {
        if (0 != $arr[$key]['obj']->uid()) {
            if (!isset($foundusers[$arr[$key]['obj']->uid()])) {
                $eachposter = new XoopsUser($arr[$key]['obj']->uid());

                $foundusers[$arr[$key]['obj']->uid()] = &$eachposter;
            } else {
                $eachposter = &$foundusers[$arr[$key]['obj']->uid()];
            }

            $poster_rank = $eachposter->rank();

            if ('' != $poster_rank['image']) {
                $poster_rank['image'] = '<img src="' . XOOPS_UPLOAD_URL . '/' . $poster_rank['image'] . '" alt="">';
            }

            if ($eachposter->isActive()) {
                // Ajout Hervé

                $show_name_username = $eachposter->getVar('uname');

                if (get_show_name($forum)) {
                    if ('' != trim(username($eachposter->getVar('uid')))) {
                        $show_name_username = username($eachposter->getVar('uid'));
                    }
                }

                $posterarr = ['poster_uid' => $eachposter->getVar('uid'), 'poster_uname' => '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $eachposter->getVar('uid') . '">' . $eachposter->getVar('uname') . '</a>'];
            } else {
                $posterarr = ['poster_uid' => 0, 'poster_uname' => $xoopsConfig['anonymous']];
            }
        } else {
            $posterarr = ['poster_uid' => 0, 'poster_uname' => $xoopsConfig['anonymous']];
        }

        $posticon = $arr[$key]['obj']->icon();

        if (isset($posticon) && '' != $posticon) {
            $post_image = '<img src="' . XOOPS_URL . '/images/subject/' . htmlspecialchars($posticon, ENT_QUOTES) . '" alt="">';
        } else {
            $post_image = '<img src="' . XOOPS_URL . '/images/icons/no_posticon.gif" alt="">';
        }

        if ($current != $key) {
            $subject = '<a href="viewtopic.php?viewmode=thread&amp;topic_id=' . $arr[$key]['obj']->topic() . '&amp;forum=' . $arr[$key]['obj']->forum() . '&amp;post_id=' . $arr[$key]['obj']->postid() . '#' . $arr[$key]['obj']->postid() . '">' . $arr[$key]['obj']->subject() . '</a>';

            $GLOBALS['xoopsTpl']->append('topic_trees', array_merge($posterarr, ['post_id' => $arr[$key]['obj']->postid(), 'post_parent_id' => $arr[$key]['obj']->parent(), 'post_date' => formatTimestamp($arr[$key]['obj']->posttime(), 'm'), 'post_image' => $post_image, 'post_title' => $subject, 'post_prefix' => $prefix]));
        } else {
            $subject = '<b>' . $arr[$key]['obj']->subject() . '</b>';

            $thisprefix = mb_substr($prefix, 0, -6) . '<b>&raquo;</b>';

            $GLOBALS['xoopsTpl']->append('topic_trees', array_merge($posterarr, ['post_id' => $arr[$key]['obj']->postid(), 'post_parent_id' => $arr[$key]['obj']->parent(), 'post_date' => formatTimestamp($arr[$key]['obj']->posttime(), 'm'), 'post_image' => $post_image, 'post_title' => $subject, 'post_prefix' => $thisprefix]));
        }
    }

    if (isset($arr[$key]['replies']) && !empty($arr[$key]['replies'])) {
        $prefix .= '&nbsp;&nbsp;';

        foreach ($arr[$key]['replies'] as $replykey) {
            $current = (0 == $current) ? $replykey : $current;

            showTree($arr, $current, $replykey, $prefix, $foundusers);
        }
    }
}
if ('DESC' == $order) {
    $xoopsTpl->assign(['order_current' => 'DESC', 'order_other' => 'ASC', 'lang_order_other' => _OLDESTFIRST]);
} else {
    $xoopsTpl->assign(['order_current' => 'ASC', 'order_other' => 'DESC', 'lang_order_other' => _NEWESTFIRST]);
}
// initialize the start number of select query
$start = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$total_posts = get_total_postsex($topic_id, 'topic');
if ($total_posts > 50) {
    $viewmode = 'flat';

    // hide link to theaded view

    $xoopsTpl->assign('lang_threaded', '');

    $xoopsTpl->assign('lang_flat', _FLAT);
} else {
    $xoopsTpl->assign(['lang_threaded' => _THREADED, 'lang_flat' => _FLAT]);
}
if (1 == $can_post) {
    $xoopsTpl->assign(['viewer_can_post' => true, 'forum_post_or_register' => '<a href="newtopic.php?forum=' . $forum . '"><img src="' . $bbImage['post'] . '" alt="' . _MDEX_POSTNEW . '"></a>']);
} else {
    $xoopsTpl->assign('viewer_can_post', false);

    if (1 == $show_reg) {
        $xoopsTpl->assign('forum_post_or_register', '<a href="' . XOOPS_URL . '/user.php?xoops_redirect=' . htmlspecialchars($xoopsRequestUri, ENT_QUOTES | ENT_HTML5) . '">' . _MDEX_REGTOPOST . '</a>');
    } else {
        $xoopsTpl->assign('forum_post_or_register', '');
    }
}
// ***********************************************************************************************************************************************************
function my_highlighter($matches)
{
    $color = '#FFFF80';

    if ('#' != mb_substr($color, 0, 1)) {
        $color = '#' . $color;
    }

    return '<span style="font-weight: bolder; background-color: ' . $color . ';">' . $matches[0] . '</span>';
}
// ***********************************************************************************************************************************************************
$firsttitle = '';
$firstcontent = '';
if ('thread' == $viewmode) {
    $start = 0;

    $postsArray = ForumPosts::getAllPosts($topic_id, 'ASC', $total_posts, $start);

    $xoopsTpl->assign('topic_viewmode', 'thread');

    $newObjArr = [];

    foreach ($postsArray as $eachpost) {
        $key1 = $eachpost->postid();

        if ((!empty($post_id) && $post_id == $key1) || (empty($post_id) && 0 == $eachpost->parent())) {
            $post_text = $eachpost->text();

            if (0 != $eachpost->uid()) {
                $eachposter = new XoopsUser($eachpost->uid());

                $poster_rank = $eachposter->rank();

                if ('' != $poster_rank['image']) {
                    $poster_rank['image'] = "<img src='" . XOOPS_UPLOAD_URL . '/' . $poster_rank['image'] . "' alt=''>";
                }

                if ($eachposter->isActive()) {
                    $poster_status = $eachposter->isOnline() ? _MDEX_ONLINE : '';

                    // Ajout Hervé

                    $show_name_username = $eachposter->getVar('uname');

                    if (get_show_name($forum)) {
                        if ('' != trim(username($eachposter->getVar('uid')))) {
                            $show_name_username = username($eachposter->getVar('uid'));
                        }
                    }

                    $posterarr = ['poster_uid' => $eachposter->getVar('uid'), 'poster_uname' => '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $eachposter->getVar('uid') . '">' . $eachposter->getVar('uname') . '</a>', 'poster_avatar' => $eachposter->getVar('user_avatar'), 'poster_from' => $eachposter->getVar('user_from'), 'poster_regdate' => formatTimestamp($eachposter->getVar('user_regdate'), 's'), 'poster_postnum' => $eachposter->getVar('posts'), 'poster_sendpmtext' => sprintf(_SENDPMTO, $eachposter->getVar('uname')), 'poster_rank_title' => $poster_rank['title'], 'poster_rank_image' => $poster_rank['image'], 'poster_status' => $poster_status];

                    if (1 == $forumdata['allow_sig'] && 1 == $eachpost->attachsig() && 1 == $eachposter->attachsig()) {
                        $myts = MyTextSanitizer::getInstance();

                        $post_text .= '<p><br>----------------<br>' . $myts->displayTarea($eachposter->getVar('user_sig', 'N'), 0, 1, 1) . '</p>';
                    }
                } else {
                    $posterarr = ['poster_uid' => 0, 'poster_uname' => $xoopsConfig['anonymous'], 'poster_avatar' => '', 'poster_from' => '', 'poster_regdate' => '', 'poster_postnum' => '', 'poster_sendpmtext' => '', 'poster_rank_title' => '', 'poster_rank_image' => ''];
                }
            } else {
                $posterarr = ['poster_uid' => 0, 'poster_uname' => $xoopsConfig['anonymous'], 'poster_avatar' => '', 'poster_from' => '', 'poster_regdate' => '', 'poster_postnum' => '', 'poster_sendpmtext' => '', 'poster_rank_title' => '', 'poster_rank_image' => ''];
            }

            $posticon = $eachpost->icon();

            if (isset($posticon) && '' != $posticon) {
                $post_image = '<a name="' . $eachpost->postid() . '"><img src="' . XOOPS_URL . '/images/subject/' . htmlspecialchars($posticon, ENT_QUOTES) . '" alt=""></a>';
            } else {
                $post_image = '<a name="' . $eachpost->postid() . '"><img src="' . XOOPS_URL . '/images/icons/posticon.gif" alt=""></a>';
            }

            // ***********************************************************************************************************************************************************

            if (isset($_GET['keywords'])) {
                $keywords = htmlspecialchars(trim(urldecode($_GET['keywords'])), ENT_QUOTES | ENT_HTML5);

                $h = new keyhighlighter($keywords, true, 'my_highlighter');

                $post_text = $h->highlight($post_text);
            }

            // ***********************************************************************************************************************************************************

            $xoopsTpl->append('topic_posts', array_merge($posterarr, ['post_id' => $eachpost->postid(), 'post_parent_id' => $eachpost->parent(), 'post_date' => formatTimestamp($eachpost->posttime(), 'm'), 'post_poster_ip' => $eachpost->posterip(), 'post_image' => $post_image, 'post_title' => $eachpost->subject(), 'post_text' => $post_text]));

            if ('' == $firsttitle) {
                $firsttitle = $eachpost->subject();

                $firstcontent = $post_text;
            }
        }

        $newObjArr[$key1]['obj'] = $eachpost;

        $key2 = $eachpost->parent();

        $newObjArr[$key2]['replies'][] = $key1;

        $newObjArr[$key2]['leaf'] = $key1;
    }

    showTree($newObjArr, $post_id);

    $xoopsTpl->assign(['lang_subject' => _MDEX_SUBJECT, 'lang_date' => _MDEX_DATE]);
} else {
    $xoopsTpl->assign(['topic_viewmode' => 'flat', 'lang_top', _MDEX_TOP, 'lang_subject' => _MDEX_SUBJECT, 'lang_bottom' => _MDEX_BOTTOM]);

    $postsArray = ForumPosts::getAllPosts($topic_id, $order, $forumdata['posts_per_page'], $start, $post_id);

    $foundusers = [];

    foreach ($postsArray as $eachpost) {
        $post_text = $eachpost->text();

        if (0 != $eachpost->uid()) {
            if (!isset($foundusers['user' . $eachpost->uid()])) {
                $eachposter = new XoopsUser($eachpost->uid());

                $foundusers['user' . $eachpost->uid()] = &$eachposter;
            } else {
                $eachposter = &$foundusers['user' . $eachpost->uid()];
            }

            $poster_rank = $eachposter->rank();

            if ('' != $poster_rank['image']) {
                $poster_rank['image'] = '<img src="' . XOOPS_UPLOAD_URL . '/' . $poster_rank['image'] . '" alt="">';
            }

            if ($eachposter->isActive()) {
                $poster_status = $eachposter->isOnline() ? _MDEX_ONLINE : '';

                // Ajout Hervé

                $show_name_username = $eachposter->getVar('uname');

                if (get_show_name($forum)) {
                    if ('' != trim(username($eachposter->getVar('uid')))) {
                        $show_name_username = username($eachposter->getVar('uid'));
                    }
                }

                $posterarr = ['poster_uid' => $eachposter->getVar('uid'), 'poster_uname' => '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $eachposter->getVar('uid') . '">' . $eachposter->getVar('uname') . '</a>', 'poster_avatar' => $eachposter->getVar('user_avatar'), 'poster_from' => $eachposter->getVar('user_from'), 'poster_regdate' => formatTimestamp($eachposter->getVar('user_regdate'), 's'), 'poster_postnum' => $eachposter->getVar('posts'), 'poster_sendpmtext' => sprintf(_SENDPMTO, $eachposter->getVar('uname')), 'poster_rank_title' => $poster_rank['title'], 'poster_rank_image' => $poster_rank['image'], 'poster_status' => $poster_status];

                if (1 == $forumdata['allow_sig'] && 1 == $eachpost->attachsig() && 1 == $eachposter->attachsig()) {
                    $myts = MyTextSanitizer::getInstance();

                    $post_text .= '<p><br>----------------<br>' . $myts->displayTarea($eachposter->getVar('user_sig', 'N'), 0, 1, 1) . '</p>';
                }
            } else {
                $posterarr = ['poster_uid' => 0, 'poster_uname' => $xoopsConfig['anonymous'], 'poster_avatar' => '', 'poster_from' => '', 'poster_regdate' => '', 'poster_postnum' => '', 'poster_sendpmtext' => '', 'poster_rank_title' => '', 'poster_rank_image' => ''];
            }
        } else {
            $posterarr = ['poster_uid' => 0, 'poster_uname' => $xoopsConfig['anonymous'], 'poster_avatar' => '', 'poster_from' => '', 'poster_regdate' => '', 'poster_postnum' => '', 'poster_sendpmtext' => '', 'poster_rank_title' => '', 'poster_rank_image' => ''];
        }

        $posticon = $eachpost->icon();

        if (isset($posticon) && '' != $posticon) {
            $post_image = '<a name="' . $eachpost->postid() . '"><img src="' . XOOPS_URL . '/images/subject/' . $eachpost->icon() . '" alt=""></a>';
        } else {
            $post_image = '<a name="' . $eachpost->postid() . '"><img src="' . XOOPS_URL . '/images/icons/no_posticon.gif" alt=""></a>';
        }

        // ***********************************************************************************************************************************************************

        if (isset($_GET['keywords'])) {
            $keywords = htmlspecialchars(trim(urldecode($_GET['keywords'])), ENT_QUOTES | ENT_HTML5);

            $h = new keyhighlighter($keywords, true, 'my_highlighter');

            $post_text = $h->highlight($post_text);
        }

        // ***********************************************************************************************************************************************************

        $xoopsTpl->append('topic_posts', array_merge($posterarr, ['post_id' => $eachpost->postid(), 'post_parent_id' => $eachpost->parent(), 'post_date' => formatTimestamp($eachpost->posttime(), 'm'), 'post_poster_ip' => $eachpost->posterip(), 'post_image' => $post_image, 'post_title' => $eachpost->subject(), 'post_text' => $post_text]));

        if ('' == $firsttitle) {
            $firsttitle = $eachpost->subject();

            $firstcontent = $post_text;
        }

        unset($eachposter);
    }

    if ($total_posts > $forumdata['posts_per_page']) {
        require XOOPS_ROOT_PATH . '/class/pagenav.php';

        $nav = new XoopsPageNav($total_posts, $forumdata['posts_per_page'], $start, 'start', 'topic_id=' . $topic_id . '&amp;forum=' . $forum . '&amp;viewmode=' . $viewmode . '&amp;order=' . $order);

        $xoopsTpl->assign('forum_page_nav', $nav->renderNav(4));
    } else {
        $xoopsTpl->assign('forum_page_nav', '');
    }
}
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->name() . ' - ' . $myts->displayTarea($forumdata['forum_name']) . ' - ' . $myts->displayTarea($forumdata['topic_title']));
require XOOPS_ROOT_PATH . '/modules/xhnewbbex/include/functions.php';
$xoopsTpl->assign('xoops_meta_keywords', xhnewbbex_createmeta_keywords($myts->displayTarea($firstcontent)));
$xoopsTpl->assign('xoops_meta_description', $myts->displayTarea($firsttitle));
// create jump box
$xoopsTpl->assign(['forum_jumpbox' => make_jumpboxex($forum), 'lang_forum_index' => sprintf(_MDEX_FORUMINDEX, $xoopsConfig['sitename']), 'lang_from' => _MDEX_FROM, 'lang_joined' => _MDEX_JOINED, 'lang_posts' => _MDEX_POSTS, 'lang_poster' => _MDEX_POSTER, 'lang_thread' => _MDEX_THREAD, 'lang_edit' => _EDIT, 'lang_delete' => _DELETE, 'lang_reply' => _REPLY, 'lang_postedon' => _MDEX_POSTEDON]);
// Read in cookie of 'lastread' times
$topic_lastread = xhnewbb_get_topics_viewed();
// if cookie is not set for this topic, update view count and set cookie
if (empty($topic_lastread[$topic_id])) {
    $sql = 'UPDATE ' . $xoopsDB->prefix('bbex_topics') . ' SET topic_views = topic_views + 1 WHERE topic_id =' . $topic_id;

    $xoopsDB->queryF($sql);
}
// Update cookie
xhnewbb_add_topics_viewed($topic_lastread, $topic_id, time(), $bbCookie['path'], $bbCookie['domain'], $bbCookie['secure']);
require XOOPS_ROOT_PATH . '/footer.php';
