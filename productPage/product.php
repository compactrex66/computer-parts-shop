<?php
    session_start();

    if(isset($_GET['productId'])) { 
        $productId = $_GET['productId'];
    } else {
        header("Location: ../index.php");
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
    <form action="../index.php" method="post" style="display: none;" id="removeItemForm" >
        <input type="text" id="itemIdInput" name="removedItemId">
    </form>
    <div class="visibilityToggle" id="visibilityToggle"><span class="material-symbols-outlined" style="font-size: 2rem;">keyboard_arrow_up</span></div>
    <header id="header">
        <div class="logo">
            Sklep
        </div>
        <div class="headerButtons">
            <a href="../index.php"><i class="fa-solid fa-shop"></i></a>
            <a href="../cart/cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
            <?php
                if(isset($_SESSION['userId'])) {
                    echo '<i class="fa-solid fa-right-from-bracket" id="signOutBtn"></i>';
                } else {
                    echo '<a href="../loginPage/login.php"><i class="fa-solid fa-right-from-bracket"></i></a>';
                }
            ?>
        </div>
    </header>
    <form action="../cart/cart.php" method="post" id="productForm" style="display: none;">
        <input type="number" name="productId" id="productIdInput">
        <input type="number" name="quantity" id="quantityInput">
        <input type="submit">
    </form>
    <div class="itemPage">
        <div class="row">
            <?php
                $conn = mysqli_connect('localhost', 'root', '', 'sklep');
                $sql = 'select * from inventory where id='.$productId.'';
                $result =  mysqli_fetch_row(mysqli_query($conn, $sql));

                echo '<img src="../media/'.$result[5].'">';
            ?>
            <div class="buyPanel">
                <?php echo $result[2]." zł"; echo "<span id=productId style='display: none;'>".$result[0]."</span>" ?>
                <div class="quantity">
                    <div class="quantityChoice"><button id="decreaseQuantity"><</button> <span id="quantity">1</span> <button id="increaseQuantity">></button></div>
                    <div class="quantityText">ilość <span>|</span> <?php echo "<span id='inStock'>".$result[6]."</span>".' W magazynie' ?> </div>
                </div>
                <button id="addToCartBtn">Dodaj do koszyka</button>
            </div>
        </div>
        <div class="itemDescription">
            <h1>Opis</h1>
            <?php echo $result[3]; ?>
        </div>
    </div>
    <script src="../index.js"></script>
</body>
</html>