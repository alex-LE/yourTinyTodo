/*
 This file is part of yourTinyTodo by the yourTinyTodo community.
 Copyrights for portions of this file are retained by their owners.

 Based on myTinyTodo by Max Pozdeev
 (C) Copyright 2009-2010 Max Pozdeev <maxpozdeev@gmail.com>

 Licensed under the GNU GPL v3 license. See file COPYRIGHT for details.
 */

// AJAX yourTinyTodo Storage

(function(){

var ytt;

function yourtinytodoStorageAjax(aytt)
{
	this.ytt = ytt = aytt;
}

window.yourtinytodoStorageAjax = yourtinytodoStorageAjax;

yourtinytodoStorageAjax.prototype =
{
	/* required method */
	request:function(action, params, callback)
	{
		if(!this[action]) throw "Unknown storage action: "+action;

		this[action](params, function(json){
			if(json.denied) ytt.errorDenied();
			if(callback) callback.call(ytt, json)
		});
	},


	loadLists: function(params, callback)
	{
		$.getJSON(this.ytt.yttUrl+'ajax.php?loadLists'+'&rnd='+Math.random(), callback);
	},


	loadTasks: function(params, callback)
	{
		var q = '';
		if(params.search && params.search != '') q += '&s='+encodeURIComponent(params.search);
		if(params.tag && params.tag != '') q += '&t='+encodeURIComponent(params.tag);
		if(params.setCompl && params.setCompl != 0) q += '&setCompl=1';
		q += '&rnd='+Math.random();

/*		$.getJSON(ytt.yttUrl+'ajax.php?loadTasks&list='+params.list+'&compl='+params.compl+'&sort='+params.sort+'&tz='+params.tz+q, function(json){
			callback.call(ytt, json);
		})
*/

		$.getJSON(this.ytt.yttUrl+'ajax.php?loadTasks&list='+params.list+'&compl='+params.compl+'&sort='+params.sort+q, callback);
	},


	newTask: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?newTask',
			{ list:params.list, title: params.title, tag:params.tag }, callback, 'json');
	},
	

	fullNewTask: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?fullNewTask',
			{ list:params.list, title:params.title, note:params.note, prio:params.prio, tags:params.tags, duedate:params.duedate },
			callback, 'json');
	},


	editTask: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?editTask='+params.id,
			{ id:params.id, title:params.title, note:params.note, prio:params.prio, tags:params.tags, duedate:params.duedate },
			callback, 'json');
	},


	editNote: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?editNote='+params.id, {id:params.id, note: params.note}, callback, 'json');
	},


	completeTask: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?completeTask='+params.id, { id:params.id, compl:params.compl }, callback, 'json');
	},


	deleteTask: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?deleteTask='+params.id, { id:params.id }, callback, 'json');
	},


	setPrio: function(params, callback)
	{
		$.getJSON(this.ytt.yttUrl+'ajax.php?setPrio='+params.id+'&prio='+params.prio+'&rnd='+Math.random(), callback);
	},

	
	setSort: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?setSort', { list:params.list, sort:params.sort }, callback, 'json');
	},

	changeOrder: function(params, callback)
	{
		var order = '';
		for(var i in params.order) {
			order += params.order[i].id +'='+ params.order[i].diff + '&';
		}
		$.post(this.ytt.yttUrl+'ajax.php?changeOrder', { order:order }, callback, 'json');
	},

	tagCloud: function(params, callback)
	{
		$.getJSON(this.ytt.yttUrl+'ajax.php?tagCloud&list='+params.list+'&rnd='+Math.random(), callback);
	},

	moveTask: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?moveTask', { id:params.id, from:params.from, to:params.to }, callback, 'json');
	},

	parseTaskStr: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?parseTaskStr', { list:params.list, title:params.title, tag:params.tag }, callback, 'json');
	},
	

	// Lists
	addList: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?addList', { name:params.name }, callback, 'json'); 

	},

	renameList:  function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?renameList', { list:params.list, name:params.name }, callback, 'json');
	},

	deleteList: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?deleteList', { list:params.list }, callback, 'json');
	},

	publishList: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?publishList', { list:params.list, publish:params.publish },  callback, 'json');
	},
	
	setShowNotesInList: function(params, callback)
	{
	    $.post(this.ytt.yttUrl+'ajax.php?setShowNotesInList', { list:params.list, shownotes:params.shownotes },  callback, 'json');
	},
	
	setHideList: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?setHideList', { list:params.list, hide:params.hide }, callback, 'json');
	},

	changeListOrder: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?changeListOrder', { order:params.order }, callback, 'json');
	},

	clearCompletedInList: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?clearCompletedInList', { list:params.list }, callback, 'json');
	},

	createUser: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?createuser', { yttusername:params.username, yttpassword:params.password, email:params.email, role:params.role }, callback, 'json');
	},

	editUser: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?edituser', { yttuserid:params.userid, yttusername:params.username, yttpassword:params.password, email:params.email, role:params.role }, callback, 'json');
	},

	deleteUser: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?deleteuser', { yttuserid:params.userid }, callback, 'json');
	},

    markread: function(params, callback)
	{
		$.post(this.ytt.yttUrl+'ajax.php?markread', { yttnotificationid:params.yttnotificationid }, callback, 'json');
	},

    countNotifications: function(params, callback)
    {
        $.getJSON(this.ytt.yttUrl+'ajax.php?countNotifications&rnd='+Math.random(), callback);
    }
};

})();