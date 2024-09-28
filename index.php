<?php
    session_start();
    $conn = mysqli_connect('localhost', 'root', '', 'sklep');
    $hasAdminPrivilege = false;
    if(isset($_POST['removedItemId'])) {
        $removeItemId = $_POST['removedItemId'];
        if($removeItemId == "-1") {
            $_SESSION['hasAdminPrivilege'] = false;
            $_SESSION['userId'] = null;
            session_destroy();
        } else {
            $sql = 'DELETE FROM `inventory` WHERE id = "'.$removeItemId.'"';
            mysqli_query($conn, $sql);
        }
        $removeItemId = null;
    }
    if(isset($_SESSION['hasAdminPrivilege'])) {
        if($_SESSION['hasAdminPrivilege']) {
            $hasAdminPrivilege = true;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=SUSE:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://kit.fontawesome.com/791dbbf45c.js" crossorigin="anonymous"></script>
    <title>Sklep</title>
</head>
<body>
    <form action="" method="post" style="display: none;" id="removeItemForm" >
        <input type="text" id="itemIdInput" name="removedItemId">
    </form>
    <div class="visibilityToggle" id="visibilityToggle"><span class="material-symbols-outlined" style="font-size: 2rem;">keyboard_arrow_up</span></div>
    <header id="header">
        <div class="logo">
            Sklep
        </div>
        <div class="headerButtons">
            <a href="#"><i class="fa-solid fa-shop"></i></a>
            <a href="cart/cart.php"><i onclick="redirectToCart()" class="fa-solid fa-cart-shopping"></i></a>
            <?php
                if(isset($_SESSION['userId'])) {
                    echo '<i class="fa-solid fa-right-from-bracket" id="signOutBtn"></i>';
                } else {
                    echo '<a href="loginPage/login.php"><i class="fa-solid fa-right-from-bracket"></i></a>';
                }
            ?>
        </div>
    </header>
    <h1 class="welcomeText">Witaj W Naszym Sklepie</h1>
    <h2 class="welcomeText2">Gdzie Sprzedajemy Tylko Najlepsze Części Komputerowe</h2>
    <div class="options">
        <div class="searchBar">
            <input class="inputSearchBar" id="searchbarInput"></input>
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <select class="categories" id="categories">
            <option value="any" style="display: none;">Wybierz kategorię</option>
            <option value="any">Wszystkie</option>
            <?php
                $sql = 'SELECT * FROM categories';
                $result = mysqli_query($conn, $sql);

                while($row = mysqli_fetch_row($result)) {
                    echo '<option value="'.$row[1].'">'.ucwords(str_replace("_", " ", $row[1])).'</option>';
                }
            ?>
        </select>
    </div>
    <div class="items" id="itemsContainer">
        <?php
            $sql = 'SELECT * FROM inventory, categories WHERE inventory.categoryId = categories.id;';
            $result = mysqli_query($conn, $sql);
            $counter = 0;
            while($row = mysqli_fetch_row($result)) {
                echo '<a href="productPage/product.php?productId='.$row[0].'" class="itemContainer" data-category="'.$row[8].'">';
                echo '<div class="item" id="'.$row[0].'">';
                echo '<img src="media/'.$row[5].'">';
                echo '<div class="title">'.$row[1].'</div>';
                echo '<div class="itemRow">';
                if($hasAdminPrivilege) {
                    echo '<div class="trashIcon" id="removeId'.$row[0].'"><i class="fa-solid fa-trash"></i></div>';
                }
                echo '<div class="price">'.$row[2].' zł</div></div>';
                echo '</div>';
                echo '</a>';
                $counter++;
            }

            if($hasAdminPrivilege) {
                echo '<a href="AdminPage/addItem.php"><div class="addItem"><i class="fa-solid fa-plus" style="font-size: 3rem;"></i></div></a>';
            }

            echo '<a><div class="item" style="background-color: transparent; box-shadow: none; padding: 0px; height: 1px;"></div></a>';
            echo '<a><div class="item" style="background-color: transparent; box-shadow: none; padding: 0px; height: 1px;"></div></a>';
            echo '<a><div class="item" style="background-color: transparent; box-shadow: none; padding: 0px; height: 1px;"></div></a>';
        ?>
    </div>
    <script src="index.js"></script>
</body>
</html>