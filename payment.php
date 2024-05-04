<?php
// Include necessary files
include('includes/header.php');
include('includes/navbar.php');
include('functions/userFunctions.php');

// Check if user is authenticated
if(!isset($_SESSION['auth'])) {
    // Redirect to login page or display an error message
    $_SESSION['message'] = "Please login to view your cart.";
    header("Location: login.php");
    exit();
}

// Get user ID from session
$userId = $_SESSION['user_id'];

?>
<section class="p-5 p-md-5 text-sm-start" style="margin-top: 30px;">
    <div class="container">
        <div class="order-here">
            <div class="row">
                <div class="col-md-12">
                    <h1 style="font-family: 'suez one';
                    color: #013D67; 
                    ">Cart</h1>
                </div>
            </div>
        </div>
        <!--------------- ORDER TABLE --------------->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th></th>
                    <th>Category</th>
                    <th>Additional Price</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Get cart items for the current user
                    $cartItems = getCartItemsByUserId($userId);

                    if(mysqli_num_rows($cartItems) > 0){ 
                        // Iterate through each cart item
                        foreach($cartItems as $cart){ 
                ?>
                            <tr>
                                <td>
                                    <img src="uploads/<?= $cart['product_image']; ?>" width="50px" height="50px" alt="<?= $cart['product_name']; ?>">
                                </td>
                                <td><?= $cart['product_name']; ?></td>
                                <td><?= $cart['category_name']; ?></td>
                                <td><?= $cart['additional_price']; ?></td>
                                <td><?= $cart['selling_price']; ?></td>
                                <td><?= $cart['quantity']; ?></td>
                                <td>...</td>
                                <td>
                                    <form action="admin/codes.php" method="POST">
                                        <input type="hidden" name="cart_id" value="<?= $cart['id'];?>">
                                        <button type="submit" class="btn btn-danger text-white" name="deleteOrderBtn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                <?php
                        }
                    } else {
                        echo "No records found";
                    }
                ?>
            </tbody>
        </table>
    </div>
</section>

        <!-- Alertify JS -->
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

        <!-- Alertify JS -->
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
        <script>
    <?php
    if(isset($_SESSION['message'])){ // CHECK IF SESSION MESSAGE VARIABLE IS SET
  ?>
    alertify.set('notifier','position', 'top-right');
    var notification = alertify.success('<i class="fas fa-check animated-check"></i> <?= $_SESSION['message']?>'); // DISPLAY MESSAGE NOTIF with animated check icon
    notification.getElementsByClassName('animated-check')[0].addEventListener('animationend', function() {
      this.classList.remove('animated-check');
    }); // Remove animation class after animation ends
  <?php
    unset($_SESSION['message']); // UNSET THE SESSION MESSAGE VARIABLE
  }
  ?>
</script>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?> 
