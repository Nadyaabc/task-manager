<?php
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {

    $id = (int) $_GET["id"];
    $title = "";
    $description = "";
    $status = "";
    $platform = "";
    $completion_percentage = "";

    require_once "config.php";

    $sql = "SELECT * FROM tasks WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = htmlspecialchars($row['title']);
        $description = htmlspecialchars($row['description']);
        $status = htmlspecialchars($row['status']);
        $platform = htmlspecialchars($row['platform']);
        $completion_percentage = (int) $row['completion_percentage'];
    } else {
        mysqli_close($conn);
        exit('Ошибка получения данных о записи');
    }
    mysqli_close($conn);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "config.php";
    $all_fields = ["title", "status", "platform", "completion_percentage"];
    foreach ($all_fields as $field) {
        if (!isset($_POST[$field]) || (is_string($_POST[$field]) && trim($_POST[$field]) === "") || $_POST[$field] == null) {
            exit("Ошибка: Обязательное поле '$field' не заполнено");
        }
    }
    $id = (int) $_POST['id'];
    $completion_percentage = (int) $_POST['completion_percentage'];
    if ($completion_percentage < 0 || $completion_percentage > 100) {
        exit("Ошибка: Процент выполнения должен быть от 0 до 100");
    }
    
    if (isset($_POST['title'])) {
        $title = trim($_POST['title']);
        if (strlen($title) > 255) {
            die("Название не должно превышать 255 символов");
        }
    }

    if (isset($_POST['platform'])) {
        $platform = trim($_POST['platform']);
        if (strlen($platform) > 255) {
            die("Название платформы не должно превышать 255 символов");
        }
    }
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $status = mysqli_real_escape_string($conn, trim($_POST['status']));
    $platform = mysqli_real_escape_string($conn, trim($_POST['platform']));

    $sql = "UPDATE tasks SET title = '$title', description = '$description', status='$status', platform='$platform', completion_percentage='$completion_percentage' WHERE id='$id'";
    if ($result = mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        header("Location: index.php");
        exit();
    } else {
        exit("Произошла ошибка при обновлении таблицы tasks, id=" . $id);
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменение видеоигры</title>
    <link rel="stylesheet" href='css/styles.css'>
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Exo+2:wght@300;400;500;600&display=swap"
        rel="stylesheet">

</head>

<body>
    <div class="wrapper">
        <form action="edit.php" method="POST" class="add-form">
            <h1>Изменение видеоигры</h1>
            <div class="form-item">
                <label for="title">Название: </label>
                <input type="text" name="title" id="title" maxlength="255" value="<?php echo $title ?>" required>
            </div>
            <div class="form-item">
                <label for="description">Описание: </label>
                <input type="text" name="description" id="description" value="<?php echo $description ?>">
            </div>
            <div class="form-item">
                <label for="status">Статус: </label>
                <select name="status" id="status" value="<?php echo $status ?>">
                    <option value="В планах">В планах</option>
                    <option value="В процессе">В процессе</option>
                    <option value="Завершено">Завершено</option>
                </select>
            </div>
            <div class="form-item">
                <label for="platform">Платформа: </label>
                <input type="text" name="platform" id="platform" maxlength="255" value="<?php echo $platform ?>"
                    required>
            </div>
            <div class="form-item">
                <label for="completion_percentage">Процент прохождения: </label>
                <input type="number" name="completion_percentage" id="completion_percentage" required min="0" max="100"
                    value="<?php echo $completion_percentage ?>">
            </div>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <button class="submit" type="submit">Изменить</button>
        </form>
    </div>
</body>

</html>