<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate author object
$authors = new Author($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if 'author_id' exists in the database
$query = 'SELECT id FROM authors WHERE id = :id LIMIT 1';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $data->id);
$stmt->execute();

// If no rows are returned, the author ID doesn't exist
if ($stmt->rowCount() == 0) {
    echo json_encode(array(
        'message' => 'author_id Not Found'));
    exit();
}

// Validate 'author' input
if (!isset($data->author) || empty(trim($data->author))) {
    echo json_encode(array(
        'message' => 'Missing Required Parameters'));
    exit();
}

// Assign data to the author object
$authors->id = htmlspecialchars(strip_tags($data->id));
$authors->author = htmlspecialchars(strip_tags($data->author));

// Update Author
if ($authors->update()) {
    echo json_encode(array(
        'id' => $authors->id,
        'author' => $authors->author
    ));
} else {
    echo json_encode(array(
        'message' => 'Author Not Updated'));
}
?>