<?php
include_once('../conn/conn.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $roomId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $roomName = isset($_POST['name']) ? trim($_POST['name']) : '';
    $enabled = isset($_POST['enable']) ? intval($_POST['enable']) : 0;

    if ($roomId > 0 && !empty($roomName)) {
        try {
        
            $query = "UPDATE rooms SET name = :name, enabled = :enabled, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $conn->prepare($query);


            $stmt->bindParam(':name', $roomName, PDO::PARAM_STR);
            $stmt->bindParam(':enabled', $enabled, PDO::PARAM_INT);
            $stmt->bindParam(':id', $roomId, PDO::PARAM_INT);


            if ($stmt->execute()) {
              
                echo json_encode(['status' => 'success', 'message' => 'Room updated successfully.']);
            } else {
                
                echo json_encode(['status' => 'error', 'message' => 'Failed to update room.']);
            }
        } catch (Exception $e) {
            
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
      
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
    }
} else {
   
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

?>
