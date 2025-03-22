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
    // Validate if the quote_id exists
    $validateQuery = "SELECT id FROM quotes WHERE id = :id LIMIT 1";
    $validateStmt = $db->prepare($validateQuery);
    $validateStmt->bindParam(':id', $data->id);
    $validateStmt->execute();

    if ($validateStmt->rowCount() === 0) {
        echo json_encode(array(
            'message' => 'No Quotes Found'
        ));
        exit;
    }

    // Assign properties
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Update the quote
    if ($quote->update()) {
        echo json_encode(array(
            'message' => 'updated quote (' . $quote->id . ', ' . $quote->quote . ', ' . $quote->author_id . ', ' . $quote->category_id . ')'
        ));
    } else {
        echo json_encode(array(
            'message' => 'Quote Not Updated'
        ));
    }
} else {
    // Incomplete data
    echo json_encode(array(
        'message' => 'Missing Required Parameters'
    ));
}
?>