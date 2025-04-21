<?php
return [
    // Trang chủ
    '' => ['controller' => 'HomeController', 'action' => 'index'],
    'home' => ['controller' => 'HomeController', 'action' => 'index'],
    
    // Game
    'games' => ['controller' => 'GameController', 'action' => 'index'],
    'games/{slug}' => ['controller' => 'GameController', 'action' => 'show'],
    
    // Tài khoản
    'accounts' => ['controller' => 'AccountController', 'action' => 'index'],
    'accounts/{id}' => ['controller' => 'AccountController', 'action' => 'show'],
    'accounts/create' => ['controller' => 'AccountController', 'action' => 'create'],
    'accounts/{id}/edit' => ['controller' => 'AccountController', 'action' => 'edit'],
    
    // Người dùng
    'login' => ['controller' => 'AuthController', 'action' => 'login'],
    'register' => ['controller' => 'AuthController', 'action' => 'register'],
    'logout' => ['controller' => 'AuthController', 'action' => 'logout'],
    'dashboard' => ['controller' => 'UserController', 'action' => 'dashboard'],
    'profile' => ['controller' => 'UserController', 'action' => 'profile'],
    'profile/edit' => ['controller' => 'UserController', 'action' => 'updateProfile'],
    'users/update-profile' => ['controller' => 'UserController', 'action' => 'updateProfile'],
    'users/update-payment' => ['controller' => 'UserController', 'action' => 'updatePayment'],
    'users/change-password' => ['controller' => 'UserController', 'action' => 'changePassword'],
    
    // Thanh toán
    'buy-now/{id}' => ['controller' => 'OrderController', 'action' => 'buyNow'],
    'checkout' => ['controller' => 'OrderController', 'action' => 'checkout'],
    'orders' => ['controller' => 'OrderController', 'action' => 'index'],
    'orders/{id}' => ['controller' => 'OrderController', 'action' => 'show'],
    
    // Tích hợp thanh toán VNPay
    'payment/vnpay/{id}' => ['controller' => 'PaymentController', 'action' => 'vnpay'],
    'payment/vnpay-return' => ['controller' => 'PaymentController', 'action' => 'vnpayReturn'],
    'payment/checkout/{id}' => ['controller' => 'PaymentController', 'action' => 'checkout'],
    'payment/process/{id}' => ['controller' => 'PaymentController', 'action' => 'process'],
    'payment/sandbox/{id}' => ['controller' => 'PaymentController', 'action' => 'sandbox'],
    'payment/sandbox-complete/{id}' => ['controller' => 'PaymentController', 'action' => 'sandboxComplete'],
    
    // Giao dịch
    'transactions' => ['controller' => 'TransactionController', 'action' => 'index'],
    'transactions/{id}' => ['controller' => 'TransactionController', 'action' => 'show'],
    
    // Admin
    'admin' => ['controller' => 'AdminController', 'action' => 'index'],
    'admin/accounts' => ['controller' => 'AdminController', 'action' => 'accounts'],
    'admin/accounts/create' => ['controller' => 'AdminController', 'action' => 'createAccount'],
    'admin/accounts/store' => ['controller' => 'AdminController', 'action' => 'storeAccount'],
    'admin/accounts/edit/{id}' => ['controller' => 'AdminController', 'action' => 'editAccount'],
    'admin/accounts/update/{id}' => ['controller' => 'AdminController', 'action' => 'updateAccount'],
    'admin/accounts/delete/{id}' => ['controller' => 'AdminController', 'action' => 'deleteAccount'],
    'admin/accounts/get-ranks' => ['controller' => 'AdminController', 'action' => 'getRanks'],
    'admin/games' => ['controller' => 'AdminController', 'action' => 'games'],
    'admin/games/create' => ['controller' => 'AdminController', 'action' => 'createGame'],
    'admin/games/store' => ['controller' => 'AdminController', 'action' => 'createGame'],
    'admin/games/edit/{id}' => ['controller' => 'AdminController', 'action' => 'editGame'],
    'admin/games/update/{id}' => ['controller' => 'AdminController', 'action' => 'updateGame'],
    'admin/games/delete/{id}' => ['controller' => 'AdminController', 'action' => 'deleteGame'],
    'admin/ranks' => ['controller' => 'AdminController', 'action' => 'ranks'],
    'admin/orders' => ['controller' => 'AdminController', 'action' => 'orders'],
    'admin/orders/{id}' => ['controller' => 'AdminController', 'action' => 'orderDetail'],
    'admin/orders/status/{id}' => ['controller' => 'AdminController', 'action' => 'updateOrderStatus'],
    'admin/transactions' => ['controller' => 'AdminController', 'action' => 'transactions'],
    'admin/transactions/{id}' => ['controller' => 'AdminController', 'action' => 'transactionDetail'],
    'admin/transactions/{id}/update-status' => ['controller' => 'AdminController', 'action' => 'updateTransactionStatus'],
    'admin/users' => ['controller' => 'AdminController', 'action' => 'users'],
    'admin/users/edit/{id}' => ['controller' => 'AdminController', 'action' => 'editUser'],
    'admin/users/view/{id}' => ['controller' => 'AdminController', 'action' => 'viewUser'],
    'admin/users/delete/{id}' => ['controller' => 'AdminController', 'action' => 'deleteUser'],
]; 