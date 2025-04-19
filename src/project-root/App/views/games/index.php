<?php require_once ROOT_PATH . '/App/views/layouts/header.php'; ?>

<div class="container">
    <div class="games-page">
        <h1 class="page-title">Game phổ biến</h1>
        
        <!-- Search and Filter Section -->
        <div class="filter-section">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm kiếm game...">
                <button type="button" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="filter-options">
                <select id="platformFilter">
                    <option value="">Tất cả nền tảng</option>
                    <option value="mobile">Mobile</option>
                    <option value="pc">PC</option>
                </select>
                
                <select id="categoryFilter">
                    <option value="">Tất cả thể loại</option>
                    <option value="moba">MOBA</option>
                    <option value="fps">FPS</option>
                    <option value="rpg">RPG</option>
                    <option value="survival">Sinh tồn</option>
                </select>
            </div>
        </div>

        <!-- Games Grid -->
        <div class="games-grid">
            <?php foreach ($games as $game): ?>
            <div class="game-card">
                <div class="game-image">
                    <img src="<?php echo $game['image']; ?>" alt="<?php echo $game['name']; ?>">
                    <div class="game-platform">
                        <i class="fas fa-<?php echo $game['platform'] === 'mobile' ? 'mobile-alt' : 'desktop'; ?>"></i>
                        <?php echo $game['platform'] === 'mobile' ? 'Mobile' : 'PC'; ?>
                    </div>
                </div>
                <div class="game-info">
                    <h3 class="game-name"><?php echo $game['name']; ?></h3>
                    <p class="game-description"><?php echo $game['description']; ?></p>
                    <div class="game-details">
                        <div class="game-size">
                            <i class="fas fa-download"></i>
                            <?php echo $game['size']; ?>
                        </div>
                        <div class="game-version">
                            <i class="fas fa-code-branch"></i>
                            <?php echo $game['version']; ?>
                        </div>
                    </div>
                    <div class="game-actions">
                        <a href="<?php echo $game['download_link']; ?>" class="download-btn" target="_blank">
                            <i class="fas fa-download"></i> Tải game
                        </a>
                        <a href="<?php echo $game['official_site']; ?>" class="official-site-btn" target="_blank">
                            <i class="fas fa-globe"></i> Trang chủ
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.games-page {
    padding: 20px 0;
}

.page-title {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.filter-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.search-box {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px 10px;
    flex: 1;
    max-width: 400px;
}

.search-box input {
    border: none;
    outline: none;
    padding: 8px;
    width: 100%;
}

.search-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
}

.filter-options {
    display: flex;
    gap: 10px;
}

.filter-options select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #fff;
    cursor: pointer;
}

.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 30px;
}

.game-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.game-card:hover {
    transform: translateY(-5px);
}

.game-image {
    height: 200px;
    overflow: hidden;
    position: relative;
}

.game-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.game-platform {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.9em;
    display: flex;
    align-items: center;
    gap: 5px;
}

.game-info {
    padding: 20px;
}

.game-name {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1.3em;
}

.game-description {
    color: #666;
    margin-bottom: 15px;
    line-height: 1.5;
}

.game-details {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    color: #666;
}

.game-size, .game-version {
    display: flex;
    align-items: center;
    gap: 5px;
}

.game-actions {
    display: flex;
    gap: 10px;
}

.download-btn, .official-site-btn {
    flex: 1;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.download-btn {
    background: #3498db;
    color: white;
}

.download-btn:hover {
    background: #2980b9;
}

.official-site-btn {
    background: #f1f1f1;
    color: #333;
}

.official-site-btn:hover {
    background: #e1e1e1;
}

@media (max-width: 768px) {
    .filter-section {
        flex-direction: column;
    }
    
    .search-box {
        max-width: 100%;
    }
    
    .filter-options {
        width: 100%;
        justify-content: space-between;
    }
    
    .games-grid {
        grid-template-columns: 1fr;
    }
    
    .game-actions {
        flex-direction: column;
    }
}
</style>

<?php require_once ROOT_PATH . '/App/views/layouts/footer.php'; ?> 