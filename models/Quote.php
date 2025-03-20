<?php
  class Quote {
    // DB Stuff
    private $conn;
    private $table = 'quotes';

    // Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get quotes
    public function read() {
      // Create query
      $query = 'SELECT 
        id, 
        quote, 
        author_id, 
        category_id 
      FROM 
        ' . $this->table . ' 
      ORDER BY 
        id ASC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      //Execute query
      $stmt->execute();
      return $stmt;
    }

    // Get single quote
    public function read_single() {
      // Create query
      $query = 'SELECT 
        id, 
        quote, 
        author_id, 
        category_id 
      FROM 
        ' . $this->table . ' 
      WHERE id = :id 
      LIMIT 1';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(':id', $this->id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($row) {
        $this->id = $row['id'];
        $this->quote = $row['quote'];
        $this->author_id = $row['author_id'];
        $this->category_id = $row['category_id'];
        return true;
      } else {
        return false;
      }
    }

    // Get quotes by category
    public function read_by_category() {
      // Create query
      $query = 'SELECT 
        id, 
        quote, 
        author_id, 
        category_id 
      FROM 
        ' . $this->table . ' 
      WHERE 
        category_id = :category_id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      //Bind data
      $stmt->bindParam(':category_id', $this->category_id);

      // Execute query
      $stmt->execute();
      return $stmt;
    }

    // Get quotes by author
    public function read_by_author() {
      // Create query
      $query = 'SELECT 
        id, 
        quote, 
        author_id, 
        category_id 
      FROM 
        ' . $this->table . ' 
      WHERE 
        author_id = :author_id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind data
      $stmt->bindParam(':author_id', $this->author_id);

      // Execute query
      $stmt->execute();
      return $stmt;
    }

    // Get quotes by author and category
    public function read_by_author_and_category() {
      // Create query
      $query = 'SELECT 
        id, 
        quote, 
        author_id, 
        category_id 
      FROM 
        ' . $this->table . ' 
      WHERE 
        author_id = :author_id AND category_id = :category_id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      //Bind ID
      $stmt->bindParam(':author_id', $this->author_id);
      $stmt->bindParam(':category_id', $this->category_id);

      // Execute query
      $stmt->execute();
      return $stmt;
    }

    // Create a quote
    public function create() {
      // Create query
      $query = 'INSERT INTO ' . 
        $this->table . ' 
        (quote, author_id, category_id) 
      VALUES 
        (:quote, :author_id, :category_id)';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->quote = htmlspecialchars(strip_tags($this->quote));
      $this->author_id = htmlspecialchars(strip_tags($this->author_id));
      $this->category_id = htmlspecialchars(strip_tags($this->category_id));

      // Bind data
      $stmt->bindParam(':quote', $this->quote);
      $stmt->bindParam(':author_id', $this->author_id);
      $stmt->bindParam(':category_id', $this->category_id);

      // Execute query
      if ($stmt->execute()) {
        // Set the ID to the last inserted ID
        $this->id = $this->conn->lastInsertId();
        return true;
      }
      // On Failure
      return false;
    }

  // Update Quote
  public function update() {
    // Create query
    $query = 'UPDATE ' . 
      $this->table . ' 
    SET 
      quote = :quote, 
      author_id = :author_id, 
      category_id = :category_id 
    WHERE 
      id = :id';

    // Prepare statement
    $stmt = $this->conn->prepare($query);
    $this->quote = htmlspecialchars(strip_tags($this->quote));
    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind data
    $stmt->bindParam(':quote', $this->quote);
    $stmt->bindParam(':author_id', $this->author_id);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':id', $this->id);

    // Execute query
    if ($stmt->execute()) {
      return true;
    }
    // on failure
    return false;
  }

    // Delete a quote
    public function delete() {
      // Create query
      $query = 'DELETE FROM 
        ' . $this->table . ' 
      WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind data
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if ($stmt->execute()) {
        return true;
      }
      return false;
    }
  }
?>