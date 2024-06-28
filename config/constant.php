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
	'fcm' => array(
		'url'=>'https://fcm.googleapis.com/fcm/send',
		'secretKey'=>'key=AAAA6y2v4W8:APA91bG84auFeLYvL6XXwBfwoJxiF_XB1V6nai2IHyyY9TMoMl2YtoLxCLl6YwvoZ309_AiRWCVRTb5uNZtpekzUznYQj7vgMAEY5nt22L374fndQ-4r4SyJNM4kh4VYpGjVa5tEXaWx',
		'FCM_API_KEY'=>'AIzaSyBopYjZG97AO9Da83d5AzsBXrdlNBFvPqk',
		'FCM_AUTH_DOMAIN'=>"chatapp-29659.firebaseapp.com",
		'FCM_PROJECT_ID'=>"chatapp-29659",
		'FCM_STORAGE_BUCKET'=>"chatapp-29659.appspot.com",
		'FCM_MESSAGIN_SENDER_ID'=>"1010083815791",
		'FCM_APP_ID'=>"1:1010083815791:web:750f119bab8c0a81a3889a",
		'FCM_JSON'=>'fcm.json',
		'FCM_API_SERVER_KEY'=>'key=AAAA6y2v4W8:APA91bG84auFeLYvL6XXwBfwoJxiF_XB1V6nai2IHyyY9TMoMl2YtoLxCLl6YwvoZ309_AiRWCVRTb5uNZtpekzUznYQj7vgMAEY5nt22L374fndQ-4r4SyJNM4kh4VYpGjVa5tEXaWx'
	),

	
];

?>