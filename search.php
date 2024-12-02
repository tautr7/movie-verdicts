<?php
require_once __DIR__ . '/bootstrap.php';
require_once 'src/Movie.php';

$page_title = 'Search Results';
$page_description = 'Search results for movies';
$page_keywords = 'search, movies, results';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($query)) {
    header('Location: index.php');
    exit;
}

$movieModel = new Movie($pdo);
$searchResults = $movieModel->searchMovies($query);

include __DIR__ . '/includes/templates/header.php';
?>

<div class="container">
    <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>
    
    <?php if (empty($searchResults)): ?>
        <div class="alert alert-info">
            No movies found matching your search.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($searchResults as $movie): ?>
                <div class="col-sm-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><?php echo htmlspecialchars($movie['title']); ?></div>
                        <div class="panel-body">
                            <?php if ($movie['poster_path']): ?>
                                <img src="<?php echo htmlspecialchars('https://image.tmdb.org/t/p/w500' . $movie['poster_path']); ?>"
                                    class="img-responsive" style="width:100%"
                                    alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            <?php else: ?>
                                <div class="no-poster">No poster available</div>
                            <?php endif; ?>
                        </div>
                        <div class="panel-footer">
                            <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
                            <p><strong>Overview:</strong> <?php echo substr(htmlspecialchars($movie['overview']), 0, 150); ?>...</p>
                            <a href="movie.php?tmdb_id=<?php echo $movie['tmdb_id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/templates/footer.php'; ?>
