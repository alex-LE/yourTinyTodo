<script type="text/javascript">
	$().ready(function(){

	yourtinytodo.yttUrl = "<?php yttinfo('ytt_url'); ?>";
	yourtinytodo.templateUrl = "<?php yttinfo('template_url'); ?>";
	yourtinytodo.db = new yourtinytodoStorageAjax(yourtinytodo);
	yourtinytodo.init({
		needAuth: <?php echo $needAuth ? "true" : "false"; ?>,
		multiUser: <?php echo $multiUser ? "true" : "false"; ?>,
		admin: <?php echo is_admin() ? "true" : "false"; ?>,
		readOnly: <?php echo is_readonly() ? "true" : "false"; ?>,
		<? if(isset($_SESSION['userid'])) {?>
		globalNotifications: <?php echo (NotificationListener::hasGlobalNotifications($_SESSION['userid'])) ? "true" : "false"; ?>,
		userId: <?php echo (!empty($_SESSION['userid']))?$_SESSION['userid']:'null'; ?>,
		userRole: <?php echo (!empty($_SESSION['role']))?$_SESSION['role']:'null'; ?>,
		userName: <?php echo (isset($_SESSION['userid']))?getUserName($_SESSION['userid']):''; ?>,
		<? } ?>
		isLogged: <?php echo ($needAuth && is_logged()) ? "true" : "false"; ?>,
		showdate: <?php echo (Config::get('showdate') && !isset($_GET['pda'])) ? "true" : "false"; ?>,
		singletab: <?php echo (isset($_GET['singletab']) || isset($_GET['pda'])) ? "true" : "false"; ?>,
		duedatepickerformat: "<?php echo htmlspecialchars(Config::get('dateformat2')); ?>",
		dateformatshort: "<?php echo htmlspecialchars(Config::get('dateformatshort')); ?>",
		firstdayofweek: <?php echo (int) Config::get('firstdayofweek'); ?>,
		autotag: <?php echo Config::get('autotag') ? "true" : "false"; ?>,
		authbypass: <?php echo Config::get('auth_bypass') == 'none' ? "false" : "true"; ?>,
		debugmode: <?php echo Config::get('debugmode') ? "true" : "false"; ?>,
		notification_count: <?php echo ($notifications_count === false)?"false":$notifications_count ?>,
		show_edit_options: <?php echo (!isset($_SESSION['role']) || $_SESSION['role'] < 3)?'true':'false'; ?>
	<?php if(isset($_GET['list'])) echo ",openList: ". (int)$_GET['list']; ?>
	<?php if(isset($_GET['pda'])) echo ", touchDevice: true"; ?>
	}).loadLists(1);

	});
</script>