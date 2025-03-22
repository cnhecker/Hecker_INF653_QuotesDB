<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quotes object
$quotes = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if ID is provided
if (!isset($data->id)) {
    echo json_encode(array(
        'message' => 'Missing Required Parameters'));
    exit;
}

// Set ID to DELETE
$quotes->id = $data->id;

// Directly try to delete and check affected rows
$query = 'DELETE FROM quotes WHERE id = :id';
$stmt = $db->prepare($query);

// Bind the ID
$stmt->bindParam(':id', $quotes->id);

// Execute the query
if ($stmt->execute() && $stmt->rowCount() > 0) {
    echo json_encode(array(
        'deleted quote id' => $quotes->id));
} else {
    echo json_encode(array(
        'message' => 'No Quotes Found'));
}
?>