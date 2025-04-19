// Game routes
$router->get('/games', 'GameController@index');
$router->get('/games/update-images', 'GameController@updateImages');

// Admin Game routes
$router->get('/admin/games', 'Admin\GameController@index');
$router->get('/admin/games/edit/{id}', 'Admin\GameController@edit');
$router->post('/admin/games/update/{id}', 'Admin\GameController@update'); 