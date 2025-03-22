<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if required fields exist in the database
if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
    // Assign properties
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Update the quote
    if ($quote->update()) {
        echo json_encode(array(
            'message' => 'updated quote (' . $quote->id . ', ' . $quotes->quote . ', ' . $quotes->author_id . ', ' . $quotes->category_id . ')'
        ));
    } else {
        echo json_encode(array(
            'message' => 'Quote Not Updated'));
    }
} else {
    // Incomplete data
    echo json_encode(array(
        'message' => 'Missing Required Parameters'));
}
?>