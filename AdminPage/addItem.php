<?php
    session_start();
    if(!$_SESSION['hasAdminPrivilege']) {
        header('Location:../loginPage/login.php');
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
            <a href="../cart/cart.php"><i onclick="redirectToCart()" class="fa-solid fa-cart-shopping"></i></a>
            <?php
                if(isset($_SESSION['userId'])) {
                    echo '<i class="fa-solid fa-right-from-bracket" id="signOutBtn"></i>';
                } else {
                    echo '<a href="../loginPage/login.php"><i class="fa-solid fa-right-from-bracket"></i></a>';
                }
            ?>
        </div>
    </header>
    <main>
        <form class="addItemForm" method="post">
            <input type="text" placeholder="Enter The Product's name" name="name" required>
            <div class="row">
                <span style="display: flex; flex-direction:row;">
                    <input type="number" placeholder="Enter The Product's price" step="0.01" name="price" min="0" id="priceInput" required>
                    <span class="numberArrows">
                        <span class="material-symbols-outlined" id="increasePriceArrow">keyboard_arrow_up</span>
                        <span class="material-symbols-outlined"id="decreasePriceArrow">keyboard_arrow_down</span>
                    </span>
                </span>
                <span style="display: flex; flex-direction:row;">
                    <input type="number" placeholder="Products In Stock" step="1" name="inStock" min="0" id="inStockInput" required>
                    <span class="numberArrows">
                        <span class="material-symbols-outlined upNumberArrow" id="increaseStockArrow">keyboard_arrow_up</span>
                        <span class="material-symbols-outlined downNumberArrow" id="decreaseStockArrow">keyboard_arrow_down</span>
                    </span>
                </span>
            </div>
            <textarea name="description" placeholder="Enter Product's Description" required></textarea>
            <div class="row">
                <span>
                <select name="category" required>
                    <option style="display: none;">Choose Category</option>
                    <?php
                        $conn = mysqli_connect('localhost', 'root', '', 'sklep');

                        $sql = "SELECT * FROM categories";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_row($result)) {
                            echo "<option value='".$row[0]."'>".ucwords(str_replace("_", " ", $row[1]))."</option>";
                        }
                    ?>
                </select>
                <span class="material-symbols-outlined selectArrow">keyboard_arrow_down</span>
                </span>
                <input type="text" placeholder="Enter The Image File Name" class="fileNameInput" name="imgPath" required>
            </div>
            <div class="addItemBtns">
                <input type="submit" value="Add Item">
                <input type="reset" value="Reset">
            </div>
        </form>
    </main>
    <?php
        $conn = mysqli_connect('localhost', 'root', '', 'sklep');

        function sanitizeData($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        if(isset($_POST['name']) && isset($_POST['price']) && isset($_POST['inStock']) && isset($_POST['description']) && isset($_POST['category']) && isset($_POST['imgPath'])) {
            if(!empty($_POST['name']) && $_POST['price'] >= 0 && !empty($_POST['inStock']) && !empty($_POST['description']) && !empty($_POST['category']) && !empty($_POST['imgPath'])) {
                $name = sanitizeData($_POST['name']);
                $price = sanitizeData($_POST['price']);
                $inStock = sanitizeData($_POST['inStock']);
                $description = sanitizeData($_POST['description']);
                $category = sanitizeData($_POST['category']);
                $imgPath = sanitizeData($_POST['imgPath']);
                
                mysqli_query($conn, "INSERT INTO inventory(name, price, description, categoryId, imgPath, inStock) values('$name', $price, '$description', $category, '$imgPath', $inStock)");
            }
        }
    ?>
    <script src="../index.js"></script>
</body>
</html>