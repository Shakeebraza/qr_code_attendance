<?php

session_start();

class Fun {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function checksession() {
        return isset($_SESSION['username']) ? 1 : 0;
    }

    public function isAdmin() {
        if ($this->checksession() == 1) {
            if (isset($_SESSION['type'])) {
                if ($_SESSION['type'] == 1) {
                    return 1; 
                } elseif ($_SESSION['type'] == 2) {
                    return 2; 
                }
            }
        }
        return 0;
    }

    public function findfiles($id) {
        if (!empty($id)) {
         
            $stmt = $this->conn->prepare("SELECT file_path, input_type FROM files WHERE user_id = :user_id");
      
            $stmt->bindParam(':user_id', $id);
           
            $stmt->execute();
            
            $filesInDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $filesInDb;
        }
    
        return [];
    }
    public function updateSessionUsername() {
        if (isset($_SESSION['user_id'])) {
            $qry = $this->conn->prepare("SELECT username FROM users WHERE id = :user_id");
            $qry->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $qry->execute();
    
            $data = $qry->fetch(PDO::FETCH_ASSOC);
    
          
            if ($data) {
                $_SESSION['username'] = $data['username'];
            } else {
                
                $_SESSION['username'] = null; 
            }
        } else {
           
            $_SESSION['username'] = null; 
        }
    }
    public function updateSessionProfile() {
        if (isset($_SESSION['user_id'])) {
            $qry = $this->conn->prepare("SELECT profile FROM users WHERE id = :user_id");
            $qry->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $qry->execute();
    
            $data = $qry->fetch(PDO::FETCH_ASSOC);
    
            
            if ($data) {
                $_SESSION['profile'] = $data['profile'];
            } else {
              
                $_SESSION['profile'] = null; 
            }
        } else {
            $_SESSION['profile'] = null; 
        }
    }
    function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
        }
        return $_SESSION['csrf_token'];
    }
    
}



?>
