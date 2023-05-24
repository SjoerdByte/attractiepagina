<?php
session_start();
require_once 'admin/backend/conn.php';

$rides = null;

if(isset($_GET['themeland'])&& !empty($_GET['themeland'])) {
    $themeland = $_GET['themeland'];
    $query = "SELECT * FROM rides WHERE themeland = '$themeland'";
    $statement = $conn->prepare($query);
    $statement->execute();
    $rides = $statement->fetchAll(PDO::FETCH_ASSOC);
}
else if(isset($_GET['min_length'])&& !empty($_GET['min_length'])) {
    $min_length = $_GET['min_length'];
    $query = "SELECT * FROM rides WHERE min_length >= '$min_length'";
    $statement = $conn->prepare($query);
    $statement->execute();
    $rides = $statement->fetchAll(PDO::FETCH_ASSOC);
}
else if(isset($_GET['fast_pass'])) {
    if($_GET['fast_pass'] == 'both') {
        $query = "SELECT * FROM rides";
        $statement = $conn->prepare($query);
        $statement->execute();
        $rides = $statement->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $fast_pass = $_GET['fast_pass'];
        $query = "SELECT * FROM rides WHERE fast_pass = :fast_pass";
        $statement = $conn->prepare($query);
        $statement->execute([
            ':fast_pass' => $fast_pass
        ]);
        $rides = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
else {
    $query = "SELECT * FROM rides";
    $statement = $conn->prepare($query);
    $statement->execute();
    $rides = $statement->fetchAll(PDO::FETCH_ASSOC);
}
if(isset($_GET['search'])&& !empty($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM rides WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
    $statement = $conn->prepare($query);
    $statement->execute();
    $rides = $statement->fetchAll(PDO::FETCH_ASSOC);
}
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
            <form action="/" method="get">
                <div class="filter-option">
                    <label for="themeland">Themaland</label>
                    <select name="themeland" id="themeland">
                        <option value="">Geen themaland</option>
                        <?php
                        require_once 'admin/backend/conn.php';
                        $query = "SELECT DISTINCT themeland FROM rides ORDER BY themeland ASC";
                        $statement = $conn->prepare($query);
                        $statement->execute();
                        $themelands = $statement->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($themelands as $themeland) {
                            echo "<option value='" . $themeland['themeland'] . "'>" . ucfirst($themeland['themeland']). "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-option">
                    <label for="min_length">Minimale lengte</label>
                    <select name="min_length" id="min_length">
                        <option value="0" disabled>Geen lengte vereist</option>
                        <?php
                        require_once 'admin/backend/conn.php';
                        $query = "SELECT DISTINCT min_length FROM rides ORDER BY min_length ASC";
                        $statement = $conn->prepare($query);
                        $statement->execute();
                        $min_lengths = $statement->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($min_lengths as $min_length) {

                            if($min_length['min_length'] == 0) echo "<option value='" . $min_length['min_length'] . "'>Geen lengte vereist</option>";
                            else
                                echo "<option value='" . $min_length['min_length'] . "'>" . $min_length['min_length'] . "cm</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-option">
                    <label for="fast_pass">Fast pass</label>
                    <select name="fast_pass" id="fast_pass">
                        <option value="both"> Beide</option>

                        <?php
                        require_once 'admin/backend/conn.php';
                        $query = "SELECT DISTINCT fast_pass FROM rides ORDER BY fast_pass ASC";
                        $statement = $conn->prepare($query);
                        $statement->execute();
                        $fast_passes = $statement->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($fast_passes as $fast_pass) {

                            if($fast_pass['fast_pass'] == 0) echo "<option value='" . $fast_pass['fast_pass'] . "'>Geen fast pass</option>";
                            else
                                echo "<option value='" . $fast_pass['fast_pass'] . "'>Fast pass</option>";
                        }
                        ?>
                    </select>
                <div class="filter-option">
                    <label style="padding-top: 10px" for="search">Zoeken</label>
                    <input type="text" name="search" id="search" placeholder="Search">
                </div>
                <div style="font-size: large; font-weight: bold;">
                    <div class="filter-option">
                        <input type="submit" value="Filter">
                    </div>
                </div>

            </form>
    </aside>
    <main>
        <div class="attracties">
            <?php foreach ($rides as $ride): ?>
                <div class="attractie <?php if($ride['fast_pass']) echo "large"?>">
                    <img src="<?php echo 'img/attracties/' . $ride['img_file']?>" alt="foto van attractie.">
                    <div class="large-split">
                        <div class="div1">
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
                        <div class="div2">
                            <?php if($ride['fast_pass']): ?>
                                <div class="fast-pass">
                                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit.</p>
                                    <p class="fastpass-booking">Boek nu en sla de wachtrij over!</p>
                                    <button><img src="img/Ticket.png" alt="ticket">FAST PASS</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>
</body>
</html>