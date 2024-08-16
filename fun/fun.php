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
    public function GetAttendance() {
        try {
            $getatt = $this->conn->prepare("SELECT * FROM tbl_attendance WHERE DATE(time_in) = :today");
            $today = date("Y-m-d");
            $getatt->bindParam(":today", $today);
            $getatt->execute();
            $attendanceRecords = $getatt->fetchAll(PDO::FETCH_ASSOC);
            $recordCount = $getatt->rowCount();
            return [
                'count' => $recordCount,
                'records' => $attendanceRecords
            ];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [
                'count' => 0,
                'records' => []
            ];
        }
    }
    public function GetAllUser() {
        try {
            $getatt = $this->conn->prepare("SELECT * FROM users");
            $getatt->execute();
            $attendanceRecords = $getatt->fetchAll(PDO::FETCH_ASSOC);
            $recordCount = $getatt->rowCount();
            return [
                'count' => $recordCount,
                'records' => $attendanceRecords
            ];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [
                'count' => 0,
                'records' => []
            ];
        }
    }

    public function FindUser($id) {
        try {
            $getatt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
            $getatt->bindParam(":id", $id, PDO::PARAM_INT);
            $getatt->execute();
            $userRecords = $getatt->fetchAll(PDO::FETCH_ASSOC);
    
            return [
                'count' => count($userRecords),
                'records' => $userRecords
            ];
        } catch (PDOException $e) {
            // Log the error message
            error_log("Error: " . $e->getMessage());
            
            return [
                'count' => 0,
                'records' => []
            ];
        }
    }

    public function GetUserFiles($id) {
        try {
            $getatt = $this->conn->prepare("SELECT filename, file_path, input_type FROM files WHERE user_id = :id");
            $getatt->bindParam(":id", $id, PDO::PARAM_INT);
            $getatt->execute();
            $userRecords = $getatt->fetchAll(PDO::FETCH_ASSOC);
    
            return [
                'count' => count($userRecords),
                'records' => $userRecords
            ];
        } catch (PDOException $e) {
            // Log the error message
            error_log("Error: " . $e->getMessage());
            
            return [
                'count' => 0,
                'records' => []
            ];
        }
    }
    
    public function GetUserAttendance($date = NULL, $name = NULL, $email = NULL, $time = NULL) {
        try {
            // Base query
            $query = "SELECT 
                                users.id,
                                users.username,
                                users.email,
                                users.profile,
                                attendance.time_in,
                                attendance.tbl_attendance_id,
                                attendance.qr_code,
                                attendance.room,
                                student.worktype
                            FROM 
                                users
                            JOIN 
                                tbl_attendance attendance ON users.id = attendance.tbl_user_id
                            JOIN
                                tbl_student student ON attendance.tbl_student_id = student.tbl_student_id";
        
            // Initialize conditions and parameters
            $conditions = [];
            $params = [];
        
            // Add conditions based on input parameters
            if (!empty($date)) {
                $conditions[] = "DATE(attendance.time_in) = :date";
                $params[':date'] = $date; // Ensure $date is in YYYY-MM-DD format
            }
            if (!empty($name)) {
                $conditions[] = "users.username LIKE :name";
                $params[':name'] = "%$name%";
            }
            if (!empty($email)) {
                $conditions[] = "users.email LIKE :email";
                $params[':email'] = "%$email%";
            }
            if (!empty($time)) {
                $conditions[] = "TIME(attendance.time_in) = :time";
                $params[':time'] = $time;
            }
        
            // Append conditions to query if any
            if (count($conditions) > 0) {
                $query .= " WHERE " . implode(' AND ', $conditions);
            }
        
            // Debugging: Output the query and parameters
            error_log("SQL Query: " . $query);
            error_log("Parameters: " . print_r($params, true));
        
            $stmt = $this->conn->prepare($query);
        
            // Bind parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
        
            $stmt->execute();
            $userRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Debugging: Output the result
            error_log("Query Result: " . print_r($userRecords, true));
        
            return [
                'count' => count($userRecords),
                'records' => $userRecords
            ];
        } catch (PDOException $e) {
            // Log the error message
            error_log("Error: " . $e->getMessage());
        
            return [
                'count' => 0,
                'records' => []
            ];
        }
    }
    
    
    
    public function GetUserAdvanceAttendance($fromdate = NULL, $todate = NULL, $name = NULL, $email = NULL, $room = NULL) {
        try {
            // Base query
            $query = "SELECT 
                          users.id,
                          users.username,
                          users.email,
                          users.profile,
                          attendance.time_in,
                          attendance.tbl_attendance_id,
                          attendance.qr_code,
                          attendance.room
                      FROM 
                          users
                      JOIN 
                          tbl_attendance attendance ON users.id = attendance.tbl_user_id";
        
            // Initialize conditions and parameters
            $conditions = [];
            $params = [];
        
            // Add conditions based on input parameters
            if (!empty($fromdate)) {
                $conditions[] = "DATE(attendance.time_in) >= :fromdate";
                $params[':fromdate'] = $fromdate;
            }
            if (!empty($todate)) {
                $conditions[] = "DATE(attendance.time_in) <= :todate";
                $params[':todate'] = $todate;
            }
            if (!empty($name)) {
                $conditions[] = "users.username LIKE :name";
                $params[':name'] = "%$name%";
            }
            if (!empty($email)) {
                $conditions[] = "users.email LIKE :email";
                $params[':email'] = "%$email%";
            }
            if (!empty($room)) {
                $conditions[] = "attendance.room LIKE :room";
                $params[':room'] = "%$room%";
            }
        
            // Append conditions to query if any
            if (count($conditions) > 0) {
                $query .= " WHERE " . implode(' AND ', $conditions);
            }
        
            // Debugging: Output the query and parameters
            error_log("SQL Query: " . $query);
            error_log("Parameters: " . print_r($params, true));
        
            $stmt = $this->conn->prepare($query);
        
            // Bind parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
        
            $stmt->execute();
            $userRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Debugging: Output the result
            error_log("Query Result: " . print_r($userRecords, true));
        
            return [
                'count' => count($userRecords),
                'records' => $userRecords
            ];
        } catch (PDOException $e) {
            // Log the error message
            error_log("Error: " . $e->getMessage());
        
            return [
                'count' => 0,
                'records' => []
            ];
        }
    }
        public function GetRooms() {
            try {
        
                
                $query = "SELECT * FROM rooms";
                $statement = $this->conn->prepare($query);
                
                $statement->execute();
                
                $rooms = $statement->fetchAll(PDO::FETCH_ASSOC);
                
                return json_encode([
                    'status' => 'success',
                    'data' => $rooms
                ]);
            } catch (PDOException $e) {
                return json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
            }
    
                public function GetTodayAttendance() {
                    try {
                  
                        $todayDate = date('Y-m-d');
                        
                    
                        $query = "SELECT 
                                users.id,
                                users.username,
                                users.email,
                                users.profile,
                                attendance.time_in,
                                attendance.tbl_attendance_id,
                                attendance.qr_code,
                                attendance.room,
                                student.worktype
                            FROM 
                                users
                            JOIN 
                                tbl_attendance attendance ON users.id = attendance.tbl_user_id
                            JOIN
                                tbl_student student ON attendance.tbl_student_id = student.tbl_student_id
                            WHERE 
                                DATE(attendance.time_in) = :todayDate";
                        
                        $stmt = $this->conn->prepare($query);
                        
                      
                        $stmt->bindValue(':todayDate', $todayDate);
                        
                        $stmt->execute();
                        $userRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
            
                        return json_encode([
                            'count' => count($userRecords),
                            'records' => $userRecords
                        ]);
                    } catch (PDOException $e) {
                    
                        error_log("Error: " . $e->getMessage());
                        
                    
                        return json_encode([
                            'count' => 0,
                            'records' => [],
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                public function checkuserworker($id) {
                    $todayDate = date('Y-m-d');
                    
                    // Prepare the query to fetch both count and tbl_student_id
                    $query = "SELECT tbl_student_id, COUNT(*) as record_count 
                              FROM tbl_student 
                              WHERE user_id = :userId AND DATE(date) = :todayDate 
                              GROUP BY tbl_student_id";
                    $stmt = $this->conn->prepare($query);
                    
                    // Bind parameters
                    $stmt->bindParam(':userId', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':todayDate', $todayDate, PDO::PARAM_STR);
                    
                    $stmt->execute();
                    
                    // Fetch the result
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Return an array with count and tbl_student_id, or null if not found
                    return $result !== false ? [
                        'tbl_student_id' => $result['tbl_student_id'],
                        'record_count' => $result['record_count']
                    ] : [
                        'tbl_student_id' => null,
                        'record_count' => 0
                    ];
                }

                public function CheckUserWorkerNew($id) {
                    $todayDate = date('Y-m-d');
                    $query = "SELECT tbl_student_id, COUNT(*) as record_count FROM tbl_student 
                              WHERE user_id = :userId AND DATE(date) = :todayDate
                              GROUP BY user_id";
                    $stmt = $this->conn->prepare($query);
                    
                    $stmt->bindParam(':userId', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':todayDate', $todayDate, PDO::PARAM_STR);
                    
                    $stmt->execute();
                    
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($result) {
                        return [
                            'tbl_student_id' => $result['tbl_student_id'],
                            'record_count' => $result['record_count']
                        ];
                    } else {
                        return [
                            'user_id' => null,
                            'record_count' => 0
                        ];
                    }
                }
                

                public function checkAssignRoom($id) {
                    // Get user and record count data
                    $usertimedata = $this->CheckUserWorkerNew($id);
                    $todayDate = date('Y-m-d');
                    
                    // Check if user_id is valid
                    if ($usertimedata['tbl_student_id'] !== null) {
                        // Prepare the query to fetch both tbl_student_id and room
                        $query = "SELECT tbl_student_id, room FROM tbl_attendance WHERE tbl_student_id = :userId AND DATE(time_in) = :todayDate LIMIT 1";
                        $stmt = $this->conn->prepare($query);
                        
                        // Bind parameters
                        $stmt->bindParam(':userId', $usertimedata['tbl_student_id'], PDO::PARAM_INT);
                        $stmt->bindParam(':todayDate', $todayDate, PDO::PARAM_STR);
                        
                        $stmt->execute();
                        
                        // Fetch the result
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        // Return the result or null if not found
                        return $result !== false ? $result : null;
                    } else {
                        // Return null if user_id is not valid
                        return null;
                    }
                }
                
                
                


    
    
}



?>
