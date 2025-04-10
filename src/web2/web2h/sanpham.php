<!--Phần đầu-->
<?php
	include_once("Phandau.php");
?>
<!--Phần nội dung-->
<h2>
	Quản Lý Sản Phẩm
	<hr>
</h2>
<a class = "btn btn-danger" href = "them_sanpham.php">
	Thêm Sản Phẩm
</a>
<table class = "table table-bordered">
	<tr>
		<th>Mã Sản Phẩm</th>
		<th>Tên Sản Phẩm</th>
		<th>Ảnh Sản Phẩm</th>
		<th>Mô Tả Sản Phẩm</th>
		<th>Xóa</th>
		<th>Sửa</th>
	</tr>
	<?php
	// select dữ liệu từ bảng sanpham
	//1. kết nói CSDL
	
	include_once("Connect.php");
	//2. viết câu truy vấn
	
	$sql = "SELECT * FROM sanpham";
	//3. thực thi câu truy vấn, nhận kết quả trả về
	$result = $conn->query($sql);
	//4. kiểm tra dữ liệu và hiển thị kết quả nếu có mẫu tin
	if($result->num_rows>0)
	{
		// có mẫu tin >> hiển thị từng mẫu tin
		while ($row = $result->fetch_assoc())
		{
			echo "<tr>";
			echo "<td>".$row["MaSP"]."</td>";			
			echo "<td>".$row["TenSP"]."</td>";
			echo "<td><img width='50' height='50' src = 'images/".$row["Hinh"]."' alt = ''></td>";
			echo "<td>".$row["Mota"]."</td>";			
			echo "<td>";
	?>
			<a onclick = "return confirm('Bạn có chắc xóa không?');"
			href = "xoa_sanpham.php?ma=<?php echo $row["MaSP"];?>">Xóa</a>
	<?php
			echo "</td>";
		echo "<td>";
	?>
		<a href = "sua_sanpham.php?ma=<?php echo $row["MaSP"];?>">Sửa</a>
	<?php
		echo "</td>";
		echo "</tr>";
		}
	}
	else
	{
		//o có mẫu tin
		echo " không sản phẩm";
	}
	?>
	
</table>
<!--Phần chân trang-->
<?php
	include_once("Phanchan.php");
?>
