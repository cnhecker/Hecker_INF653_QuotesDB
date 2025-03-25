<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$categories = new Category($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if ID is provided
if (!isset($data->id)) {
    echo json_encode(array(
        'message' => 'Missing Required Parameters'));
    exit;
}

// Set ID to DELETE
$categories->id = $data->id;

// Directly try to delete and check affected rows
$query = 'DELETE FROM categories WHERE id = :id';
$stmt = $db->prepare($query);

// Bind the ID
$stmt->bindParam(':id', $categories->id);

// Execute the query
if ($stmt->execute() && $stmt->rowCount() > 0) {
    echo json_encode(array(
        'id' => $categories->id));
} else {
    echo json_encode(array(
        'message' => 'category_id Not Found'));
}
?>