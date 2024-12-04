<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once __DIR__ . '/bootstrap.php';
    require_once 'src/Movie.php';
    require_once 'src/Favorite.php';
    require_once 'src/Rating.php';

    if (!isset($_GET['tmdb_id'])) {
        die('Invalid movie ID.');
    }

    $tmdb_id = $_GET['tmdb_id'];
    $movieModel = new Movie($pdo);
    $movie = $movieModel->getMovieWithReviews($tmdb_id);

    if (!$movie) {
        die('Movie not found.');
    }

    $page_title = 'Movie Details';
    include __DIR__ . '/includes/templates/header.php';
?>

<div class="container">
    <div class="row">
        <!-- Left column - Movie Details -->
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                </div>
                <div class="panel-body">
                    <div class="movie-poster">
                        <?php
                        $filename = strtolower(str_replace(' ', '-', $movie['title'])) . '.jpg';
                        $imagePath = '/flix/assets/images/movies/' . $filename;
                        ?>
                        <img src="<?php echo htmlspecialchars($imagePath); ?>"
                             class="img-responsive"
                             alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    </div>
                    <div class="movie-details">
                        <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
                        <p><strong>Overview:</strong> <?php echo htmlspecialchars($movie['overview']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right column - Reviews -->
        <div class="col-sm-6">
            <?php if (isset($_SESSION['user'])): ?>
                <!-- Write Review Panel -->
                <div class="panel panel-primary">
                    <div class="panel-heading">Write a Review</div>
                    <div class="panel-body">
                        <form action="review.php" method="POST">
                            <input type="hidden" name="tmdb_id" value="<?php echo htmlspecialchars($movie['tmdb_id']); ?>">
                            
                            <div class="form-group">
                                <label for="rating">Rating (1-5):</label>
                                <select name="rating" id="rating" class="form-control" required>
                                    <option value="">Select Rating</option>
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="review_text">Your Review:</label>
                                <textarea class="form-control" id="review_text" name="review_text" rows="4" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>

                <!-- Previous reviews -->
                <?php if (!empty($movie['reviews'])): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">Reviews</div>
                        <div class="panel-body">
                            <?php foreach ($movie['reviews'] as $review): ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <strong><?php echo htmlspecialchars($review['email']); ?></strong>
                                        <span class="text-muted"> - <?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                                        <?php if (isset($review['rating'])): ?>
                                            <div class="rating-display pull-right">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">☆</span>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="review-content">
                                        <?php echo htmlspecialchars($review['review_text']); ?>
                                    </div>
                                    <hr>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="alert alert-info">
                    Please <a href="login.php">login</a> to write a review.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.movie-poster img {
    width: 100%;
    height: auto;
    margin-bottom: 15px;
}

.review-item {
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}

.review-header {
    margin-bottom: 10px;
}

.star {
    color: #ddd;
    font-size: 20px;
}

.star.filled {
    color: #ffd700;
}
</style>

<?php
    include __DIR__ . '/includes/templates/footer.php';
} catch (Exception $e) {
    error_log($e->getMessage());
    die('An error occurred. Please try again later.');
}
?>