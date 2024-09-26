<?php
    session_start();
    if(!isset($_SESSION['itemsInCart'])) {
        $_SESSION['itemsInCart'] = array();
    }

    $conn = mysqli_connect('localhost', 'root', '', 'sklep');
    $alreadyInCart = false;
    $counter = 0;

    if(isset($_POST['productId']) && isset($_POST['quantity'])) {
        $productId = $_POST['productId'];
        $quantity = $_POST['quantity'];
        
        foreach($_SESSION['itemsInCart'] as $product) {
            if($product['id'] == $productId) {
                $alreadyInCart = true;
                $_SESSION['itemsInCart'][$counter]['quantity'] += $quantity;
                $counter = 0;
                break;
            }
            $counter++;
        }

        if(!$alreadyInCart) {
            array_push($_SESSION['itemsInCart'], array('id' => $productId, 'quantity' => $quantity));
        }
    }
    
    if(isset($_POST['action'])) {
        $action = $_POST['action'];
        if($action == "removeCartItems") {
            $_SESSION['itemsInCart'] = array();
        }
        if(str_contains($action, "product")) {
            $action = str_replace("product", "", $action);
            for($i = 0; $i < sizeof($_SESSION['itemsInCart']); $i++) {
                if($_SESSION["itemsInCart"][$i]['id'] == $action) {
                    array_splice($_SESSION['itemsInCart'], $i, 1);
                }
            }
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
    <form action="" style="display: none;" method="post" id="actionForm">
        <input type="text" name="action" id="actionInput">
        <input type="submit">
    </form>
    <form action="../index.php" method="post" style="display: none;" id="removeItemForm" >
        <input type="text" id="itemIdInput" name="removedItemId">
    </form>
    <div class="visibilityToggle" id="visibilityToggle"><span class="material-symbols-outlined" style="font-size: 2rem;">keyboard_arrow_up</span></div>
    <header id="header">
        <div class="logo">
            Shop
        </div>
        <div class="headerButtons">
            <a href="../index.php"><i class="fa-solid fa-shop"></i></a>
            <a href="#"><i onclick="redirectToCart()" class="fa-solid fa-cart-shopping"></i></a>
            <?php
                if(isset($_SESSION['userId'])) {
                    echo '<i class="fa-solid fa-right-from-bracket" id="signOutBtn"></i>';
                } else {
                    echo '<a href="../loginPage/login.php"><i class="fa-solid fa-right-from-bracket"></i></a>';
                }
            ?>
        </div>
    </header>
    <div class="cart">
        <div class="items">
            <?php
                $totalCost = 0;

                foreach($_SESSION['itemsInCart'] as $product) {
                    $sql = 'SELECT `name`, `price`, `imgPath` FROM inventory WHERE id = '.$product["id"].';';
                    $result = mysqli_fetch_row(mysqli_query($conn, $sql));

                    echo '<a href="../productPage/product.php?productId='.$product['id'].'">';
                    echo '<div class="cartItem">';
                    echo '<img src="../media/'.$result[2].'" alt="">';
                    echo '<div class="cartItemName">'.$result[0].'</div>';
                    echo '<div class="cartItemPrice"><span style="font-family: arial;">'.$product['quantity'].'</span> X '.$result[1].'$ <span class="cartTrashIcons" id="product'.$product['id'].'" style="padding-left: 50px;"><i class="fa-solid fa-trash"></i></span></div>';
                    echo '</div>';
                    echo '</a>';

                    $totalCost += $result[1] * $product['quantity'];
                }
            ?>
        </div>
        <div class="summary">
            <?php echo "<div class='summaryText'><span>Total Cost:</span> <span>$totalCost$</span></div>" ?>
            <button id="goToPaymentBtn">Go To Payment</button>
            <button id="removeCartItemsBtn">Remove all from cart</button>
        </div>
    </div>
    <script src="../index.js"></script>
</body>
</html>