<?php
class Product{
  
    // database connection and table name
    private $conn;
    private $table_name = "menus";
  
    // object properties
    public $idmenus;
    public $menu_name;
    public $diet_type;
    public $created;
    /*public $category_id;
    public $category_name;
    public $created;*/
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
    
        // select all query
        $query = "SELECT
                    idmenus, menu_name, diet_type, created
                FROM
                    " . $this->table_name . "
                ORDER BY
                    created DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create product
    function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    menu_name=:menu_name, diet_type=:diet_type, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->menu_name=htmlspecialchars(strip_tags($this->menu_name));
        $this->diet_type=htmlspecialchars(strip_tags($this->diet_type));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":menu_name", $this->menu_name);
        $stmt->bindParam(":diet_type", $this->diet_type);
        $stmt->bindParam(":created", $this->created);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    // used when filling up the update product form
    function readOne(){
    
        // query to read single record
        $query = "SELECT
                    idmenus , menu_name, diet_type, created
                FROM
                    " . $this->table_name . "
                WHERE
                    menu_name = ?
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->menu_name);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->menu_name = $row['menu_name'];
        $this->diet_type = $row['diet_type'];
    }

    // update the product
    function update(){
    
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    menu_name = :menu_name,
                    diet_type = :diet_type,
                WHERE
                    idmenus = :idmenus";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->menu_name=htmlspecialchars(strip_tags($this->menu_name));
        $this->diet_type=htmlspecialchars(strip_tags($this->diet_type));
        $this->idmenus=htmlspecialchars(strip_tags($this->idmenus));
    
        // bind new values
        $stmt->bindParam(':menu_name', $this->menu_name);
        $stmt->bindParam(':diet_type', $this->diet_type);
        $stmt->bindParam(':idmenus', $this->idmenus);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    
    // delete the product
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE idmenus = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->idmenus=htmlspecialchars(strip_tags($this->idmenus));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->idmenus);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // search products
    function search($keywords){
    
        // select all query
        $query = "SELECT
                    menu_name
                FROM
                    " . $this->table_name . "
                WHERE
                    menu_name like ?
                ORDER BY
                    menu_name DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        /*$stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);*/
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // read products with pagination
    public function readPaging($from_record_num, $records_per_page){
  
        // select query
        $query = "SELECT
                    idmenus, menu_name, diet_type, created
                FROM
                    " . $this->table_name . " p
                ORDER BY created DESC
                LIMIT ?, ?";
      
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
      
        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
      
        // execute query
        $stmt->execute();
      
        // return values from database
        return $stmt;
    }
    
    // used for paging products
    function countMenuName($menu_name){

        //select query
        $query = "SELECT COUNT(*) as total_rows
         FROM " . $this->table_name . "
          WHERE menu_name = :menu_name";
        
        //prepare query statement
        $stmt = $this->conn->prepare( $query );

        //sanitize
        $menu_name = htmlspecialchars(strip_tags($menu_name));

        //bind variable values
        $stmt->bindParam(":menu_name", $menu_name);

        // execute query
        $stmt->execute();

        //fetch associated
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //return row
        return $row['total_rows'];
    }

    public function count(){

        //select query
        $query = "SELECT COUNT(*) as total_rows
         FROM " . $this->table_name . "";
        
        //prepare query statement
        $stmt = $this->conn->prepare( $query );

        // execute query
        $stmt->execute();

        //fetch associated
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //return row
        return $row['total_rows'];
    }
}
?>