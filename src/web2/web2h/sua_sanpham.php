<?php
	include_once("Phandau.php");
?>
<?php
	//1-Lấy dữ liệu từ URL đổ lên form
	$masp = "";
	if(isset($_GET["ma"]))
	{
		$masp = $_GET["ma"];
	}
	//Select các thông tin còn lại của mẩu tin có mã vừa lấy từ url
	include_once("connect.php");
	$sql = "SELECT * FROM sanpham WHERE MaSP = '$masp'";
	$result = $conn->query($sql);
	$ma_val = $ten_val = $mota_val = $file_val="";
	$row = $result->fetch_assoc();
	
	$ma_val = $row["MaSP"];
	$ten_val = $row["TenSP"];
	$mota_val = $row["Mota"];
	$file_val = $row["Hinh"];
?>
<!--Thiết kế form thêm mới một sản phẩm-->
<div class = "row">
	<div class = "col-sm-2">&nbsp;</div>
	<div class = "col-sm-8">
		<fieldset>
			<legend>Cập Nhật Sản Phẩm</legend>
			<form name = "frmSuaSP" action = "xuly_suasanpham.php" method = "post" enctype="multipart/form-data">
				<div class="mb-3 mt-3" >
					<label for="txtMa" class="form-label">Mã Sản Phẩm:</label>
					<input readonly type="text" class="form-control" id="txtMa"
						placeholder="Nhập mã sản phẩm mới" name="txtMa" style="background-color: #e5e5e5;"
						value = "<?php if(isset($_GET["ma"])){ echo $ma_val;}?>"
						>
				</div>
				<div class="mb-3 mt-3">
					<label for="txtTen" class="form-label">Nhập Tên Sản Phẩm:</label>
					<input type="text" class="form-control" id="txtTen" 
						placeholder="Nhập tên sản phẩm mới" name="txtTen"
						value = "<?php if(isset($_GET["ma"])){ echo $ten_val;}?>"
						>
				</div>
				<div class="mb-3 mt-3">
					<label for="txtMoTa" class="form-label">Nhập Mô Tả:</label>
					<textarea class="form-control"  name = "txtMoTa" id = "txtMoTa" cols = "50" rows = "3"><?php if(isset($_GET["ma"])){ echo $mota_val;}?></textarea>
				</div>
				<div class="mb-3 mt-3">
					<label for="fileHinh" class="form-label">Chọn hình đại diện:</label>
					<input type = "file" name = "fileHinh"
					value = "<?php if(isset($_GET["ma"])){ echo $file_val;}?>"
						>						
				</div>	
				<input class = "btn btn-danger" type = "submit" name = "sbSua" value = "Lưu">
				<input class = "btn btn-danger" type = "reset" name = "sbHuy" value = "Hủy">
			</form>
		</fieldset>
	</div>
	<div class = "col-sm-2">&nbsp;</div>
</div>

<?php
	include_once("Phanchan.php");
?>