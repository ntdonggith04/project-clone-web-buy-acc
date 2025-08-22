TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`localhost` TRIGGER after_order_completed\nAFTER UPDATE ON DON_HANG\nFOR EACH ROW\nBEGIN\n    IF NEW.trang_thai_don_hang = \'Hoàn thành\' AND OLD.trang_thai_don_hang != \'Hoàn thành\' THEN\n        UPDATE TAI_KHOAN\n        SET da_ban = TRUE\n        WHERE tai_khoan_id = NEW.tai_khoan_id;\n    END IF;\nEND'
sql_modes=1098907648
definers='root@localhost'
client_cs_names='utf8mb4'
connection_cl_names='utf8mb4_unicode_ci'
db_cl_names='utf8mb4_general_ci'
created=1743582017784028
