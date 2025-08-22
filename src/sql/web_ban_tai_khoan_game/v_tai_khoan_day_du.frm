TYPE=VIEW
query=select `t`.`tai_khoan_id` AS `tai_khoan_id`,`t`.`tieu_de` AS `tieu_de`,`t`.`gia` AS `gia`,`t`.`da_ban` AS `da_ban`,`g`.`ten_game` AS `ten_game`,`d`.`ten_danh_muc` AS `ten_danh_muc`,`u`.`ten_dang_nhap` AS `nguoi_ban`,count(`h`.`hinh_anh_id`) AS `so_hinh_anh`,`t`.`ngay_tao` AS `ngay_tao`,`t`.`ngay_cap_nhat` AS `ngay_cap_nhat` from ((((`web_ban_tai_khoan_game`.`tai_khoan` `t` join `web_ban_tai_khoan_game`.`game` `g` on(`t`.`game_id` = `g`.`game_id`)) join `web_ban_tai_khoan_game`.`danh_muc_tai_khoan` `d` on(`t`.`danh_muc_id` = `d`.`danh_muc_id`)) join `web_ban_tai_khoan_game`.`nguoi_dung` `u` on(`t`.`nguoi_ban_id` = `u`.`nguoi_dung_id`)) left join `web_ban_tai_khoan_game`.`hinh_anh_tai_khoan` `h` on(`t`.`tai_khoan_id` = `h`.`tai_khoan_id`)) group by `t`.`tai_khoan_id`
md5=eacb78e8c6ab07f573587d9ec2440ddf
updatable=0
algorithm=0
definer_user=root
definer_host=localhost
suid=2
with_check_option=0
timestamp=0001743582017845266
create-version=2
source=SELECT \n    t.tai_khoan_id,\n    t.tieu_de,\n    t.gia,\n    t.da_ban,\n    g.ten_game,\n    d.ten_danh_muc,\n    u.ten_dang_nhap AS nguoi_ban,\n    COUNT(h.hinh_anh_id) AS so_hinh_anh,\n    t.ngay_tao,\n    t.ngay_cap_nhat\nFROM\n    TAI_KHOAN t\n    JOIN GAME g ON t.game_id = g.game_id\n    JOIN DANH_MUC_TAI_KHOAN d ON t.danh_muc_id = d.danh_muc_id\n    JOIN NGUOI_DUNG u ON t.nguoi_ban_id = u.nguoi_dung_id\n    LEFT JOIN HINH_ANH_TAI_KHOAN h ON t.tai_khoan_id = h.tai_khoan_id\nGROUP BY\n    t.tai_khoan_id
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=select `t`.`tai_khoan_id` AS `tai_khoan_id`,`t`.`tieu_de` AS `tieu_de`,`t`.`gia` AS `gia`,`t`.`da_ban` AS `da_ban`,`g`.`ten_game` AS `ten_game`,`d`.`ten_danh_muc` AS `ten_danh_muc`,`u`.`ten_dang_nhap` AS `nguoi_ban`,count(`h`.`hinh_anh_id`) AS `so_hinh_anh`,`t`.`ngay_tao` AS `ngay_tao`,`t`.`ngay_cap_nhat` AS `ngay_cap_nhat` from ((((`web_ban_tai_khoan_game`.`tai_khoan` `t` join `web_ban_tai_khoan_game`.`game` `g` on(`t`.`game_id` = `g`.`game_id`)) join `web_ban_tai_khoan_game`.`danh_muc_tai_khoan` `d` on(`t`.`danh_muc_id` = `d`.`danh_muc_id`)) join `web_ban_tai_khoan_game`.`nguoi_dung` `u` on(`t`.`nguoi_ban_id` = `u`.`nguoi_dung_id`)) left join `web_ban_tai_khoan_game`.`hinh_anh_tai_khoan` `h` on(`t`.`tai_khoan_id` = `h`.`tai_khoan_id`)) group by `t`.`tai_khoan_id`
mariadb-version=100432
