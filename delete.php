<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])) {
    require_once 'config.php';
    $id = (int) $_POST['id'];
    $sql = "DELETE FROM tasks WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        header('Location: index.php');
    } else {
        echo "Ошибка: " . mysqli_error($conn);
        mysqli_close($conn);
    }
} else {
    header('Location: index.php');
    exit();
}
?>