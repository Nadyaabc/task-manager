<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $all_fields = ["title", "status", "platform", "completion_percentage"];
    foreach ($all_fields as $field) {
        if (!isset($_POST[$field]) || (is_string($_POST[$field]) && trim($_POST[$field]) === "") || $_POST[$field] == null) {
            exit("Ошибка: Обязательное поле '$field' не заполнено");
        }
    }

    $title = trim($_POST["title"]);
    if (strlen($title) > 255) {
        exit("Название не должно превышать 255 символов");
    }
    $status = trim($_POST["status"]);
    $platform = trim($_POST["platform"]);
    if (strlen($platform) > 255) {
        exit("Название платформы не должно превышать 255 символов");
    }
    $completion_percentage = (int) $_POST["completion_percentage"];

    if ($completion_percentage < 0 || $completion_percentage > 100) {
        exit("Ошибка: Процент выполнения должен быть от 0 до 100");
    }

    $description = "";
    if (isset($_POST["description"]) && !empty(trim($_POST["description"]))) {
        $description = trim($_POST["description"]);
    }

    require_once "config.php";

    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);
    $status = mysqli_real_escape_string($conn, $status);
    $platform = mysqli_real_escape_string($conn, $platform);

    $sql = "INSERT INTO tasks (title, description, status, platform, completion_percentage) 
            VALUES ('$title', '$description', '$status', '$platform', $completion_percentage)";

    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        header("Location: index.php");
        exit();
    } else {
        mysqli_close($conn);
        exit("Ошибка: " . mysqli_error($conn));
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление видеоигры</title>
    <link rel="stylesheet" href='css/styles.css'>
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Exo+2:wght@300;400;500;600&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <form method="POST" class="add-form">
            <h1>Добавление видеоигры</h1>
            <div class="form-item">
                <label for="title">Название: </label>
                <input type="text" name="title" id="title" required  maxlength="255">
            </div>
            <div class="form-item">
                <label for="description">Описание: </label>
                <input type="text" name="description" id="description">
            </div>
            <div class="form-item">
                <label for="status">Статус: </label>
                <select name="status" id="status">В планах
                    <option value="В планах">В планах</option>
                    <option value="В процессе">В процессе</option>
                    <option value="Завершено">Завершено</option>
                </select>
            </div>
            <div class="form-item">
                <label for="platform">Платформа: </label>
                <input type="text" name="platform" id="platform" required  maxlength="255">
            </div>
            <div class="form-item">
                <label for="completion_percentage">Процент прохождения: </label>
                <input type="number" name="completion_percentage" id="completion_percentage" required min="0" max="100"
                    value="0">
            </div>
            <button class="submit" type="submit">Добавить</button>
        </form>
    </div>
</body>

</html>