<?php
include '../components/topbar.php';

require_once '../models/user.php';
require_once '../models/review.php';

User::init();
Review::init();

if (isset($_POST['place_review'])) {
    $stars = min(5, max(1, intval($_POST['stars']))); // Ensure stars are between 1 and 5
    $comment = $_POST['comment'];
    $username = $_SESSION['username'];

    Review::placeReview($stars, $comment, $username);
}

$reviews = Review::getAllReviews();

if (User::isLoggedIn()) {
    $current_user = User::getCurrentUser();
    $username = User::getUsername();
} else {
    $username = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .star-rating {
            display: inline-flex;
            flex-direction: row-reverse;
            gap: 0.3rem;
            margin: 1rem 0;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            color: #ddd;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            color: #ffd700;
        }
        .review-stars {
            color: #ffd700;
        }
        .review-stars .bi-star-fill {
            font-size: 1.2rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mt-5">Reviews</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if ($username): ?>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Write a Review</h5>
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="star-rating">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                            <input type="radio" name="stars" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" <?php echo $i === 5 ? 'required' : ''; ?>>
                                            <label for="star<?php echo $i; ?>" class="bi bi-star-fill"></label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Comment</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" name="place_review">Submit Review</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>You need to be logged in to place a review
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mt-4 mb-4">All Reviews</h2>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if (empty($reviews)): ?>
                            <p class="text-center text-muted">No reviews yet</p>
                        <?php else: ?>
                            <?php foreach ($reviews as $review): ?>
                                <div class="border-bottom mb-3 pb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="review-stars me-2">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star-fill <?php echo $i <= $review['stars'] ? '' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <strong class="text-primary"><?php echo htmlspecialchars($review['username']); ?></strong>
                                    </div>
                                    <p class="mb-0"><?php echo htmlspecialchars($review['comment']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>