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

<style>
.movie-card {
    margin-bottom: 30px;
    transition: transform 0.2s;
}

.movie-card:hover {
    transform: translateY(-5px);
}

.poster-container {
    position: relative;
    overflow: hidden;
}

.poster-container img {
    width: 100%;
    transition: transform 0.3s;
}

.poster-container:hover img {
    transform: scale(1.05);
}

.no-poster {
    height: 300px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.movie-info {
    height: 100px;
    overflow: hidden;
    margin-bottom: 15px;
}

.search-stats {
    color: #6c757d;
    margin-bottom: 20px;
}

.panel-footer {
    background-color: #f8f9fa;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Search Results</h1>
            <div class="search-stats">
                Found <?php echo count($searchResults); ?> results for "<?php echo htmlspecialchars($query); ?>"
            </div>
            
            <?php if (empty($searchResults)): ?>
                <div class="alert alert-info">
                    <i class="glyphicon glyphicon-info-sign"></i>
                    No movies found matching your search. Try different keywords.
                    <div class="mt-3">
                        <a href="index.php" class="btn btn-primary">Browse All Movies</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($searchResults as $movie): ?>
                        <div class="col-sm-4">
                            <div class="panel panel-primary movie-card">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                                </div>
                                <div class="panel-body poster-container">
                                    <?php if ($movie['poster_path']): ?>
                                        <img src="<?php echo htmlspecialchars('https://image.tmdb.org/t/p/w500' . $movie['poster_path']); ?>"
                                            class="img-responsive"
                                            alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                    <?php else: ?>
                                        <div class="no-poster">
                                            <i class="glyphicon glyphicon-film"></i>
                                            No poster available
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="movie-info">
                                        <?php if ($movie['release_date']): ?>
                                            <p><strong>Release Date:</strong> 
                                                <?php echo date('F j, Y', strtotime($movie['release_date'])); ?>
                                            </p>
                                        <?php endif; ?>
                                        <p><strong>Overview:</strong> 
                                            <?php echo substr(htmlspecialchars($movie['overview']), 0, 150); ?>...
                                        </p>
                                    </div>
                                    <a href="movie.php?tmdb_id=<?php echo $movie['tmdb_id']; ?>" 
                                        class="btn btn-primary btn-block">
                                        <i class="glyphicon glyphicon-info-sign"></i> View Details
                                    </a>
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
