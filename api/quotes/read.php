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

// Instantiate quote object
$quote = new Quote($db);

// Check for query parameters
if (isset($_GET['category_id']) && isset($_GET['author_id'])) {
    // Read quotes by category and author
    $quote->category_id = $_GET['category_id'];
    $quote->author_id = $_GET['author_id'];
    $result = $quote->read_by_author_and_category();
} elseif (isset($_GET['category_id'])) {
    // Read quotes by category
    $quote->category_id = $_GET['category_id'];
    $result = $quote->read_by_category();
} elseif (isset($_GET['author_id'])) {
    // Read quotes by author
    $quote->author_id = $_GET['author_id'];
    $result = $quote->read_by_author();
} else {
    // Read all quotes
    $result = $quote->read();
}

// Get row count
$num = $result->rowCount();

// Check if any quotes
if ($num > 0) {
    // Quote array
    $quotes_arr = array();
    $quotes_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $quote_item = array(
            'id' => $id,
            'quote' => $quote,
            'author_id' => $author_id,
            'category_id' => $category_id
        );

        array_push($quotes_arr['data'], $quote_item);
    }

    // Convert to JSON & output
    echo json_encode($quotes_arr);
} else {
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}
?>





