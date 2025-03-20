<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Author object
$authors = new Author($db);

// Get ID
$authors->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get single author
if ($authors->read_single()) {
    // Create array
    $authors_arr = array(
    'id' => $authors->id,
    'author' => $authors->author,
    );

    // Convert to JSON & output
    echo json_encode($authors_arr);
} else {
    // Return if author_id not found
    echo json_encode(array(
    'message'=> 'author_id Not Found'));
}
?>