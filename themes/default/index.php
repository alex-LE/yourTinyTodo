<?php
/*
This file is part of yourTinyTodo by the yourTinyTodo community.
Copyrights for portions of this file are retained by their owners.

Based on myTinyTodo by Max Pozdeev
(C) Copyright 2009-2010 Max Pozdeev <maxpozdeev@gmail.com>

Licensed under the GNU GPL v3 license. See file COPYRIGHT for details.
*/

header("Content-type: text/html; charset=utf-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php yttinfo('title'); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php yttinfo('template_url'); ?>style.css" media="all" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php yttinfo('template_url'); ?> /favicon.ico" />
	<?php if(Config::get('rtl')): ?>
	<link rel="stylesheet" type="text/css" href="<?php yttinfo('template_url'); ?>style_rtl.css" media="all" />
	<?php endif; ?>
</head>

<body>

<script type="text/javascript" src="<?php yttinfo('ytt_url'); ?>jquery/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php yttinfo('ytt_url'); ?>jquery/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php yttinfo('ytt_url'); ?>jquery/jquery.autocomplete-1.1.js"></script>
<script type="text/javascript" src="<?php yttinfo('ytt_url'); ?>jquery/jquery.cookie.js?v=<?=YTT_VERSION?>"></script>
<script type="text/javascript" src="<?php yttinfo('ytt_url'); ?>yourtinytodo.js?v=<?=YTT_VERSION?>"></script>
<script type="text/javascript" src="<?php yttinfo('ytt_url'); ?>yourtinytodo_lang.php?v=<?=YTT_VERSION?>"></script>
<script type="text/javascript" src="<?php yttinfo('ytt_url'); ?>yourtinytodo_ajax_storage.js?v=<?=YTT_VERSION?>"></script>
<script type="text/javascript" src="<?php yttinfo('ytt_url'); ?>jquery/highcharts.js"></script>


<?php require_once(YTTPATH.'yourtinytodo_theme_init_js.php'); ?>

<div id="wrapper">
<div id="container">
<div id="ytt_body">

<div id="function-bar">
	<h2><?php yttinfo('title'); ?></h2>

	<div id="bar">
		<div id="msg">
			<span class="msg-text"></span>
			<div class="msg-details"></div>
		</div>
		<div class="bar-menu">
			<span class="menu-owner menuitem" style="display:none;position: relative;">
				<a href="#notifications" id="notifications" style="display:none"><?php _e('a_notifications');?><span id="notification_counter">
						<span class="notification_counter-left"></span>
						<span class="notification_counter-value"></span>
						<span class="notification_counter-right"></span>
					</span>
				</a>
			</span>
			<span class="bar-delim" style="display:none"></span>
			<span class="menu-owner menuitem" style="display:none">
				<a href="#settings" id="settings"><?php _e('a_settings');?></a>
			</span>
			<span class="bar-delim" style="display:none"></span>
			<span id="bar_auth">
				<span id="bar_public" style="display:none"><?php _e('public_tasks');?></span>
				<a href="#login" id="bar_login" class="nodecor menuitem"><u><?php _e('a_login');?></u></a>
				<a href="#logout" id="bar_logout" class="menuitem"><?php _e('a_logout');?></a>
			</span>
		</div>
	</div>
</div>

<div id="main">

<div id="header">
	<div id="ytt-work-timer">
		<span id="ytt-time">00:00:00</span>
		<a href="#" id="ytt-timer-pause" title="<?=_e('timer_pause')?>"></a>
		<a href="#" id="ytt-timer-stop" title="<?=_e('timer_stop')?>"></a>
		<a href="#" id="ytt-timer-finish" title="<?=_e('timer_finish')?>"></a>
		<a href="#" id="ytt-timer-continue" title="<?=_e('timer_continue')?>"></a>
	</div>
</div>

<div id="page_tasks" style="display:none">

	<div id="lists">
		<ul class="ytt-tabs"></ul>
		<div class="ytt-tabs-add-button" title="<?php _e('list_new'); ?>"><span></span></div>
		<div id="tabs_buttons">
			<div class="ytt-tabs-select-button ytt-tabs-button" title="<?php _e('list_select'); ?>"><span></span></div>
		</div>
		<div id="list_all" class="ytt-tab ytt-tabs-alltasks ytt-tabs-hidden"><a href="#alltasks"><span><?php _e('alltasks'); ?></span></a><div class="list-action"></div></div>
	</div>



	<div id="toolbar" class="ytt-htabs">

		<div id="htab_search">
			<table class="ytt-searchbox"><tr><td>
				<div class="ytt-searchbox-c">
					<input type="text" name="search" value="" maxlength="250" id="search" autocomplete="off" />
					<div class="ytt-searchbox-icon ytt-icon-search"></div>
					<div id="search_close" class="ytt-searchbox-icon ytt-icon-cancelsearch"></div>
				</div>
			</td></tr></table>
		</div>

		<div id="htab_newtask">
			<table class="ytt-taskbox"><tr><td class="ytt-tb-cell">
				<div class="ytt-tb-c">
					<form id="newtask_form" method="post" action="">
						<label id="task_placeholder" class="placeholding" for="task">
							<input type="text" name="task" value="" maxlength="250" id="task" autocomplete="off" />
							<span><?php _e('htab_newtask');?></span>
						</label>
						<div id="newtask_submit" class="ytt-taskbox-icon ytt-icon-submittask" title="<?php _e('btn_add');?>"></div>
					</form>
				</div>
			</td>
				<td><a href="#" id="newtask_adv" class="ytt-img-button" title="<?php _e('advanced_add');?>"><span></span></a></td>
			</tr></table>
		</div>

		<div id="searchbar" style="display:none"><?php _e('searching');?> <span id="searchbarkeyword"></span></div>

		<div style="clear:both"></div>

	</div>


	<h3>
		<span id="taskview" class="ytt-menu-button"><span class="btnstr"><?php _e('tasks');?></span> (<span id="total">0</span>) <span class="arrdown"></span></span>
		<span class="ytt-notes-showhide"><?php _e('notes');?> <a href="#" id="ytt-notes-show"><?php _e('notes_show');?></a> / <a href="#" id="ytt-notes-hide"><?php _e('notes_hide');?></a></span>
		<span id="ytt_filters"></span>
		<span id="tagcloudbtn" class="ytt-menu-button"><?php _e('tagcloud');?> <span class="arrdown2"></span></span>
	</h3>

	<div id="taskcontainer">
		<ol id="tasklist" class="sortable"></ol>
		<div id="taskajax"></div>
	</div>

</div> <!-- end of page_tasks -->


<div id="page_taskedit" style="display:none">

	<div><a href="#" class="ytt-back-button"><?php _e('go_back');?></a></div>

	<h3 class="ytt-inadd"><?php _e('add_task');?></h3>
	<h3 class="ytt-inedit"><?php _e('edit_task');?>
		<span id="taskedit-date" class="ytt-inedit">
			(<span class="date-created" title="<?php _e('taskdate_created');?>"><span>&nbsp;</span></span><span class="date-completed" title="<?php _e('taskdate_completed');?>"> &mdash; <span>&nbsp;</span></span>)
		</span>
	</h3>

	<form id="taskedit_form" name="edittask" method="post" action="">
		<input type="hidden" name="isadd" value="0" />
		<input type="hidden" name="id" value="" />
		<div class="form-row form-row-short">
			<span class="h"><?php _e('priority');?></span>
			<select name="prio">
				<option value="2">+2</option><option value="1">+1</option><option value="0" selected="selected">&plusmn;0</option><option value="-1">&minus;1</option>
			</select>
		</div>
		<div class="form-row form-row-short">
			<span class="h"><?php _e('due');?> </span>
			<input name="duedate" id="duedate" value="" class="in100" title="Y-M-D, M/D/Y, D.M.Y, M/D, D.M" autocomplete="off" />
			<select name="duedate_h" id="duedate_h">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
			</select>
			&nbsp;<?php _e('hour_sign');?>
			<select name="duedate_m" id="duedate_m">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
				<option value="32">32</option>
				<option value="33">33</option>
				<option value="34">34</option>
				<option value="35">35</option>
				<option value="36">36</option>
				<option value="37">37</option>
				<option value="38">38</option>
				<option value="39">39</option>
				<option value="40">40</option>
				<option value="41">41</option>
				<option value="42">42</option>
				<option value="43">43</option>
				<option value="44">44</option>
				<option value="45">45</option>
				<option value="46">46</option>
				<option value="47">47</option>
				<option value="48">48</option>
				<option value="49">49</option>
				<option value="50">50</option>
				<option value="51">51</option>
				<option value="52">52</option>
				<option value="53">53</option>
				<option value="54">54</option>
				<option value="55">55</option>
				<option value="56">56</option>
				<option value="57">57</option>
				<option value="58">58</option>
				<option value="59">59</option>
			</select>
			&nbsp;<?php _e('minute_sign');?>
		</div>
		<div class="form-row form-row-short">
			<span class="h"><?php _e('duration');?> </span>
			<input name="duration_h" id="duration_h" value="" class="in35 textright" title="" autocomplete="off" />&nbsp;<?php _e('hour_sign');?>
			<input name="duration_m" id="duration_m" value="" class="in35 textright" title="" autocomplete="off" />&nbsp;<?php _e('minute_sign');?>
		</div>
		<div class="form-row-short-end"></div>
		<div class="form-row"><div class="h"><?php _e('task');?></div> <input type="text" name="task" value="" class="in500" maxlength="250" /></div>
		<div class="form-row"><div class="h"><?php _e('note');?></div> <textarea name="note" class="in500"></textarea></div>
		<div class="form-row"><div class="h"><?php _e('tags');?></div>
			<table cellspacing="0" cellpadding="0" width="100%"><tr>
				<td><input type="text" name="tags" id="edittags" value="" class="in500" maxlength="250" /></td>
				<td class="alltags-cell">
					<a href="#" id="alltags_show"><?php _e('alltags_show');?></a>
					<a href="#" id="alltags_hide" style="display:none"><?php _e('alltags_hide');?></a></td>
			</tr></table>
		</div>
		<div class="form-row" id="alltags" style="display:none;"><?php _e('alltags');?> <span class="tags-list">&nbsp;</span></div>
		<div class="form-row form-bottom-buttons">
			<input type="submit" value="<?php _e('save');?>" />
			<input type="button" id="ytt_edit_cancel" class="ytt-back-button" value="<?php _e('cancel');?>" />
		</div>
	</form>

</div>  <!-- end of page_taskedit -->

<div id="priopopup" style="display:none">
	<span class="prio-neg prio-neg-1">&minus;1</span>
	<span class="prio-zero">&plusmn;0</span>
	<span class="prio-pos prio-pos-1">+1</span>
	<span class="prio-pos prio-pos-2">+2</span>
</div>

<div id="page_ajax" style="display:none"></div>
</div>

</div>
<div id="space"></div>
</div>

<div id="ytt-menu-modal" class="ytt-menu-modal"></div>

<div id="footer">
	<div id="footer_content">
		Powered by <strong><a href="http://www.yourtinytodo.net/">yourTinyTodo</a></strong> <?=YTT_VERSION?>
		<div id="loggedinuser"></div>
	</div>
</div>

</div> <!-- end of main -->

<div id="cmenupriocontainer" class="ytt-menu-container" style="display:none">
	<ul>
		<li id="cmenu_prio:2"><div class="menu-icon"></div>+2</li>
		<li id="cmenu_prio:1"><div class="menu-icon"></div>+1</li>
		<li id="cmenu_prio:0"><div class="menu-icon"></div>&plusmn;0</li>
		<li id="cmenu_prio:-1"><div class="menu-icon"></div>&minus;1</li>
	</ul>
</div>

<div id="cmenulistscontainer" class="ytt-menu-container" style="display:none">
	<ul>
	</ul>
</div>

<div id="slmenucontainer" class="ytt-menu-container" style="display:none">
	<ul>
		<li id="slmenu_list:-1" class="list-id--1 ytt-need-list" <?php if(is_readonly()) echo 'style="display:none"' ?>><div class="menu-icon"></div><a href="#alltasks"><?php _e('alltasks'); ?></a></li>
		<li class="ytt-menu-delimiter"></li>
		<li id="slmenu_list:-2" class="list-id--2" <?php if(is_readonly()) echo 'style="display:none"' ?>><div class="menu-icon"></div><a href="#archivelists"><?php _e('archivelists'); ?></a></li>
		<li class="ytt-menu-delimiter slmenu-lists-begin ytt-need-list" <?php if(is_readonly()) echo 'style="display:none"' ?>></li>
	</ul>
</div>

<div id="ytt-createuser" style="display:none" class="ytt-menu-container">
	<form method="post" action="" name="createuserForm">
		<label for="um_username"><?php _e('um_username');?></label>
		<input type="text" name="um_username" id="um_username" value="" />

		<label for="um_email"><?php _e('um_email');?></label>
		<input type="text" name="um_email" id="um_email" value="" />

		<label for="um_password"><?php _e('um_password');?></label>
		<input type="password" name="um_password" id="um_password" value="" />

		<label for="um_notification"><?php _e('um_notification');?></label>
		<input type="checkbox" name="um_notification" id="um_notification" value="1" />

		<label for="um_role" class="ytt-clear"><?php _e('um_role');?></label>
		<select name="um_role" id="um_role">
			<option value="1"><?php _e('um_rolename_1')?></option>
			<option value="2"><?php _e('um_rolename_2')?></option>
			<option value="3"><?php _e('um_rolename_3')?></option>
		</select>

		<input type="hidden" value="" id="um_userid" name="um_userid" />

		<input type="button" id="createuserSubmit" value="<?php _e('save')?>" />
	</form>
</div>

<div id="taskviewcontainer" class="ytt-menu-container" style="display:none">
	<ul>
		<li id="view_tasks"><?php _e('tasks');?> (<span id="cnt_total">0</span>)</li>
		<li id="view_past"><?php _e('f_past');?> (<span id="cnt_past">0</span>)</li>
		<li id="view_today"><?php _e('f_today');?> (<span id="cnt_today">0</span>)</li>
		<li id="view_soon"><?php _e('f_soon');?> (<span id="cnt_soon">0</span>)</li>
	</ul>
</div>

<div id="tagcloud" style="display:none">
	<a id="tagcloudcancel" class="ytt-img-button"><span>&nbsp;</span></a>
	<div id="tagcloudload"></div>
	<div id="tagcloudcontent"></div>
</div>

<div id="listexportmenucontainer" class="ytt-menu-container" style="display:none">
	<ul>
		<li class="ytt-need-list ytt-need-real-list" id="btnExportCSV"><?php _e('list_export_csv');?></li>
		<li class="ytt-need-list ytt-need-real-list" id="btnExportICAL"><?php _e('list_export_ical');?></li>
	</ul>
</div>

<div id="authform" style="display:none">
	<form id="login_form" action="" method="post">
		<!-- if multiuser is enabled -->
		<div class="login_multiuser"><label for="username"><?php _e('um_username');?></label><input type="text" name="username" id="username" /></div>
		<div class="login_multiuser"><label for="password"><?php _e('um_password');?></label><input type="password" name="password" id="password" /></div>
		<div class="login_multiuser"><input type="submit" value="<?php _e('btn_login');?>" /></div>

		<!-- if singleuser is enabled -->
		<div class="h login_singleuser"><?php _e('password');?></div>
		<div class="login_singleuser"><input type="password" name="password" id="password" /></div>
		<div class="login_singleuser"><input type="submit" value="<?php _e('btn_login');?>" /></div>

	</form>
</div>

<div id="taskcontextcontainer" class="ytt-menu-container" style="display:none">
	<ul>
		<li id="cmenu_edit"><b><?php _e('action_edit');?></b></li>
		<li id="cmenu_note"><?php _e('action_note');?></li>
		<li id="cmenu_prio" class="ytt-menu-indicator" submenu="cmenupriocontainer"><div class="submenu-icon"></div><?php _e('action_priority');?></li>
		<li id="cmenu_move" class="ytt-menu-indicator" submenu="cmenulistscontainer"><div class="submenu-icon"></div><?php _e('action_move');?></li>
		<li id="cmenu_delete"><?php _e('action_delete');?></li>
	</ul>
</div>

<div id="listmenucontainer" class="ytt-menu-container" style="display:none">
	<ul>
		<li class="ytt-need-list ytt-need-real-list" id="btnRenameList"><?php _e('list_rename');?></li>
		<li class="ytt-need-list ytt-need-real-list" id="btnDeleteList"><?php _e('list_delete');?></li>
		<li class="ytt-need-list ytt-need-real-list" id="btnClearCompleted"><?php _e('list_clearcompleted');?></li>
		<li class="ytt-need-list ytt-need-real-list ytt-menu-indicator" id="btnExport" submenu="listexportmenucontainer"><div class="submenu-icon"></div><?php _e('list_export'); ?></li>
		<li class="ytt-menu-delimiter ytt-need-real-list"></li>
		<li class="ytt-need-list ytt-need-real-list" id="btnTimeTable"><?php _e('list_timetable');?></li>
		<li class="ytt-need-list ytt-need-real-list" id="btnPublish"><div class="menu-icon"></div><?php _e('list_publish');?></li>
		<li class="ytt-need-list ytt-need-real-list" id="btnArchive"><div class="menu-icon"></div><?php _e('list_archive');?></li>
		<li class="ytt-need-list ytt-need-real-list" id="btnPrivate"><div class="menu-icon"></div><?php _e('list_private');?></li>
		<li class="ytt-need-list ytt-need-real-list" id="btnRssFeed"><div class="menu-icon"></div><?php _e('list_rssfeed');?></li>
		<li class="ytt-menu-delimiter ytt-need-real-list"></li>
		<li class="ytt-need-list ytt-need-real-list sort-item" id="sortByHand"><div class="menu-icon"></div><?php _e('sortByHand');?> <span class="ytt-sort-direction">&nbsp;</span></li>
		<li class="ytt-need-list sort-item" id="sortByDateCreated"><div class="menu-icon"></div><?php _e('sortByDateCreated');?> <span class="ytt-sort-direction">&nbsp;</span></li>
		<li class="ytt-need-list sort-item" id="sortByPrio"><div class="menu-icon"></div><?php _e('sortByPriority');?> <span class="ytt-sort-direction">&nbsp;</span></li>
		<li class="ytt-need-list sort-item" id="sortByDueDate"><div class="menu-icon"></div><?php _e('sortByDueDate');?> <span class="ytt-sort-direction">&nbsp;</span></li>
		<li class="ytt-need-list sort-item" id="sortByDateModified"><div class="menu-icon"></div><?php _e('sortByDateModified');?> <span class="ytt-sort-direction">&nbsp;</span></li>
		<li class="ytt-menu-delimiter"></li>
		<li class="ytt-need-list" id="btnShowCompleted"><div class="menu-icon"></div><?php _e('list_showcompleted');?></li>
		<li class="ytt-menu-delimiter"></li>
		<li class="ytt-need-list" id="btnNotifications"><div class="menu-icon"></div><?php _e('list_notifications');?></li>
	</ul>
</div>

</body>
</html>