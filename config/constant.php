<?php 

return [
	'siteTitle' => 'Journey with Journals',
	'paginatePerPage' => 10,
	'communityPerPage' => 6,
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
	'inquiry_type' => array(
		1 => 'Plans Related', 2 => 'Billing Related', 3 => 'General Inquiry', 4 => 'Community Related', 5 => 'Community Guidelines', 6 => 'Community Creation', 7 => 'Task Management', 8 => 'Journals Related', 9 => 'Journaling Functionality'
	)
];

?>