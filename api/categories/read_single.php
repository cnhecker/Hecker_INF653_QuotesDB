<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Category object
$categories = new Category($db);

// Get ID
$categories->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get author details
if ($categories->read_single()) {
    // Create array
    $categories_arr = array(
        'id' => $categories->id,
        'category' => $categories->category,
    );

    // Convert array to JSON & output
    echo json_encode($categories_arr);
} else {
    // Return if author not found
    echo json_encode(array(
        'message'=> 'category_id Not Found'));
}
?>