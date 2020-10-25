<?php
// $Id: forumform.inc.php,v 1.3 2006/03/28 03:58:46 mikhail Exp $
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
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';
echo "<form action='post.php' method='post' name='forumform' id='forumform' onsubmit='return xoopsValidate(\"subject\", \"message\", \"contents_submit\", \"" . htmlspecialchars(_PLZCOMPLETE, ENT_QUOTES) . '", "' . htmlspecialchars(_MESSAGETOOLONG, ENT_QUOTES) . '", "' . htmlspecialchars(
    _ALLOWEDCHAR,
    ENT_QUOTES
) . '", "' . htmlspecialchars(_CURRCHAR, ENT_QUOTES) . "\");'><table cellspacing='1' class='outer' width='100%'><tr><td class='head' width='25%' valign='top'>" . _MDEX_ABOUTPOST . ':</td>';
if (1 == $forumdata['forum_type']) {
    echo "<td class='even'>" . _MDEX_PRIVATE . '</td>';
} elseif (1 == $forumdata['forum_access']) {
    echo "<td class='even'>" . _MD_REGCANPOST . '</td>';
} elseif (2 == $forumdata['forum_access']) {
    echo "<td class='even'>" . _MDEX_ANONCANPOST . '</td>';
} elseif (3 == $forumdata['forum_access']) {
    echo "<td class='even'>" . _MDEX_MODSCANPOST . '</td>';
}
echo "</tr>
<tr>
<td class='head' valign='top' nowrap='nowrap'>" . _MDEX_SUBJECTC . "</td>
<td class='odd'>";
echo "<input type='text' id='subject' name='subject' size='60' maxlength='100' value='$subject'></td></tr>";
// Ajout Hervé
$panels_params = get_show_panels($forum);
if ('1' == mb_substr($panels_params, 0, 1)) {
    echo "<tr>
<td class='head' valign='top' nowrap='nowrap'>" . _MDEX_MESSAGEICON . "</td>
<td class='even'>
";

    $lists = new XoopsLists();

    $filelist = $lists::getSubjectsList();

    $count = 1;

    while (list($key, $file) = each($filelist)) {
        $checked = '';

        if (isset($icon) && $file == $icon) {
            $checked = ' checked';
        }

        echo "<input type='radio' value='$file' name='icon'$checked>&nbsp;";

        echo "<img src='" . XOOPS_URL . "/images/subject/$file' alt=''>&nbsp;";

        if (8 == $count) {
            echo '<br>';

            $count = 0;
        }

        $count++;
    }

    echo '</td></tr>';
}
echo "<tr align='left'>
<td class='head' valign='top' nowrap='nowrap'>" . _MDEX_MESSAGEC . "
</td>
<td class='odd'>";
xoopsCodeTarea('message');
if (!empty($isreply) && isset($hidden) && '' != $hidden) {
    echo "<input type='hidden' name='isreply' value='1'>";

    echo "<input type='hidden' name='hidden' id='hidden' value='$hidden'>
<input type='button' name='quote' class='formButton' value='" . _MDEX_QUOTE . "' onclick='xoopsGetElementById(\"message\").value=xoopsGetElementById(\"message\").value + xoopsGetElementById(\"hidden\").value; xoopsGetElementById(\"hidden\").value=\"\";'><br>";
}
// Ajout Hervé
if ('1' == mb_substr($panels_params, 1, 1)) {
    xoopsSmilies('message');
}
echo '</td></tr>
<tr>';
echo "<td class='head' valign='top' nowrap='nowrap'>" . _MDEX_OPTIONS . "</td>\n";
echo "<td class='even'>";
if (is_object($xoopsUser) && 2 == $forumdata['forum_access'] && !empty($post_id)) {
    echo "<input type='checkbox' name='noname' value='1'";

    if (isset($noname) && $noname) {
        echo ' checked';
    }

    echo '>&nbsp;' . _MDEX_POSTANONLY . "<br>\n";
}
echo "<input type='checkbox' name='nosmiley' value='1'";
if (isset($nosmiley) && $nosmiley) {
    echo ' checked';
}
echo '>&nbsp;' . _MDEX_DISABLESMILEY . "<br>\n";
if ($forumdata['allow_html']) {
    echo "<input type='checkbox' name='nohtml' value='1'";

    if ($nohtml) {
        echo ' checked';
    }

    echo '>&nbsp;' . _MDEX_DISABLEHTML . "<br>\n";
} else {
    echo "<input type='hidden' name='nohtml' value='1'>";
}
if ($forumdata['allow_sig'] && is_object($xoopsUser)) {
    echo "<input type='checkbox' name='attachsig' value='1'";

    if (isset($_POST['contents_preview'])) {
        if ($attachsig) {
            echo ' checked>&nbsp;';
        } else {
            echo '>&nbsp;';
        }
    } else {
        if ($xoopsUser->getVar('attachsig') || !empty($attachsig)) {
            echo ' checked>&nbsp;';
        } else {
            echo '>&nbsp;';
        }
    }

    echo _MDEX_ATTACHSIG . "<br>\n";
}
if (is_object($xoopsUser) && !empty($xoopsModuleConfig['notification_enabled'])) {
    echo "<input type='hidden' name='istopic' value='1'>";

    echo "<input type='checkbox' name='notify' value='1'";

    if (!empty($notify)) {
        // If 'notify' set, use that value (e.g. preview)

        echo ' checked';
    } else {
        // Otherwise, check previous subscribed status...

        $notificationHandler = xoops_getHandler('notification');

        if (!empty($topic_id) && $notificationHandler->isSubscribed('thread', $topic_id, 'new_post', $xoopsModule->getVar('mid'), $xoopsUser->getVar('uid'))) {
            echo ' checked';
        }
    }

    echo '>&nbsp;' . _MDEX_NEWPOSTNOTIFY . "<br>\n";
}
$post_id = isset($post_id) ? (int)$post_id : '';
$topic_id = isset($topic_id) ? (int)$topic_id : '';
$order = isset($order) ? (int)$order : '';
$pid = isset($pid) ? (int)$pid : 0;
echo "</td></tr>
<tr><td class='head'></td><td class='odd'>
<input type='hidden' name='pid' value='" . (int)$pid . "'>
<input type='hidden' name='post_id' value='" . $post_id . "'>
<input type='hidden' name='topic_id' value='" . $topic_id . "'>
<input type='hidden' name='forum' value='" . (int)$forum . "'>
<input type='hidden' name='viewmode' value='$viewmode'>
<input type='hidden' name='order' value='" . $order . "'>
<input type='submit' name='contents_preview' class='formButton' value='" . _PREVIEW . "'>&nbsp;<input type='submit' name='contents_submit' class='formButton' id='contents_submit' value='" . _SUBMIT . "'>
<input type='button' onclick='location=\"";
if (isset($topic_id) && '' != $topic_id) {
    echo 'viewtopic.php?topic_id=' . (int)$topic_id . '&amp;forum=' . (int)$forum . "\"'";
} else {
    echo 'viewforum.php?forum=' . (int)$forum . "\"'";
}
echo " class='formButton' value='" . _MDEX_CANCELPOST . "'>";
echo "</td></tr></table></form>\n";
