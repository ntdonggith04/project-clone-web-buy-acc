<?php
$title = "Nạp tiền - Game Account Store";
require_once __DIR__ . '/layouts/header.php';
?>

<div class="recharge-container">
    <div class="recharge-header">
        <h1>Nạp tiền vào tài khoản</h1>
        <p>Chọn số tiền và phương thức thanh toán bạn muốn</p>
    </div>

    <form action="<?php echo BASE_PATH; ?>/recharge/process" method="POST" id="rechargeForm">
        <div class="amount-section">
            <h2>Chọn số tiền</h2>
            <div class="amount-grid">
                <div class="amount-option" data-amount="50000">
                    <div class="amount">50.000đ</div>
                    <div class="bonus">+0% bonus</div>
                </div>
                <div class="amount-option" data-amount="100000">
                    <div class="amount">100.000đ</div>
                    <div class="bonus">+5% bonus</div>
                </div>
                <div class="amount-option" data-amount="200000">
                    <div class="amount">200.000đ</div>
                    <div class="bonus">+10% bonus</div>
                </div>
                <div class="amount-option" data-amount="500000">
                    <div class="amount">500.000đ</div>
                    <div class="bonus">+15% bonus</div>
                </div>
                <div class="amount-option" data-amount="1000000">
                    <div class="amount">1.000.000đ</div>
                    <div class="bonus">+20% bonus</div>
                </div>
                <div class="amount-option" data-amount="2000000">
                    <div class="amount">2.000.000đ</div>
                    <div class="bonus">+25% bonus</div>
                </div>
            </div>
            <div class="custom-amount">
                <input type="number" name="custom_amount" id="customAmount" placeholder="Hoặc nhập số tiền khác (tối thiểu 10.000đ)" min="10000" step="1000">
            </div>
        </div>

        <div class="payment-section">
            <h2>Chọn phương thức thanh toán</h2>
            <div class="payment-methods">
                <div class="payment-method" data-method="bank">
                    <img src="<?php echo BASE_PATH; ?>/images/payment/bank.png" alt="Bank Transfer">
                    <div class="payment-method-info">
                        <div class="payment-method-name">Chuyển khoản ngân hàng</div>
                        <div class="payment-method-description">Chuyển khoản qua các ngân hàng nội địa</div>
                    </div>
                </div>
                <div class="payment-method" data-method="momo">
                    <img src="<?php echo BASE_PATH; ?>/images/payment/momo.png" alt="Momo">
                    <div class="payment-method-info">
                        <div class="payment-method-name">Ví MoMo</div>
                        <div class="payment-method-description">Thanh toán qua ví điện tử MoMo</div>
                    </div>
                </div>
                <div class="payment-method" data-method="zalopay">
                    <img src="<?php echo BASE_PATH; ?>/images/payment/zalopay.png" alt="ZaloPay">
                    <div class="payment-method-info">
                        <div class="payment-method-name">ZaloPay</div>
                        <div class="payment-method-description">Thanh toán qua ví điện tử ZaloPay</div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="selected_amount" id="selectedAmount">
        <input type="hidden" name="payment_method" id="paymentMethod">

        <div class="submit-section">
            <button type="submit" class="submit-button" id="submitButton" disabled>
                Tiến hành thanh toán
            </button>
        </div>
    </form>
</div>

<style>
.recharge-container {
    max-width: 800px;
    margin: 100px auto 40px;
    padding: 20px;
}

.recharge-header {
    text-align: center;
    margin-bottom: 40px;
}

.recharge-header h1 {
    color: #333;
    font-size: 2rem;
    margin-bottom: 10px;
}

.recharge-header p {
    color: #666;
    font-size: 1.1rem;
}

.amount-section, .payment-section {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.amount-section h2, .payment-section h2 {
    color: #333;
    margin-bottom: 20px;
    font-size: 1.5rem;
}

.amount-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.amount-option {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.amount-option:hover {
    border-color: #3498db;
    transform: translateY(-2px);
}

.amount-option.selected {
    border-color: #3498db;
    background: rgba(52, 152, 219, 0.1);
}

.amount-option .amount {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.amount-option .bonus {
    font-size: 0.9rem;
    color: #e74c3c;
}

.custom-amount input {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s;
}

.custom-amount input:focus {
    border-color: #3498db;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.payment-method {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 10px;
}

.payment-method:hover {
    border-color: #3498db;
    transform: translateY(-2px);
}

.payment-method.selected {
    border-color: #3498db;
    background: rgba(52, 152, 219, 0.1);
}

.payment-method img {
    width: 40px;
    height: 40px;
    object-fit: contain;
}

.payment-method-info {
    flex-grow: 1;
}

.payment-method-name {
    font-weight: bold;
    color: #333;
    margin-bottom: 4px;
}

.payment-method-description {
    font-size: 0.9rem;
    color: #666;
}

.submit-section {
    margin-top: 30px;
    text-align: center;
}

.submit-button {
    background: #3498db;
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 25px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s;
}

.submit-button:hover {
    background: #2980b9;
    transform: translateY(-2px);
}

.submit-button:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

@media (max-width: 768px) {
    .recharge-container {
        padding: 15px;
        margin-top: 80px;
    }

    .amount-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .payment-methods {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountOptions = document.querySelectorAll('.amount-option');
    const customAmount = document.getElementById('customAmount');
    const paymentMethods = document.querySelectorAll('.payment-method');
    const selectedAmountInput = document.getElementById('selectedAmount');
    const paymentMethodInput = document.getElementById('paymentMethod');
    const submitButton = document.getElementById('submitButton');

    function updateSubmitButton() {
        const hasAmount = selectedAmountInput.value !== '';
        const hasPaymentMethod = paymentMethodInput.value !== '';
        submitButton.disabled = !(hasAmount && hasPaymentMethod);
    }

    // Handle amount selection
    amountOptions.forEach(option => {
        option.addEventListener('click', () => {
            amountOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            customAmount.value = '';
            selectedAmountInput.value = option.dataset.amount;
            updateSubmitButton();
        });
    });

    // Handle custom amount input
    customAmount.addEventListener('input', () => {
        amountOptions.forEach(opt => opt.classList.remove('selected'));
        selectedAmountInput.value = customAmount.value;
        updateSubmitButton();
    });

    // Handle payment method selection
    paymentMethods.forEach(method => {
        method.addEventListener('click', () => {
            paymentMethods.forEach(m => m.classList.remove('selected'));
            method.classList.add('selected');
            paymentMethodInput.value = method.dataset.method;
            updateSubmitButton();
        });
    });

    // Form submission
    document.getElementById('rechargeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const amount = selectedAmountInput.value;
        const method = paymentMethodInput.value;
        
        if (!amount || !method) {
            alert('Vui lòng chọn số tiền và phương thức thanh toán');
            return;
        }

        // Here you would typically make an AJAX call to your backend
        // For now, we'll just submit the form
        this.submit();
    });
});
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?> 