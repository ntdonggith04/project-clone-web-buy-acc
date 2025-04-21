<?php
namespace App\Services;

class VNPayService {
    /**
     * Tạo URL thanh toán VNPay
     * 
     * @param int $orderId ID đơn hàng
     * @param float $amount Số tiền thanh toán (VND)
     * @param string $orderInfo Thông tin đơn hàng
     * @param string $returnUrl URL callback khi thanh toán xong (có thể ghi đè URL mặc định)
     * @return string URL thanh toán
     */
    public static function createPaymentUrl($orderId, $amount, $orderInfo = '', $returnUrl = '') {
        // Kiểm tra nếu đang trong chế độ sandbox
        if (defined('VNPAY_SANDBOX') && VNPAY_SANDBOX) {
            return BASE_URL . 'payment/sandbox/' . $orderId;
        }
        
        $vnp_TmnCode = VNPAY_TMN_CODE;
        $vnp_HashSecret = VNPAY_HASH_SECRET_KEY;
        $vnp_Url = VNPAY_URL;
        $vnp_Returnurl = !empty($returnUrl) ? $returnUrl : VNPAY_RETURN_URL;
        
        // Thông tin đơn hàng mặc định nếu không được cung cấp
        if (empty($orderInfo)) {
            $orderInfo = "Thanh toan don hang #{$orderId}";
        }
        
        $vnp_TxnRef = $orderId; // Mã đơn hàng
        $vnp_OrderInfo = $orderInfo;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100; // VNPay yêu cầu số tiền * 100
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );
        
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        
        return $vnp_Url;
    }
    
    /**
     * Kiểm tra tính hợp lệ của callback từ VNPay
     * 
     * @param array $vnpayData Dữ liệu từ VNPay gửi về ($_GET)
     * @return bool Kết quả kiểm tra
     */
    public static function verifyPayment($vnpayData) {
        // Trong chế độ sandbox, luôn trả về true
        if (defined('VNPAY_SANDBOX') && VNPAY_SANDBOX) {
            return true;
        }
        
        $vnp_SecureHash = $vnpayData['vnp_SecureHash'];
        $vnp_HashSecret = VNPAY_HASH_SECRET_KEY;
        
        // Xóa vnp_SecureHash để tạo chuỗi hash mới
        unset($vnpayData['vnp_SecureHash']);
        
        // Sắp xếp dữ liệu theo key
        ksort($vnpayData);
        
        // Tạo chuỗi hash
        $i = 0;
        $hashData = "";
        foreach ($vnpayData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        // So sánh hai chuỗi hash
        return $secureHash == $vnp_SecureHash;
    }
}
?> 