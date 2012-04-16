<?php

/*
	This file is part of myTinyTodo.
	(C) Copyright 2012 Alexander Adam <info@alexander-adam.net>
	Licensed under the GNU GPL v3 license. See file COPYRIGHT for details.
*/

require_once('./init.php');

$lang = Lang::instance();
$db = DBConnection::instance();

if(($needAuth && !is_logged()) || !is_admin())
{
	die("Access denied!<br/> Disable password protection or Log in.");
}

function getUserListFromDB()
{
	global $db;
	$q = $db->dq("SELECT * FROM {$db->prefix}users");
	if($q->rows() > 0)
	{
		while($r = $q->fetch_assoc($q))
		{
			?>
			<tr>
				<td valign="left" class="username"><?php echo $r['username'] ?></td>
				<td valign="left" class="email"><?php echo $r['email'] ?></td>
				<td valign="left" class="role"><?php echo _e('um_rolename_'.$r['role']) ?></td>
				<td>
					<a href="#" class="edituser" rel="<?php echo $r['id'] ?>"><?php _e('action_edit') ?></a>
					<?php if($r['id'] != $_SESSION['userid']) { ?>
					&nbsp;|&nbsp;
					<a href="#" class="deleteuser" rel="<?php echo $r['id'] ?>"><?php _e('action_delete') ?></a>
					<?php } ?>
				</td>
			</tr>
			<?
		}
	}
	else
	{
		?>
		<tr>
			<td colspan="4"><?php _e('um_nousers'); ?></td>
		</tr>
		<?
	}
}



header('Content-type:text/html; charset=utf-8');
?>
<div><a href="#" class="mtt-back-button"><?php _e('go_back');?></a></div>

<h3><?php _e('um_header');?></h3>

<div id="usermanagement_msg" style="display:none"></div>

<a href="#" class="createuserBtn" id="createuserBtn"><?php _e('um_createuser');?></a>

<table class="mtt-usermanagement-table">
	<tr>
		<th><?php _e('um_username');?></th>
		<th><?php _e('um_email');?></th>
		<th><?php _e('um_role');?></th>
		<th></th>
	</tr>
	<?php getUserListFromDB(); ?>
</table>