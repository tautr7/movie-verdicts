<?php
// movie.php

require_once __DIR__ . '/bootstrap.php';

$page_title = 'Movie';
$page_description = 'Movie detail page';
$page_keywords = 'flix, movies, tv shows, streaming';

require_once 'src/Movie.php';

if (!isset($_GET['tmdb_id'])) {
    die('Invalid movie ID.');
}

$tmdb_id = $_GET['tmdb_id'];
$movieModel = new Movie($pdo);
$movie = $movieModel->getMovieById($tmdb_id);

if (!$movie) {
    die('Movie not found.');
}

include __DIR__ . '/includes/templates/header.php';

// Add Font Awesome for star icons
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">';
// Add CSS for star rating system
echo '<style>
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
    }

    .review-content {
        line-height: 1.6;
    }
</style>';
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo htmlspecialchars($movie['title']); ?></div>
                <div class="panel-body">
                    <img src="<?php echo htmlspecialchars('https://image.tmdb.org/t/p/w500' . $movie['poster_path']); ?>"
                        class="img-responsive" style="width:100%"
                        alt="<?php echo htmlspecialchars($movie['title']); ?>">
                </div>
                <div class="panel-footer">
                    <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
                    <p><strong>Overview:</strong> <?php echo htmlspecialchars($movie['overview']); ?></p>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">Add to Favorites</div>
                    <div class="panel-body">
                        <form action="favorite.php" method="POST">
                            <input type="hidden" name="csrf_token"
                                value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                            <input type="hidden" name="tmdb_id" value="<?php echo htmlspecialchars($movie['tmdb_id']); ?>">
                            <input type="hidden" name="media_type" value="movie">
                            <button type="submit" class="btn btn-warning">Add to Favorites</button>
                        </form>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">Submit Your Review</div>
                    <div class="panel-body">
                        <form action="review.php" method="POST">
                            <input type="hidden" name="csrf_token"
                                value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                            <input type="hidden" name="tmdb_id" value="<?php echo htmlspecialchars($movie['tmdb_id']); ?>">
                            <input type="hidden" name="media_type" value="movie">
                            <div class="form-group">
                                <label for="review_text">Your Review:</label>
                                <textarea class="form-control" id="review_text" name="review_text" rows="4"
                                    required></textarea>
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
                <p class="mt-3"><a href="login.php">Login</a> to add favorites and reviews.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-sm-12">
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
                <div class="review-item mb-3">
                    <div class="review-header">
                        <strong><?php echo htmlspecialchars($review['email']); ?></strong>
                        <span class="text-muted"> - <?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                        <?php if ($review['rating']): ?>
                            <span class="pull-right">Rating: <?php echo $review['rating']; ?>/5</span>
                        <?php endif; ?>
                    </div>
                    <div class="review-content mt-2">
                        <?php echo htmlspecialchars($review['review_text']); ?>
                    </div>
                    <hr>
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

<?php
include __DIR__ . '/includes/templates/footer.php';
?>
