<?php
// upload-profile-pic.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];
    $userId = $_SESSION['user_id'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($file['name']);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // Update the user's profile picture in the database
        require_once 'db_connection.php';
        $updateQuery = "UPDATE user_profile SET profile_pic = :profile_pic WHERE userid = :userId";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':profile_pic', $targetFile, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
