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

     // Validate author_id exists
    $authorQuery = "SELECT id FROM authors WHERE id = :author_id LIMIT 1";
    $authorStmt = $db->prepare($authorQuery);
    $authorStmt->bindParam(':author_id', $data->author_id);
    $authorStmt->execute();

    if ($authorStmt->rowCount() === 0) {
        echo json_encode(array(
            'message' => 'author_id Not Found'
        ));
        exit;
    }

     // Validate category_id exists
    $categoryQuery = "SELECT id FROM categories WHERE id = :category_id LIMIT 1";
    $categoryStmt = $db->prepare($categoryQuery);
    $categoryStmt->bindParam(':category_id', $data->category_id);
    $categoryStmt->execute();

    if ($categoryStmt->rowCount() === 0) {
        echo json_encode(array(
            'message' => 'category_id Not Found'
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
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
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