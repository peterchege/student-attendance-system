<?php

include('admin/database_connection.php');
session_start();

$teacher_name = '';
$teacher_address = '';
$teacher_emailid = '';
$teacher_password = '';
$teacher_grade_id = '';
$teacher_qualification = '';
$teacher_doj = '';
$teacher_image = '';
$error_teacher_name = '';
$error_teacher_address = '';
$error_teacher_emailid = '';
$error_teacher_password = '';
$error_teacher_grade_id = '';
$error_teacher_qualification = '';
$error_teacher_doj = '';
$error_teacher_image = '';
$error = 0;

$teacher_image = $_POST["hidden_teacher_image"];
if($_FILES["teacher_image"]["name"] != '')
{
	$file_name = $_FILES["teacher_image"]["name"];
	$tmp_name = $_FILES["teacher_image"]['tmp_name'];
	$extension_array = explode(".", $file_name);
	$extension = strtolower($extension_array[1]);
	$allowed_extension = array('jpg','png');
	if(!in_array($extension, $allowed_extension))
	{
		$error_teacher_image = 'Invalid Image Format';
		$error++;
	}
	else
	{
		$teacher_image = uniqid() . '.' . $extension;
		$upload_path = 'admin/teacher_image/' . $teacher_image;				
		move_uploaded_file($tmp_name, $upload_path);
	}	
}

if(empty($_POST["teacher_name"]))
{
	$error_teacher_name = 'Teacher Name is required';
	$error++;
}
else
{
	$teacher_name = $_POST["teacher_name"];
}
if(empty($_POST["teacher_address"]))
{
	$error_teacher_address = 'Teacher Address is required';
	$error++;
}
else
{
	$teacher_address = $_POST["teacher_address"];
}
if(empty($_POST["teacher_emailid"]))
{
	$error_teacher_emailid = 'Email Address is required';
	$error++;
}
else
{
	if (!filter_var($_POST["teacher_emailid"], FILTER_VALIDATE_EMAIL))
	{
		$error_teacher_emailid = "Invalid email format"; 
		$error++;
	}
	else
	{
		$teacher_emailid = $_POST["teacher_emailid"];
	}
}
		
if(!empty($_POST["teacher_password"]))
{
	$teacher_password = $_POST["teacher_password"];
}

if(empty($_POST["teacher_grade_id"]))
{
	$error_teacher_grade_id = 'Grade is required';
	$error++;
}
else
{
	$teacher_grade_id = $_POST["teacher_grade_id"];
}

if(empty($_POST["teacher_qualification"]))
{
	$error_teacher_qualification = 'Qualification Field is required';
	$error++;
}
else
{
	$teacher_qualification = $_POST["teacher_qualification"];
}
if(empty($_POST["teacher_doj"]))
{
	$error_teacher_doj = 'Date of Join Field is required';
	$error++;
}
else
{
	$teacher_doj = $_POST["teacher_doj"];
}
if($error == 0)
{
	$output = array(
		'error'							=>	true,
		'error_teacher_name'			=>	$error_teacher_name,
		'error_teacher_address'			=>	$error_teacher_address,
		'error_teacher_emailid'			=>	$error_teacher_emailid,
		'error_teacher_grade_id'		=>	$error_teacher_grade_id,
		'error_teacher_qualification'	=>	$error_teacher_qualification,
		'error_teacher_doj'				=>	$error_teacher_doj,
		'error_teacher_image'			=>	$error_teacher_image
	);
}
else
{
	if($teacher_password != "")
	{
		$data = array(
			':teacher_name'				=>	$teacher_name,
			':teacher_address'			=>	$teacher_address,
			':teacher_emailid'			=>	$teacher_emailid,
			':teacher_password'			=>	password_hash($teacher_password, PASSWORD_DEFAULT),
			':teacher_qualification'	=>	$teacher_qualification,
			':teacher_doj'				=>	$teacher_doj,
			':teacher_image'			=>	$teacher_image,
			':teacher_grade_id'			=>	$teacher_grade_id,
			':teacher_id'				=>	$_POST["teacher_id"]
		);

		$query = "
		UPDATE tbl_teacher 
		SET teacher_name = :teacher_name, 
		teacher_address = :teacher_address, 
		teacher_emailid = :teacher_emailid, 
		teacher_password = :teacher_password, 
		teacher_grade_id = :teacher_grade_id, 
		teacher_qualification = :teacher_qualification, 
		teacher_doj = :teacher_doj, 
		teacher_image = :teacher_image 
		WHERE teacher_id = :teacher_id
		";

	}
	else
	{
		$data = array(
			':teacher_name'				=>	$teacher_name,
			':teacher_address'			=>	$teacher_address,
			':teacher_emailid'			=>	$teacher_emailid,
			':teacher_qualification'	=>	$teacher_qualification,
			':teacher_doj'				=>	$teacher_doj,
			':teacher_image'			=>	$teacher_image,
			':teacher_grade_id'			=>	$teacher_grade_id,
			':teacher_id'				=>	$_POST["teacher_id"]
		);
		$query = "
		UPDATE tbl_teacher 
		SET teacher_name = :teacher_name, 
		teacher_address = :teacher_address, 
		teacher_emailid = :teacher_emailid, 
		teacher_grade_id = :teacher_grade_id, 
		teacher_qualification = :teacher_qualification, 
		teacher_doj = :teacher_doj, 
		teacher_image = :teacher_image 
		WHERE teacher_id = :teacher_id
		";
	}

	$statement = $connect->prepare($query);
	if($statement->execute($data))
	{
		$output = array(
			'success'		=>	'Profile Details Change Successfully',
		);
	}

	echo json_encode($output);
}
?>