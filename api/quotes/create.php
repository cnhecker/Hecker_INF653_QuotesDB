<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quotes = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check for required fields
if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
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
    $quotes->quote = $data->quote;
    $quotes->author_id = $data->author_id;
    $quotes->category_id = $data->category_id;

    // Create Quote
    if ($quotes->create()) {
        // Fetch the last inserted ID
        $lastInsertedId = $db->lastInsertId();
        echo json_encode(array(
            'message' => 'created quote (' . $lastInsertedId . ', ' . $quotes->quote . ', ' . $quotes->author_id . ', ' . $quotes->category_id . ')'
        ));
    } else {
        echo json_encode(array(
            'message' => 'Quote Not Created'
        ));
    }
} else {
    // Missing data
    echo json_encode(array(
        'message' => 'Missing Required Parameters'
    ));
}
?>