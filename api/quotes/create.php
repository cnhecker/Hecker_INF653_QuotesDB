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
      // Assign properties
      $quotes->quote = $data->quote;
      $quotes->author_id = $data->author_id;
      $quotes->category_id = $data->category_id;

      // Create Quote
      if($quotes->create()) {
        // Fetch the last inserted ID
        $lastInsertedId = $db->lastInsertId();
          echo json_encode(array(
            'message' => 'created quote (' . $lastInsertedId . ', ' . $quotes->quote . ', ' . $quotes->author_id . ', ' . $quotes->category_id . ')'));
      } else {
          echo json_encode(array(
            'message' => 'Quote Not Created'));
      }
  } else {
      // missing data
      echo json_encode(array(
        'message' => 'Missing Required Parameters'));
  }
?>