<?php 
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate author object
$author = new Author($db);

// Author read query
$result = $author->read();

// Get row count
$num = $result->rowCount();

// Check if any authors
if($num > 0) {
  // author array
  $auth_arr = array();
  $auth_arr['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $auth_item = array(
          'id' => $id,
          'author' => $author
        );

        // Push to "data"
        array_push($auth_arr['data'], $auth_item);
      }

      // Turn to JSON & output
      echo json_encode($auth_arr);

} else {
  // No Author
echo json_encode(array(
'message' => 'No Authors Found')
);
}
?>