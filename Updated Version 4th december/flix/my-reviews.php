<?php
require_once __DIR__ . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'My Reviews';
$page_description = 'View all your movie reviews';
$page_keywords = 'reviews, movies, ratings';

// Get user's reviews
$sql = "SELECT r.*, m.title as movie_title, m.poster_path, m.tmdb_id, rt.rating 
        FROM reviews r 
        JOIN movies m ON r.movie_id = m.tmdb_id 
        LEFT JOIN ratings rt ON r.movie_id = rt.movie_id AND r.user_id = rt.user_id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user']['id']]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/templates/header.php';
// Add Font Awesome for star icons
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">';
?>

<style>
.review-card {
    margin-bottom: 30px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.movie-poster {
    position: relative;
    overflow: hidden;
    border-radius: 4px;
}

.movie-poster img {
    width: 100%;
    height: auto;
    transition: transform 0.3s ease;
}

.movie-poster:hover img {
    transform: scale(1.05);
}

.review-content {
    padding: 15px;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.review-date {
    color: #666;
    font-size: 0.9em;
}

.star-rating {
    color: #ffd700;
}

.star-rating .far {
    color: #ddd;
}

.review-text {
    line-height: 1.6;
    margin-bottom: 15px;
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
            <h1 class="page-header">My Reviews</h1>
            
            <?php if (empty($reviews)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-comment-slash"></i>
                    </div>
                    <h3>No Reviews Yet</h3>
                    <p>You haven't written any reviews yet. Start by finding a movie you've watched and share your thoughts!</p>
                    <a href="index.php" class="btn btn-primary">Browse Movies</a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($reviews as $review): ?>
                        <div class="col-md-6">
                            <div class="panel panel-default review-card">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="movie-poster">
                                                <a href="movie.php?tmdb_id=<?php echo $review['tmdb_id']; ?>">
                                                    <img src="<?php echo htmlspecialchars('https://image.tmdb.org/t/p/w200' . $review['poster_path']); ?>"
                                                         class="img-responsive"
                                                         alt="<?php echo htmlspecialchars($review['movie_title']); ?>">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="review-content">
                                                <div class="review-header">
                                                    <h4><?php echo htmlspecialchars($review['movie_title']); ?></h4>
                                                    <?php if ($review['rating']): ?>
                                                        <div class="star-rating">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="review-date">
                                                    Reviewed on <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                                </div>
                                                <div class="review-text">
                                                    <?php echo htmlspecialchars($review['review_text']); ?>
                                                </div>
                                                <a href="movie.php?tmdb_id=<?php echo $review['tmdb_id']; ?>" class="btn btn-primary">
                                                    View Movie
                                                </a>
                                            </div>
                                        </div>
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
