<?php
	//1- Kết nối cơ sở dữ liệu
	include_once("connect.php");
	
	$target_dir = "images/";
	$target_file = $target_dir . basename($_FILES["fileHinh"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
	$ma = $ten = $mota = $file ="";
	$check="";
    if(!empty($_FILES["fileHinh"]["tmp_name"]))
    {
		$check = getimagesize($_FILES["fileHinh"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} 
		else 
		{
		if (move_uploaded_file($_FILES["fileHinh"]["tmp_name"], $target_file)) 
		{
			echo "File ảnh ". htmlspecialchars( basename( $_FILES["fileHinh"]["name"])). " đã thêm thành công.";
			$file = $_FILES["fileHinh"]["name"];		
		} 
		else 
		{
			echo "Sorry, there was an error uploading your file.";
		}
		}
	}
	//2-Lấy dữ liệu từ form
	$ma = $_POST["txtMa"];
	$ten = $_POST["txtTen"];
	$mota = $_POST["txtMoTa"];
	//3-Viết câu truy vấn cập nhật
	if(!empty($file))
	{
	$sql = "UPDATE sanpham SET TenSP = '$ten', Mota = '$mota', Hinh = '$file' WHERE MaSP = '$ma'";
	}
	else 
	{
	$sql = "UPDATE sanpham SET TenSP = '$ten', Mota = '$mota' WHERE MaSP = '$ma'";
	}
	//4- Thực thi câu truy vấn và kiểm tra kết quả
	if($conn->query($sql)===TRUE)
	{
		//echo "Thành công rồi";
		header("Location:sanpham.php");
	}
	else
	{
		if (move_uploaded_file($_FILES["fileHinh"]["tmp_name"], $target_file)) 
	  {
		echo "File ảnh ". htmlspecialchars( basename( $_FILES["fileHinh"]["name"])). " đã thêm thành công.";
		$file = $_FILES["fileHinh"]["name"];	
	  } 
	{
		echo "Lỗi câu truy vấn ".$sql."<br>".$conn->error;
	}
	}
	//5- Đóng kết nối
	$conn->close();
?>