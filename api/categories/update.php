<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$categories = new Category($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if 'category_id' exists in the database
$query = 'SELECT id FROM categories WHERE id = :id LIMIT 1';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $data->id);
$stmt->execute();

// If no rows are returned, the category ID doesn't exist
if ($stmt->rowCount() == 0) {
    echo json_encode(array(
        'message' => 'category_id Not Found'));
    exit();
}

// Validate 'category' input
if (!isset($data->category) || empty(trim($data->category))) {
    echo json_encode(array(
        'message' => 'Missing Required Parameters'));
    exit();
}

// Assign data to the category object
$categories->id = htmlspecialchars(strip_tags($data->id));
$categories->category = htmlspecialchars(strip_tags($data->category));

// Update Category
if ($categories->update()) {
    echo json_encode(array(
        'message' => 'updated category (' . $categories->id . ', ' . $categories->category . ')'
    ));
} else {
    echo json_encode(array(
        'message' => 'Category Not Updated'));
}
?>