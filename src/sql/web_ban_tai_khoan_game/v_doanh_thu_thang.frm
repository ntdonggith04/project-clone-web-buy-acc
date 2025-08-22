TYPE=VIEW
query=select date_format(`dh`.`ngay_hoan_thanh`,\'%Y-%m\') AS `thang`,count(`dh`.`don_hang_id`) AS `so_don_hang`,sum(`dh`.`tong_tien`) AS `tong_doanh_thu` from `web_ban_tai_khoan_game`.`don_hang` `dh` where `dh`.`trang_thai_don_hang` = \'Hoàn thành\' and `dh`.`ngay_hoan_thanh` is not null group by date_format(`dh`.`ngay_hoan_thanh`,\'%Y-%m\') order by date_format(`dh`.`ngay_hoan_thanh`,\'%Y-%m\')
md5=97d78d741d6ec14cc9b7312b145c115c
updatable=0
algorithm=0
definer_user=root
definer_host=localhost
suid=2
with_check_option=0
timestamp=0001743582017867642
create-version=2
source=SELECT \n    DATE_FORMAT(dh.ngay_hoan_thanh, \'%Y-%m\') AS thang,\n    COUNT(dh.don_hang_id) AS so_don_hang,\n    SUM(dh.tong_tien) AS tong_doanh_thu\nFROM \n    DON_HANG dh\nWHERE \n    dh.trang_thai_don_hang = \'Hoàn thành\'\n    AND dh.ngay_hoan_thanh IS NOT NULL\nGROUP BY \n    DATE_FORMAT(dh.ngay_hoan_thanh, \'%Y-%m\')\nORDER BY \n    thang
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=select date_format(`dh`.`ngay_hoan_thanh`,\'%Y-%m\') AS `thang`,count(`dh`.`don_hang_id`) AS `so_don_hang`,sum(`dh`.`tong_tien`) AS `tong_doanh_thu` from `web_ban_tai_khoan_game`.`don_hang` `dh` where `dh`.`trang_thai_don_hang` = \'Hoàn thành\' and `dh`.`ngay_hoan_thanh` is not null group by date_format(`dh`.`ngay_hoan_thanh`,\'%Y-%m\') order by date_format(`dh`.`ngay_hoan_thanh`,\'%Y-%m\')
mariadb-version=100432
