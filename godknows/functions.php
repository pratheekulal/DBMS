<?php
include 'db.php';

function createUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, password_hash($password, PASSWORD_DEFAULT));
    $stmt->execute();
    $stmt->close();
}

function authenticateUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hash);
    $stmt->fetch();
    $stmt->close();
    return password_verify($password, $hash);
}

function addReview($product_id, $user_id, $rating, $text, $image) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, text, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $product_id, $user_id, $rating, $text, $image);
    $stmt->execute();
    $stmt->close();
}

function getReviews() {
    global $conn;
    $result = $conn->query("SELECT users.username, reviews.rating, reviews.text, reviews.image FROM reviews INNER JOIN users ON reviews.user_id = users.id");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function uploadImage($product_id, $image_path) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO images (product_id, image_path) VALUES (?, ?)");
    $stmt->bind_param("is", $product_id, $image_path);
    $stmt->execute();
    $stmt->close();
}

function getProducts() {
    global $conn;
    $result = $conn->query("SELECT id, name FROM products");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function likeReview($review_id, $user_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO review_likes (review_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $review_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['signup'])) {
    createUser($_POST['username'], $_POST['password']);
    header('Location: signin.html');
    exit();
}

if (isset($_POST['signin'])) {
    if (authenticateUser($_POST['username'], $_POST['password'])) {
        header('Location: review.html');
    } else {
        header('Location: signin.html');
    }
    exit();
}

if (isset($_POST['review'])) {
    $image_path = 'images/' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    addReview($_POST['product'], 1, $_POST['rating'], $_POST['text'], $image_path); // Assuming user_id is 1 for simplicity
    header('Location: review.html');
    exit();
}

if (isset($_POST['upload'])) {
    $image_path = 'images/' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    uploadImage($_POST['product'], $image_path);
    header('Location: upload.html');
    exit();
}

if (isset($_POST['like'])) {
    likeReview($_POST['review_id'], $_POST['user_id']); // Assuming user_id is passed from the form
    header('Location: review.html');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'get_products') {
    header('Content-Type: application/json');
    echo json_encode(['products' => getProducts()]);
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'get_reviews') {
    header('Content-Type: application/json');
    echo json_encode(['reviews' => getReviews()]);
    exit();
}
?>
