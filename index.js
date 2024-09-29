function handleSearch(categories, searchbar) {
    let allItems = Array.from(document.getElementsByClassName("itemContainer"));
    let searchbarValue = searchbar.value.toLowerCase().replaceAll(" ", "");
    let itemName;
    let itemCategory;
    let chosenCategory;

    chosenCategory = categories.selectedOptions[0].value;

    allItems.forEach(item => {
        itemName = (item.querySelector('.title').innerHTML.toLowerCase()).replaceAll(" ", "");
        searchbarValue = searchbar.value.toLowerCase().replaceAll(" ", "");
        itemCategory = item.getAttribute("data-category")

        if(!itemName.includes(searchbarValue) && !itemCategory.includes(searchbarValue) || (chosenCategory != itemCategory && chosenCategory != 'any')) {
            console.log(item)
            item.style.display = "none";
        } else {
            item.style.display = "flex";
        }
    });
}

let visibilityToggle = document.getElementById("visibilityToggle")
let header = document.getElementById("header")
let isVisible = true;

//show/hide header
visibilityToggle.addEventListener("click", () => {
    if(isVisible) {
        header.animate(
            { transform: "translate(0, -100px)"},
            {fill: "forwards", duration: 250, easing: "ease-out"}
        )
        visibilityToggle.animate(
            { transform: "rotate(180deg) translate(50%, 50%)"},
            { fill: "forwards", duration: 250, easing: "ease-out"}
        )
    } else {
        header.animate(
            { transform: "translate(0, 0px)"},
            {fill: "forwards", duration: 250, easing: "ease-out"}
        )
        visibilityToggle.animate(
            { transform: "rotate(0deg) translate(-50%, -50%)" },
            { fill: "forwards", duration: 250, easing: "ease-out"}
        )
    }

    isVisible = !isVisible;
});

let trashIcons = Array.from(document.getElementsByClassName("trashIcon"));
let itemIdInput = document.getElementById("itemIdInput");
let removeItemForm = document.getElementById("removeItemForm");

//sign out
if(document.getElementById("signOutBtn") != null) {
    let signOutBtn = document.getElementById("signOutBtn");

    signOutBtn.addEventListener("click", () => {
        if(confirm("Are you sure you want to sign out ?")) {
            itemIdInput.value = "-1"
            removeItemForm.submit();
        }
    });
}

//remove item
trashIcons.forEach(element => {
    element.addEventListener("click", (event) => {
        event.preventDefault();
        event.stopPropagation();
        if(confirm("Are you sure you want to remove this item ?")) {
            itemIdInput.value = element.id.replace("removeId", "");
            removeItemForm.submit();
        }
    });
});

//search for matching items
if(document.getElementById("searchbarInput") != null) {
    let categories = document.getElementById('categories');
    let searchbar = document.getElementById("searchbarInput");

    categories.addEventListener("change", () => {
        handleSearch(categories, searchbar)
    });
    searchbar.addEventListener("keyup", () => {
        handleSearch(categories, searchbar)
    });
}

let increaseQuantity = document.getElementById("increaseQuantity")
let decreaseQuantity = document.getElementById("decreaseQuantity")
let quantity = document.getElementById("quantity")

//handle quantity arrows
if (document.getElementById("inStock") != null) {
    let inStock = parseInt(document.getElementById("inStock").innerText)

    increaseQuantity.addEventListener("click", () => {
        if(parseInt(quantity.innerText) + 1 <= inStock) {
            quantity.innerText = parseInt(quantity.innerText) + 1;
        }
    });

    decreaseQuantity.addEventListener("click", () => {
        if(parseInt(quantity.innerText) - 1 > 0) {
            quantity.innerText = parseInt(quantity.innerText) - 1;
        }
    });
}

//edit product page

if(document.getElementById("editBuyPanel")) {
    let editBuyPanel = document.getElementById("editBuyPanel");
    let editDescription = document.getElementById("editDescription");
    let productPrice = document.getElementById("price");
    let inStock = document.getElementById("inStock");

    editBuyPanel.addEventListener("click", () => {
        productPrice.innerHTML = `<input value="${productPrice.innerText}"></input>`;
        inStock.innerHTML = `<input value="${inStock.innerText}"></input>`;
    });
}

//add to cart
if(document.getElementById("productId") != null) {
    let productId = parseInt(document.getElementById("productId").innerText)
    let addToCartBtn = document.getElementById("addToCartBtn")
    let productIdInput = document.getElementById("productIdInput")
    let quantityInput = document.getElementById("quantityInput")
    let productForm = document.getElementById("productForm")

    addToCartBtn.addEventListener("click", () => {
        productIdInput.value = productId;
        quantityInput.value = parseInt(quantity.innerText);
        productForm.submit()
    });
}

//remove cart items
if (document.getElementById("actionInput") != null) {
    let removeCartItemsBtn = document.getElementById("removeCartItemsBtn")
    let actionInput = document.getElementById("actionInput")
    let actionForm = document.getElementById("actionForm")

    removeCartItemsBtn.addEventListener("click", () => {
        actionInput.value = "removeCartItems"
        actionForm.submit()
    });

    let cartTrashIcons = Array.from(document.getElementsByClassName("cartTrashIcons"));

    cartTrashIcons.forEach(element => {
            element.addEventListener("click", (event) => {
            event.preventDefault();
            event.stopPropagation();
            if(confirm("Are you sure you want to remove this item ?")) {
                actionInput.value = element.id.replace("removeProduct", "");
                actionForm.submit();
            }
        })
    });
}

let increasePriceArrow = document.getElementById("increasePriceArrow")
let decreasePriceArrow = document.getElementById("decreasePriceArrow")
let priceInput = document.getElementById("priceInput")

let increaseStockArrow = document.getElementById("increaseStockArrow")
let decreaseStockArrow = document.getElementById("decreaseStockArrow")
let inStockInput = document.getElementById("inStockInput")

//handle add item input arrows
if(increasePriceArrow != null) {
    increasePriceArrow.addEventListener("click", () => {
        if(priceInput.value != "") {
            priceInput.value = parseFloat(priceInput.value) + 100;
        } else {
            priceInput.value = 100;
        }
    });

    decreasePriceArrow.addEventListener("click", () => {
        if(parseFloat(priceInput.value) == 199.99) {
            priceInput.value = 99.99;
        } else if(priceInput.value - 100 >= 0) {
            priceInput.value -= 100;
        } else {
            priceInput.value = 0;
        }
    });


    increaseStockArrow.addEventListener("click", () => {
        inStockInput.value++;
    });
    decreaseStockArrow.addEventListener("click", () => {
        if(inStockInput.value - 1 >= 0) {
            inStockInput.value--;
        } else {
            inStockInput.value = 0
        }
    });
}

//prevent form resubmision on page refresh
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}