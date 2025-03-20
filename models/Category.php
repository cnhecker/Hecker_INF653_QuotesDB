<?php
  class Category {
    // DB Stuff
    private $conn;
    private $table = 'categories';

    // Properties
    public $id;
    public $category;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get categories
    public function read() {
      // Create query
      $query = 'SELECT
      id,
      category
    FROM
      ' . $this->table . '
    ORDER BY
      id ASC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();
      return $stmt;
    }

    // Get Single Category
    public function read_single(){
      // Create query
      $query = 'SELECT
            id,
            category
          FROM
            ' . $this->table . '
        WHERE id = :1
        LIMIT 1';


        //Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
          $this->id = $row['id'];
          $this->category = $row['category'];
          return true;
        } else {
          return false;
        }
    }

    public function create() {
      // Create Query
      $query = 'INSERT INTO ' . 
        $this->table . ' 
        (category) 
      VALUES (:category)';
    
      // Prepare Statement
      $stmt = $this->conn->prepare($query);
    
      // Clean data
      $this->category = htmlspecialchars(strip_tags($this->category));
    
      // Bind data
      $stmt->bindParam(':category', $this->category);
    
      // Execute query
      if ($stmt->execute()) {
        // Set the ID to the last inserted ID
        $this->id = $this->conn->lastInsertId();
        return true;
      }
      // On failure
      return false;
  }

    // Update Category
    public function update() {
      // Create Query
      $query = 'UPDATE ' .
        $this->table . '
      SET
        category = :category
      WHERE
        id = :id';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // Bind data
    $stmt-> bindParam(':category', $this->category);
    $stmt-> bindParam(':id', $this->id);

    // Execute query
    if($stmt->execute()) {
      return true;
    }
    // On failure
    return false;
  }

    // Delete Category
    public function delete() {
      // Create query
      $query = 'DELETE FROM 
      ' . $this->table . ' 
      WHERE id = :id';

      // Prepare Statement
      $stmt = $this->conn->prepare($query);

      // Bind Data
      $stmt-> bindParam(':id', $this->id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }
      return false;
    }
  }
?>