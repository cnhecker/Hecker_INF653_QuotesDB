<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quote = new Quote($db);

// Get ID
$quote->id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(array('message' => 'Missing Parameters')));

// Get single quote
if ($quote->read_single()) {
    // Create array
    $quote_arr = array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author' => $quote->author_id,
        'category' => $quote->category_id
    );

    // Convert to JSON & output
    echo json_encode($quote_arr);
} else {
    // Return if quote not found
    echo json_encode(array(
        'message' => 'No Quotes Found')
    );
}
?>