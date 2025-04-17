<?php
namespace App\Controllers;

class GameController {
    public function index() {
        // Danh sách game phổ biến
        $games = [
            [
                'id' => 1,
                'name' => 'Liên Quân Mobile',
                'image' => '/project-clone-web-buy-acc/src/project-root/public/images/games/lienquan.jpg',
                'description' => 'Liên Quân Mobile là một tựa game MOBA đình đám với lối chơi nhanh, mượt mà và đồ họa đẹp mắt.',
                'platform' => 'mobile',
                'size' => '1.2 GB',
                'version' => '1.48.1.6',
                'download_link' => 'https://lienquan.garena.vn/download',
                'official_site' => 'https://lienquan.garena.vn'
            ],
            [
                'id' => 2,
                'name' => 'PUBG Mobile',
                'image' => '/project-clone-web-buy-acc/src/project-root/public/images/games/pubg.jpg',
                'description' => 'PUBG Mobile là game bắn súng sinh tồn với đồ họa ấn tượng và lối chơi hấp dẫn.',
                'platform' => 'mobile',
                'size' => '1.8 GB',
                'version' => '2.9.0',
                'download_link' => 'https://pubgmobile.com/download',
                'official_site' => 'https://pubgmobile.com'
            ],
            [
                'id' => 3,
                'name' => 'Genshin Impact',
                'image' => '/project-clone-web-buy-acc/src/project-root/public/images/games/genshin.jpg',
                'description' => 'Genshin Impact là game nhập vai thế giới mở với đồ họa anime tuyệt đẹp.',
                'platform' => 'pc',
                'size' => '30 GB',
                'version' => '4.4',
                'download_link' => 'https://genshin.hoyoverse.com/en/download',
                'official_site' => 'https://genshin.hoyoverse.com'
            ],
            [
                'id' => 4,
                'name' => 'Free Fire',
                'image' => '/project-clone-web-buy-acc/src/project-root/public/images/games/freefire.jpg',
                'description' => 'Free Fire là game sinh tồn với lối chơi nhanh, phù hợp với nhiều thiết bị.',
                'platform' => 'mobile',
                'size' => '700 MB',
                'version' => '1.101.1',
                'download_link' => 'https://ff.garena.com/download',
                'official_site' => 'https://ff.garena.com'
            ],
            [
                'id' => 5,
                'name' => 'Valorant',
                'image' => '/project-clone-web-buy-acc/src/project-root/public/images/games/valorant.jpg',
                'description' => 'Valorant là game bắn súng chiến thuật với các nhân vật có kỹ năng đặc biệt.',
                'platform' => 'pc',
                'size' => '20 GB',
                'version' => '8.0',
                'download_link' => 'https://playvalorant.com/en-us/download',
                'official_site' => 'https://playvalorant.com'
            ],
            [
                'id' => 6,
                'name' => 'League of Legends',
                'image' => '/project-clone-web-buy-acc/src/project-root/public/images/games/lol.jpg',
                'description' => 'League of Legends là tựa game MOBA PC nổi tiếng với cộng đồng người chơi lớn.',
                'platform' => 'pc',
                'size' => '15 GB',
                'version' => '14.4',
                'download_link' => 'https://signup.leagueoflegends.com/en-us/signup/index',
                'official_site' => 'https://www.leagueoflegends.com'
            ]
        ];

        require_once BASE_PATH . '/App/views/games/index.php';
    }
} 