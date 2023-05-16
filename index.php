<?php
session_start();
require_once 'admin/backend/config.php';
?>

<!doctype html>
<html lang="nl">

<head>
    <title>Attractiepagina</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;600;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/main.css">
    <link rel="icon" href="<?php echo $base_url; ?>/favicon.ico" type="image/x-icon" />
</head>

<body>

<?php require_once 'header.php'; ?>
<div class="container content">
    <aside>
        <div class="filter">
            <h2>Filteren</h2>
            <form action="" method="post">
                <div class="filter-option">
                    <label for="themeland">Themaland</label>
                    <select name="themeland" id="themeland">
                        <?php
                        require_once 'admin/backend/conn.php';
                        $query = "SELECT DISTINCT themeland FROM rides ORDER BY themeland ASC";
                        $statement = $conn->prepare($query);
                        $statement->execute();
                        $themelands = $statement->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($themelands as $themeland) {
                            echo "<option value='" . $themeland['themeland'] . "'>" . $themeland['themeland'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-option">
                    <label for="min_length">Minimale lengte</label>
                    <select name="min_length" id="min_length">
                        <option value="0">Geen lengte vereist</option>
                        <?php
                        require_once 'admin/backend/conn.php';
                        $query = "SELECT DISTINCT min_length FROM rides ORDER BY min_length ASC";
                        $statement = $conn->prepare($query);
                        $statement->execute();
                        $min_lengths = $statement->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($min_lengths as $min_length) {
                            echo "<option value='" . $min_length['min_length'] . "'>" . $min_length['min_length'] . "cm</option>";
                        }


                        ?>
                    </select>
                </div>
                <div class="filter-option">
                    <label for="fast_pass">Fast pass</label>
                    <input type="radio" name="fast_pass" id="fast_pass">
                </div>
                <div class="filter-option">
                    <label for="sort">Sorteer op</label>
                    <select name="sort" id="sort">
                        <option value="title">Titel</option>
                        <option value="themeland">Themeland</option>
                        <option value="min_length">Minimale lengte</option>
                    </select>
                </div>
                <div class="filter-option">
                    <input type="submit" value="Filter">
                </div>
            </form>
    </aside>
    <main>
        <?php
        require_once 'admin/backend/conn.php';
        $query = "SELECT * FROM rides";
        $statement = $conn->prepare($query);
        $statement->execute();
        $rides = $statement->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="attracties">
            <?php foreach ($rides as $ride): ?>
                <div class="attractie <?php if($ride['fast_pass']) echo "large"?>">
                    <img src="<?php echo 'img/attracties/' . $ride['img_file']?>" alt="foto van attractie.">
                    <div class="ride-bottom">
                        <p><i><?php echo "<div class='themeland'>" .$ride['themeland']."</div>"?></i></p>
                        <h2><?php echo $ride['title']?></h2>
                        <p><?php echo $ride['description']?></p>
                        <p class="min-length">
                            <i>
                                <?php
                                if($ride['min_length'] == 0) echo "Geen lengte vereist!";
                                else
                                    echo "Minimale lengte: " . $ride['min_length'] . "cm";
                                ?>
                            </i>
                        </p>
                    </div>
                    <?php if($ride['fast_pass']): ?>
                        <div class="fast-pass">
                            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit.</p>
                            <button><img src="img/Ticket.png" alt="ticket">FAST PASS</button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </main>
</div>

</body>

</html>