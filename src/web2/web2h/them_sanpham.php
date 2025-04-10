<?php
	include_once("Phandau.php");
?>
<!--Thiết kế form thêm mới một sản phẩm - sanpham-->
<div class = "row">
	<div class = "col-sm-2">&nbsp;</div>
	<div class = "col-sm-8">
		<fieldset>
			<legend>Thêm Một Sản Phẩm Mới</legend>
			<form name = "frmThemSP" action = "xuly_themsanpham.php" method = "post" enctype="multipart/form-data">
							
				<div class="mb-3 mt-3">
					<label for="txtTen" class="form-label">Nhập Tên Sản Phẩm:</label>
					<input type="text" class="form-control" id="txtTen" 
						placeholder="Nhập tên sản phẩm mới" name="txtTen">
				</div>	
				
				<div class="mb-3 mt-3">
					<label for="txtMoTa" class="form-label">Nhập Mô Tả:</label>
					<textarea class="form-control"  name = "txtMoTa" id = "txtMoTa" cols = "50" rows = "3"></textarea>
				</div>
				
				<div class="mb-3 mt-3">
					<label for="fileHinh" class="form-label">Chọn Hình Đại Diện:</label>
					<input type = "file" name = "fileHinh">
				</div>	
				
				<input class = "btn btn-danger" type = "submit" name = "sbThem" value = "Thêm">
				<input class = "btn btn-danger" type = "reset" name = "sbHuy" value = "Hủy">
			</form>
		</fieldset>
	</div>
	<div class = "col-sm-2">&nbsp;</div>
</div>

<?php
	include_once("Phanchan.php");
?>
