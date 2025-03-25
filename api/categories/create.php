<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';
  
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $categories = new Category($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Validate 'category' input
  if (!isset($data->category) || empty(trim($data->category))) {
    echo json_encode(array(
      'message' => 'Missing Required Parameters'));
    exit();
  }

  $categories->category = $data->category;

  // Create Category
  if($categories->create()){
    echo json_encode(array(
      'id' => $categories->id,
      'category' => $categories->category));
  } else{
    echo json_encode(array(
      'message' => 'Category Not Created'));
  }
  ?>