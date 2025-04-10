
<?php
		//1-Lấy biến trên URL sử dụng S_Get
		$masp ="";
		if(isset($_GET["ma"]))
		{
			$masp = $_GET["ma"];
		}
		//2- kết nói CSDL
		include_once ("Connect.php");
		
		//3. Viết câu truy vấn
		$sql = "DELETE FROM sanpham WHERE MaSP = '$masp'";
		//4. Thực thi câu truy vấn và kiểm tra kết quả
		if($conn->query($sql)===TRUE)
		{
			header ("Location:sanpham.php");
		}
		else
		{
			echo " Lỗi câu truy vấn: ".$sql."<br>". $conn->error;
		}
		//5. đóng kết nói
		$conn->close();

?>