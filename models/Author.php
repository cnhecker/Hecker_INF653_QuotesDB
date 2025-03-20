<?php
  class Author {
    // DB Stuff
    private $conn;
    private $table = 'authors';

    // Properties
    public $id;
    public $author;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get authors
    public function read() {
      // Create query
      $query = 'SELECT
        id,
        author
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

  // Get single author
  public function read_single(){
    // Create query
    $query = 'SELECT
      id,
      author
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
      $this->author = $row['author'];
      return true;
    } else {
      return false;
    }
  }

  // Create Author
  public function create() {
    // Create Query
    $query = 'INSERT INTO ' .
      $this->table . '
      (author)
    VALUES (:author)';

  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->author = htmlspecialchars(strip_tags($this->author));

  // Bind data
  $stmt-> bindParam(':author', $this->author);

  // Execute query
  if ($stmt->execute()) {
    // Set the ID to the last inserted ID
    $this->id = $this->conn->lastInsertId();
    return true;
  }
  // On failure
  return false;
}

  // Update Author
  public function update() {
    // Create Query
    $query = 'UPDATE ' .
      $this->table . '
    SET
      author = :author
    WHERE
      id = :id';

  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Bind data
  $stmt-> bindParam(':author', $this->author);
  $stmt-> bindParam(':id', $this->id);

  // Execute query
  if($stmt->execute()) {
    return true;
  }
  // On failure
  return false;
  }

  // Delete Author
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
    // On failure
    return false;
    }
  }
  ?>