<?php
	//Khai báo các biến 
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "kiemtralan1";
	
	//Tạo kết nối
	$conn = new mysqli($servername,$username,$password,$dbname);
	
	//Kiểm tra kết nối
	if (!$conn)
	{
		die("Lỗi kết nối: ".mysqli_connect_error());
	}
/*
	else
	{
		echo "Kết nối thành công";
	}
*/

?>