<?php
include_once('../conn/conn.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Begin a transaction
    $conn->beginTransaction();

    try {
        // Prepare and execute the delete statements for each table
        $stmt = $conn->prepare("DELETE FROM tbl_student WHERE user_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM tbl_attendance WHERE tbl_user_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM files WHERE user_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        echo "success";
    } catch (Exception $e) {
        // Roll back the transaction if something failed
        $conn->rollBack();
        echo "error: " . $e->getMessage();
    }
}
?>
