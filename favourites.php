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
include __DIR__ . '/includes/templates/header.php';
?>

<div class="container">
    <h1 class="my-4">My Favorites</h1>
    
    <?php if (!empty($favorites)): ?>
        <div class="row">
            <?php foreach ($favorites as $movie): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars('https://image.tmdb.org/t/p/w500' . $movie['poster_path']); ?>"
                            class="card-img-top"
                            alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($movie['overview']), 0, 100); ?>...</p>
                            <a href="movie.php?tmdb_id=<?php echo $movie['tmdb_id']; ?>" class="btn btn-primary">View Details</a>
                            <form action="favorite.php" method="POST" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                                <input type="hidden" name="tmdb_id" value="<?php echo htmlspecialchars($movie['tmdb_id']); ?>">
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>You haven't added any favorites yet.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/templates/header.php'; ?>
