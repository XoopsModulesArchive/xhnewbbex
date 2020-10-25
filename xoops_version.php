<?php
// $Id: xoops_version.php,v 1.4 2006/03/28 03:58:44 mikhail Exp $
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
$modversion['name'] = _MI_NEWBBEX_NAME;
$modversion['version'] = 1.25;
$modversion['description'] = _MI_NEWBBEX_DESC;
$modversion['credits'] = 'Kazumi Ono<br>( http://www.myweb.ne.jp/ )';
$modversion['author'] = "<A HREF='http://www.herve-thouzard.com'>Hervé Thouzard</A><BR>Based on Xoops forum made by Kazumi Ono.<BR>Original admin section (phpBB 1.4.4) by<br>The phpBB Group<br>( http://www.phpbb.com/ )<br>";
$modversion['help'] = 'xhnewbb.html';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 1;
$modversion['image'] = 'images/xoopsbb_slogo.png';
$modversion['dirname'] = 'xhnewbbex';
// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";
// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'bbex_categories';
$modversion['tables'][1] = 'bbex_forum_access';
$modversion['tables'][2] = 'bbex_forum_mods';
$modversion['tables'][3] = 'bbex_forums';
$modversion['tables'][4] = 'bbex_posts';
$modversion['tables'][5] = 'bbex_posts_text';
$modversion['tables'][6] = 'bbex_topics';
// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';
// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MI_NEWBBEX_SMNAME1;
$modversion['sub'][1]['url'] = 'search.php';
// Templates
$modversion['templates'][1]['file'] = 'xhnewbbex_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'xhnewbbex_search.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'xhnewbbex_searchresults.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'xhnewbbex_thread.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'xhnewbbex_viewforum.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'xhnewbbex_viewtopic_flat.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'xhnewbbex_viewtopic_thread.html';
$modversion['templates'][7]['description'] = '';
// Blocks
$modversion['blocks'][1]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][1]['name'] = _MI_NEWBBEX_BNAME1;
$modversion['blocks'][1]['description'] = 'Shows recent topics in the forums';
$modversion['blocks'][1]['show_func'] = 'b_xhnewbbex_new_show';
$modversion['blocks'][1]['options'] = '10|1|time|1'; // Modif Hervé
$modversion['blocks'][1]['edit_func'] = 'b_xhnewbbex_new_edit';
$modversion['blocks'][1]['template'] = 'xhnewbbex_block_new.html';
$modversion['blocks'][2]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][2]['name'] = _MI_NEWBBEX_BNAME2;
$modversion['blocks'][2]['description'] = 'Shows most viewed topics in the forums';
$modversion['blocks'][2]['show_func'] = 'b_xhnewbbex_new_show';
$modversion['blocks'][2]['options'] = '10|1|views|1'; // Modif Hervé
$modversion['blocks'][2]['edit_func'] = 'b_xhnewbbex_new_edit';
$modversion['blocks'][2]['template'] = 'xhnewbbex_block_top.html';
$modversion['blocks'][3]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][3]['name'] = _MI_NEWBBEX_BNAME3;
$modversion['blocks'][3]['description'] = 'Shows most active topics in the forums';
$modversion['blocks'][3]['show_func'] = 'b_xhnewbbex_new_show';
$modversion['blocks'][3]['options'] = '10|1|replies|1'; // Modif Hervé
$modversion['blocks'][3]['edit_func'] = 'b_xhnewbbex_new_edit';
$modversion['blocks'][3]['template'] = 'xhnewbbex_block_active.html';
$modversion['blocks'][4]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][4]['name'] = _MI_NEWBBEX_BNAME4;
$modversion['blocks'][4]['description'] = 'Shows recent and private topics in the forums';
$modversion['blocks'][4]['show_func'] = 'b_xhnewbbex_new_private_show';
$modversion['blocks'][4]['options'] = '10|1|time|1'; // Modif Hervé
$modversion['blocks'][4]['edit_func'] = 'b_xhnewbbex_new_edit';
$modversion['blocks'][4]['template'] = 'xhnewbbex_block_prv.html';
// Ajout Hervé
$modversion['blocks'][5]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][5]['name'] = _MI_NEWBBEX_BNAME5;
$modversion['blocks'][5]['description'] = 'Shows public topics without answer';
$modversion['blocks'][5]['show_func'] = 'b_xhnewbbex_new_show';
$modversion['blocks'][5]['options'] = '10|1|withoutreplies|1'; // Modif Hervé
$modversion['blocks'][5]['edit_func'] = 'b_xhnewbbex_new_edit';
$modversion['blocks'][5]['template'] = 'xhnewbbex_block_noanswer.html';
// Ajout Hervé
$modversion['blocks'][6]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][6]['name'] = _MI_NEWBBEX_BNAME6;
$modversion['blocks'][6]['description'] = 'Shows private topics without answer';
$modversion['blocks'][6]['show_func'] = 'b_xhnewbbex_new_private_show';
$modversion['blocks'][6]['options'] = '10|1|withoutreplies|1'; // Modif Hervé
$modversion['blocks'][6]['edit_func'] = 'b_xhnewbbex_new_edit';
$modversion['blocks'][6]['template'] = 'xhnewbbex_block_prv_noanswer.html';
// Ajout Hervé
$modversion['blocks'][7]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][7]['name'] = _MI_NEWBBEX_BNAME7;
$modversion['blocks'][7]['description'] = 'Shows public and private topics without answer';
$modversion['blocks'][7]['show_func'] = 'b_xhnewbbex_new_all_show';
$modversion['blocks'][7]['options'] = '10|1|withoutreplies|1'; // Modif Hervé
$modversion['blocks'][7]['edit_func'] = 'b_xhnewbbex_new_edit';
$modversion['blocks'][7]['template'] = 'xhnewbbex_block_all_noanswer.html';
// Ajout Hervé
$modversion['blocks'][8]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][8]['name'] = _MI_NEWBBEX_BNAME9;
$modversion['blocks'][8]['description'] = 'Recent private and public topics';
$modversion['blocks'][8]['show_func'] = 'b_xhnewbbex_new_show';
$modversion['blocks'][8]['options'] = '10|1|showall|1'; // Modif Hervé
$modversion['blocks'][8]['edit_func'] = 'b_xhnewbbex_new_edit';
$modversion['blocks'][8]['template'] = 'xhnewbbex_block_new_all.html';
// Ajout Hervé
$modversion['blocks'][9]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][9]['name'] = _MI_NEWBBEX_BNAME8;
$modversion['blocks'][9]['description'] = 'Shows forums statistics';
$modversion['blocks'][9]['show_func'] = 'b_xhnewbbex_show_forums_stat';
$modversion['blocks'][9]['options'] = '10|1|showall|1';
$modversion['blocks'][9]['edit_func'] = '';
$modversion['blocks'][9]['template'] = 'xhnewbbex_block_forum_stat.html';
// Ajout Hervé
$modversion['blocks'][10]['file'] = 'xhnewbbex_new.php';
$modversion['blocks'][10]['name'] = _MI_NEWBBEX_BNAME10;
$modversion['blocks'][10]['description'] = "Shows monthly's statistics";
$modversion['blocks'][10]['show_func'] = 'b_xhnewbbex_show_monthly_forums_stat';
$modversion['blocks'][10]['options'] = '';
$modversion['blocks'][10]['edit_func'] = '';
$modversion['blocks'][10]['template'] = 'xhnewbbex_block_monthly_forum_stat.html';
// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'xhnewbbex_search';
// Smarty
$modversion['use_smarty'] = 1;
$modversion['config'][0]['name'] = 'confidentiality';
$modversion['config'][0]['title'] = '_MI_NEWBBEX_SHOWMSG';
$modversion['config'][0]['description'] = '_MI_NEWBBEX_SHOWMSGDESC';
$modversion['config'][0]['formtype'] = 'yesno';
$modversion['config'][0]['valuetype'] = 'int';
$modversion['config'][0]['default'] = 0;
// Notification
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'xhnewbbex_notify_iteminfo';
$modversion['notification']['category'][1]['name'] = 'thread';
$modversion['notification']['category'][1]['title'] = _MI_NEWBBEX_THREAD_NOTIFY;
$modversion['notification']['category'][1]['description'] = _MI_NEWBBEX_THREAD_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = 'viewtopic.php';
$modversion['notification']['category'][1]['item_name'] = 'topic_id';
$modversion['notification']['category'][1]['allow_bookmark'] = 1;
$modversion['notification']['category'][2]['name'] = 'forum';
$modversion['notification']['category'][2]['title'] = _MI_NEWBBEX_FORUM_NOTIFY;
$modversion['notification']['category'][2]['description'] = _MI_NEWBBEX_FORUM_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = ['viewtopic.php', 'viewforum.php'];
$modversion['notification']['category'][2]['item_name'] = 'forum';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;
$modversion['notification']['category'][3]['name'] = 'global';
$modversion['notification']['category'][3]['title'] = _MI_NEWBBEX_GLOBAL_NOTIFY;
$modversion['notification']['category'][3]['description'] = _MI_NEWBBEX_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = ['index.php', 'viewtopic.php', 'viewforum.php'];
$modversion['notification']['event'][1]['name'] = 'new_post';
$modversion['notification']['event'][1]['category'] = 'thread';
$modversion['notification']['event'][1]['title'] = _MI_NEWBBEX_THREAD_NEWPOST_NOTIFY;
$modversion['notification']['event'][1]['caption'] = _MI_NEWBBEX_THREAD_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][1]['description'] = _MI_NEWBBEX_THREAD_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'thread_newpost_notify';
$modversion['notification']['event'][1]['mail_subject'] = _MI_NEWBBEX_THREAD_NEWPOST_NOTIFYSBJ;
$modversion['notification']['event'][2]['name'] = 'new_thread';
$modversion['notification']['event'][2]['category'] = 'forum';
$modversion['notification']['event'][2]['title'] = _MI_NEWBBEX_FORUM_NEWTHREAD_NOTIFY;
$modversion['notification']['event'][2]['caption'] = _MI_NEWBBEX_FORUM_NEWTHREAD_NOTIFYCAP;
$modversion['notification']['event'][2]['description'] = _MI_NEWBBEX_FORUM_NEWTHREAD_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'forum_newthread_notify';
$modversion['notification']['event'][2]['mail_subject'] = _MI_NEWBBEX_FORUM_NEWTHREAD_NOTIFYSBJ;
$modversion['notification']['event'][3]['name'] = 'new_forum';
$modversion['notification']['event'][3]['category'] = 'global';
$modversion['notification']['event'][3]['title'] = _MI_NEWBBEX_GLOBAL_NEWFORUM_NOTIFY;
$modversion['notification']['event'][3]['caption'] = _MI_NEWBBEX_GLOBAL_NEWFORUM_NOTIFYCAP;
$modversion['notification']['event'][3]['description'] = _MI_NEWBBEX_GLOBAL_NEWFORUM_NOTIFYDSC;
$modversion['notification']['event'][3]['mail_template'] = 'global_newforum_notify';
$modversion['notification']['event'][3]['mail_subject'] = _MI_NEWBBEX_GLOBAL_NEWFORUM_NOTIFYSBJ;
$modversion['notification']['event'][4]['name'] = 'new_post';
$modversion['notification']['event'][4]['category'] = 'global';
$modversion['notification']['event'][4]['title'] = _MI_NEWBBEX_GLOBAL_NEWPOST_NOTIFY;
$modversion['notification']['event'][4]['caption'] = _MI_NEWBBEX_GLOBAL_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][4]['description'] = _MI_NEWBBEX_GLOBAL_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][4]['mail_template'] = 'global_newpost_notify';
$modversion['notification']['event'][4]['mail_subject'] = _MI_NEWBBEX_GLOBAL_NEWPOST_NOTIFYSBJ;
$modversion['notification']['event'][5]['name'] = 'new_post';
$modversion['notification']['event'][5]['category'] = 'forum';
$modversion['notification']['event'][5]['title'] = _MI_NEWBBEX_FORUM_NEWPOST_NOTIFY;
$modversion['notification']['event'][5]['caption'] = _MI_NEWBBEX_FORUM_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][5]['description'] = _MI_NEWBBEX_FORUM_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][5]['mail_template'] = 'forum_newpost_notify';
$modversion['notification']['event'][5]['mail_subject'] = _MI_NEWBBEX_FORUM_NEWPOST_NOTIFYSBJ;
$modversion['notification']['event'][6]['name'] = 'new_fullpost';
$modversion['notification']['event'][6]['category'] = 'global';
$modversion['notification']['event'][6]['admin_only'] = 1;
$modversion['notification']['event'][6]['title'] = _MI_NEWBBEX_GLOBAL_NEWFULLPOST_NOTIFY;
$modversion['notification']['event'][6]['caption'] = _MI_NEWBBEX_GLOBAL_NEWFULLPOST_NOTIFYCAP;
$modversion['notification']['event'][6]['description'] = _MI_NEWBBEX_GLOBAL_NEWFULLPOST_NOTIFYDSC;
$modversion['notification']['event'][6]['mail_template'] = 'global_newfullpost_notify';
$modversion['notification']['event'][6]['mail_subject'] = _MI_NEWBBEX_GLOBAL_NEWFULLPOST_NOTIFYSBJ;
