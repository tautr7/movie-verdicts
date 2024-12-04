<?php
// index.php
require_once __DIR__ . '/bootstrap.php';
$page_title = 'Home';
$page_description = 'Welcome to Flix';
$page_keywords = 'flix, movies, tv shows, streaming';
include __DIR__ . '/includes/templates/header.php';
require_once 'src/Movie.php';
$movie = new Movie($pdo);
$genres = $movie->getAllGenres();
$selectedGenre = isset($_GET['genre']) ? $_GET['genre'] : null;
$movies = $selectedGenre ? $movie->getMoviesByGenre($selectedGenre, 12) : $movie->getAllMovies();
?>

<div class="container">
    <!-- Genre Selection -->
    <div class="row mb-4">
        <div class="col-md-6 col-md-offset-3">
            <form method="GET" action="index.php" class="form-inline text-center">
                <div class="form-group">
                    <label for="genre" class="mr-2">Select Genre:</label>
                    <select name="genre" id="genre" class="form-control" onchange="this.form.submit()">
                        <option value="">All Genres</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?php echo htmlspecialchars($genre['name']); ?>" 
                                <?php echo $selectedGenre === $genre['name'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($genre['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Movies Grid -->
    <div class="row">
        <?php foreach ($movies as $movie): ?>
            <div class="col-sm-4 mb-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a href="movie.php?tmdb_id=<?php echo urlencode($movie['tmdb_id']); ?>"
                                class="text-white" style="text-decoration: none;">
                                <?php echo htmlspecialchars($movie['title']); ?>
                            </a>
                        </h3>
                    </div>
                    <div class="panel-body" style="padding: 0;">
                        <a href="movie.php?tmdb_id=<?php echo urlencode($movie['tmdb_id']); ?>" class="movie-poster">
                            <?php
                            $filename = strtolower(str_replace(' ', '-', $movie['title'])) . '.jpg';
                            $imagePath = '/flix/assets/images/movies/' . $filename;
                            ?>
                            <img src="<?php echo htmlspecialchars($imagePath); ?>"
                                class="img-responsive" style="width:100%"
                                alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                onerror="console.log('Failed to load: ' + this.src);">
                        </a>
                    </div>
                    <div class="panel-footer">
                        <p class="movie-overview">
                            <?php 
                            $overview = htmlspecialchars($movie['overview']);
                            echo strlen($overview) > 150 ? substr($overview, 0, 150) . '...' : $overview;
                            ?>
                        </p>
                        <a href="movie.php?tmdb_id=<?php echo urlencode($movie['tmdb_id']); ?>" 
                            class="btn btn-primary btn-block">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.mb-4 {
    margin-bottom: 20px;
}

.movie-poster {
    display: block;
    position: relative;
    overflow: hidden;
    background-color: #f8f9fa;
    border-radius: 4px 4px 0 0;
}

.movie-poster img {
    width: 100%;
    height: auto;
    transition: transform 0.3s ease;
    display: block;
}

.movie-poster:hover img {
    transform: scale(1.05);
}

.movie-overview {
    height: 100px;
    overflow: hidden;
    margin-bottom: 15px;
}

.text-white {
    color: white !important;
}

.panel-title {
    margin: 0;
    font-size: 16px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.form-inline {
    margin: 20px 0;
    text-align: center;
}

.form-inline label {
    margin-right: 10px;
}

.panel {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.panel-body {
    padding: 0 !important;
}

.panel-footer {
    background-color: #fff;
    padding: 15px;
}
</style>

<?php include __DIR__ . '/includes/templates/footer.php'; ?>