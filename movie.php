<?php
// movie.php

require_once __DIR__ . '/bootstrap.php';

$page_title = 'Movie';
$page_description = 'Movie detail page';
$page_keywords = 'flix, movies, tv shows, streaming';

require_once 'src/Movie.php';
require_once 'src/Favorite.php';

if (!isset($_GET['tmdb_id'])) {
    die('Invalid movie ID.');
}

$tmdb_id = $_GET['tmdb_id'];
$movieModel = new Movie($pdo);
$movie = $movieModel->getMovieWithReviews($tmdb_id);

if (!$movie) {
    die('Movie not found.');
}

include __DIR__ . '/includes/templates/header.php';

// Add Font Awesome for star icons
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">';
?>
<style>
    .star-rating {
        direction: rtl;
        display: inline-block;
        padding: 20px;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        color: #bbb;
        cursor: pointer;
        font-size: 24px;
        padding: 0 3px;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #f90;
    }

    .review-item {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .review-header {
        margin-bottom: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .review-content {
        line-height: 1.6;
        padding: 10px 0;
    }

    .movie-poster {
        position: relative;
        overflow: hidden;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .movie-poster img {
        width: 100%;
        transition: transform 0.3s ease;
    }

    .movie-poster:hover img {
        transform: scale(1.05);
    }

    .movie-details {
        margin-top: 20px;
    }

    .panel {
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .panel-heading {
        font-weight: bold;
    }

    .favorite-btn {
        margin-bottom: 15px;
    }

    .average-rating {
        font-size: 18px;
        margin-left: 10px;
    }

    .text-warning {
        color: #f90;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                </div>
                <div class="panel-body">
                    <div class="movie-poster">
                        <img src="<?php echo htmlspecialchars('https://image.tmdb.org/t/p/w500' . $movie['poster_path']); ?>"
                            class="img-responsive"
                            alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    </div>
                    <div class="movie-details">
                        <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
                        <p><strong>Overview:</strong> <?php echo htmlspecialchars($movie['overview']); ?></p>
                        <?php if (isset($movie['average_rating'])): ?>
                            <p>
                                <strong>Average Rating:</strong>
                                <span class="average-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= round($movie['average_rating']) ? 'text-warning' : 'text-muted'; ?>"></i>
                                    <?php endfor; ?>
                                    (<?php echo number_format($movie['average_rating'], 1); ?>)
                                </span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-8">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">Favorites</div>
                    <div class="panel-body">
                        <form action="favorite.php" method="POST" class="favorite-btn">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                            <input type="hidden" name="tmdb_id" value="<?php echo htmlspecialchars($movie['tmdb_id']); ?>">
                            <?php 
                            $favoriteModel = new Favorite($pdo);
                            $isFavorite = $favoriteModel->isFavorite($_SESSION['user']['id'], $movie['tmdb_id']);
                            ?>
                            <button type="submit" class="btn btn-lg <?php echo $isFavorite ? 'btn-danger' : 'btn-warning'; ?> btn-block">
                                <i class="fas <?php echo $isFavorite ? 'fa-heart-broken' : 'fa-heart'; ?>"></i>
                                <?php echo $isFavorite ? 'Remove from Favorites' : 'Add to Favorites'; ?>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">Submit Your Review</div>
                    <div class="panel-body">
                        <form action="review.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                            <input type="hidden" name="tmdb_id" value="<?php echo htmlspecialchars($movie['tmdb_id']); ?>">
                            <div class="form-group">
                                <label for="review_text">Your Review:</label>
                                <textarea class="form-control" id="review_text" name="review_text" rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Your Rating:</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4"><label for="star4"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3"><label for="star3"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2"><label for="star2"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1"><label for="star1"><i class="fas fa-star"></i></label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <a href="login.php">Login</a> to add favorites and reviews.
                </div>
            <?php endif; ?>

            <div class="panel panel-info">
                <div class="panel-heading">
                    User Reviews 
                    <?php if ($movie['average_rating']): ?>
                        <span class="pull-right">Average Rating: <?php echo number_format($movie['average_rating'], 1); ?> / 5</span>
                    <?php endif; ?>
                </div>
                <div class="panel-body">
                    <?php if (!empty($movie['reviews'])): ?>
                        <?php foreach ($movie['reviews'] as $review): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <strong><?php echo htmlspecialchars($review['email']); ?></strong>
                                    <span class="text-muted"> - <?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                                    <?php if ($review['rating']): ?>
                                        <span class="pull-right">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="review-content">
                                    <?php echo htmlspecialchars($review['review_text']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No reviews yet. Be the first to review this movie!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/templates/footer.php'; ?>
