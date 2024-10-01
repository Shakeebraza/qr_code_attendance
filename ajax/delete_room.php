<?php
include_once('../conn/conn.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Begin a transaction
    $conn->beginTransaction();

    try {
        // Prepare and execute the delete statement for the rooms table
        $stmt = $conn->prepare("DELETE FROM rooms WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Send a success response
        echo "success";
    } catch (Exception $e) {
        // Roll back the transaction if something failed
        $conn->rollBack();
        echo "error: " . $e->getMessage();
    }
}
?>
