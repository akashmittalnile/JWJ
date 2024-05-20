<?php 

return [
	'siteTitle' => 'Journey with Journals',
	'paginatePerPage' => 10,
	'apiPaginatePerPage' => 10,
	'communityPerPage' => 6,
	'postPerPage' => 5,
	'role' => array(
		'User' => 1,
		'Admin' => 2,
	),
	'userStatus' => array(
		'0' => 'Pending',
		'1' => 'Active',
		'2' => 'Inactive',
		'3' => 'Rejected',
	),
	'frequency' => array(
		'O' => 'Once', 'D' => 'Daily', 'T' => 'Date', 'C' => 'Custom', 'R' => 'Repeat'
	),
	'days' => array(
		'M'=> 'Monday', 'T'=>'Tuesday', 'W'=>'Wednesday', 'TH'=>'Thursday', 'F'=>'Friday', 'SA'=>'Saturday', 'S'=>'Sunday'
	),
	'day_num' => array(
		1=> 'M', 2=> 'T', 3=> 'W', 4=> 'TH', 5=> 'F', 6=> 'SA', 7=> 'S'
	),
	'inquiry_type' => array(
		1 => 'Plans Related', 2 => 'Billing Related', 3 => 'General Inquiry', 4 => 'Community Related', 5 => 'Community Guidelines', 6 => 'Community Creation', 7 => 'Task Management', 8 => 'Journals Related', 9 => 'Journaling Functionality'
	),
	'object_type' => array(
		1 => 'post', 2 => 'community', 3 => 'routine', 4 => 'task', 5 => 'journal'
	),
];

?>