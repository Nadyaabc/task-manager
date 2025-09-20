<?php
    require_once "config.php";
    $sql = "SELECT * FROM tasks ORDER BY created_at  DESC";
    $result = mysqli_query($conn, $sql);
    $rows_amount = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='css/styles.css'>
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Exo+2:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <title>Коллекция видеоигр</title>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <h1>Ваши видеоигры</h1>
            <a href="add.php" class="add-button">
                <p>Добавить</p>
            </a>
        </div>
        <main>
            <section class="cards">

               <?php if ($rows_amount > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $title = htmlspecialchars($row['title']);
                        $description = $row['description'] ? htmlspecialchars($row['description']) : 'Описание отсутствует';
                        $platform = $row['platform'] ? htmlspecialchars($row['platform']) : 'Информация отсутствует';
                        $created_at = $row['created_at'] ? date('d.m.Y H:i', strtotime($row['created_at'])) : 'Информация отсутствует';
                        $completion = (int)$row['completion_percentage'];
                        $status = htmlspecialchars($row['status']);
                        $id = (int)$row['id'];
                        ?>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3><?php echo $title; ?></h3>
                                <div class="icons">
                                    <form action="update_status.php" method="POST">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="В планах" <?php echo ($status == 'В планах') ? 'selected' : ''; ?>>В планах</option>
                                            <option value="В процессе" <?php echo ($status == 'В процессе') ? 'selected' : ''; ?>>В процессе</option>
                                            <option value="Завершено" <?php echo ($status == 'Завершено') ? 'selected' : ''; ?>>Завершено</option>
                                        </select>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        
                                    </form>
                                </div>
                                <div class="complete-percentage">
                                    <p><?php echo $completion; ?>%</p>
                                </div>
                            </div>
                            <div class="card-main">
                                <div class="description">
                                    <h3>Описание</h3>
                                    <p><?php echo $description; ?></p>
                                </div>
                                <div class="platform">
                                    <h3>Платформа</h3>
                                    <p><?php echo $platform; ?></p>
                                </div>
                                <div class="created-at">
                                    <h3>Время добавления</h3>
                                    <p><?php echo $created_at; ?></p>
                                </div>
                            </div>
                            <div class="card-buttons">
                                <form action='edit.php' class="edit" method="GET">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="submit" value="Изменить">
                                </form>
                                
                                <!-- КНОПКА УДАЛИТЬ -->
                                <form action='delete.php' method='post' class="delete" 
                                      onsubmit="return confirmDelete(event)">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="submit" value="Удалить">
                                </form>
                                
                                <!-- КНОПКА ЗАВЕРШИТЬ -->
                                <?php if ($status != 'Завершено'): ?>
                                <form action='update_status.php' method='post' class="update-button">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="status" value="Завершено">
                                    <input type="hidden" name="completion_percentage" value="100">
                                    <input type="submit" value="Завершено">
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>Игр пока нет</h3>
                        <p>Нажмите "Добавить", чтобы добавить первую игру в коллекцию</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
        <?php mysqli_close($conn);?>
</body>

</html>