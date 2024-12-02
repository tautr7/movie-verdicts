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
?>

<div class="container">
    <h1 class="my-4">My Reviews</h1>

    <?php if (empty($reviews)): ?>
        <div class="alert alert-info">
            You haven't written any reviews yet.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($reviews as $review): ?>
                <div class="col-md-12 mb-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Review for <?php echo htmlspecialchars($review['movie_title']); ?>
                                <?php if ($review['rating']): ?>
                                    <span class="pull-right">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                <?php endif; ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php if ($review['poster_path']): ?>
                                        <img src="<?php echo htmlspecialchars('https://image.tmdb.org/t/p/w200' . $review['poster_path']); ?>"
                                            class="img-responsive"
                                            alt="<?php echo htmlspecialchars($review['movie_title']); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-10">
                                    <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                                    <div class="text-muted">
                                        Posted on: <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                    </div>
                                    <div class="mt-2">
                                        <a href="movie.php?tmdb_id=<?php echo $review['tmdb_id']; ?>" class="btn btn-primary btn-sm">View Movie</a>
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

<?php include __DIR__ . '/includes/templates/footer.php'; ?>
