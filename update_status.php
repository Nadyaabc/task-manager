<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST["id"];
    $new_status = htmlspecialchars($_POST["status"]);
    require_once "config.php";
    $sql_check = "SELECT status FROM tasks WHERE id='$id'";
    $result_check = mysqli_query($conn, $sql_check);
    $current_status = mysqli_fetch_assoc($result_check)['status'];
    if ($new_status !== $current_status) {
        $sql = "UPDATE tasks SET status='$new_status' WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            mysqli_close($conn);
            header("Location: index.php");
            exit();
        } else {
            echo "Ошибка: " . mysqli_error($conn);
            exit();
        }
    } else {
        mysqli_close($conn);
        header("Location: index.php");
        exit();
    }
}
?>