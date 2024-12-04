<?php
require_once __DIR__ . '/bootstrap.php';
require_once 'src/Favorite.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

$favoriteModel = new Favorite($pdo);
$favorites = $favoriteModel->getUserFavorites($_SESSION['user_id']);

$page_title = 'My Favorites';
$page_description = 'Your favorite movies collection';
$page_keywords = 'favorites, movies, collection';

include __DIR__ . '/includes/templates/header.php';
?>

<style>
.movie-card {
    margin-bottom: 30px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.movie-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.movie-poster {
    position: relative;
    overflow: hidden;
    border-radius: 4px 4px 0 0;
}

.movie-poster img {
    width: 100%;
    height: auto;
    transition: transform 0.3s ease;
}

.movie-poster:hover img {
    transform: scale(1.05);
}

.movie-info {
    padding: 15px;
}

.movie-title {
    font-size: 18px;
    margin-bottom: 10px;
    font-weight: bold;
    height: 40px;
    overflow: hidden;
}

.movie-overview {
    height: 80px;
    overflow: hidden;
    margin-bottom: 15px;
    color: #666;
}

.button-group {
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

.button-group form {
    flex-grow: 1;
}

.button-group button {
    width: 100%;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    background: #f8f9fa;
    border-radius: 4px;
    margin: 20px 0;
}

.empty-state-icon {
    font-size: 48px;
    color: #dee2e6;
    margin-bottom: 20px;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">My Favorites</h1>
            
            <?php if (empty($favorites)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="glyphicon glyphicon-heart-empty"></i>
                    </div>
                    <h3>No Favorites Yet</h3>
                    <p>Start adding movies to your favorites collection!</p>
                    <a href="index.php" class="btn btn-primary">Browse Movies</a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($favorites as $movie): ?>
                        <div class="col-md-4">
                            <div class="panel panel-default movie-card">
                                <div class="movie-poster">
                                    <img src="<?php echo htmlspecialchars('https://image.tmdb.org/t/p/w500' . $movie['poster_path']); ?>"
                                         class="img-responsive"
                                         alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                </div>
                                <div class="panel-body movie-info">
                                    <h3 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                                    <div class="movie-overview">
                                        <?php echo substr(htmlspecialchars($movie['overview']), 0, 150); ?>...
                                    </div>
                                    <div class="button-group">
                                        <a href="movie.php?tmdb_id=<?php echo $movie['tmdb_id']; ?>" 
                                           class="btn btn-primary">View Details</a>
                                        <form action="favorite.php" method="POST">
                                            <input type="hidden" name="csrf_token" 
                                                   value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                                            <input type="hidden" name="tmdb_id" 
                                                   value="<?php echo htmlspecialchars($movie['tmdb_id']); ?>">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="glyphicon glyphicon-heart"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/templates/footer.php'; ?>
