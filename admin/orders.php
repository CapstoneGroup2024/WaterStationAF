<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
?>
<!--------------- PRODUCT PAGE --------------->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 style="font-family: 'Suez One', sans-serif; font-size: 35px;">Orders</h4>
                </div>
                <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Pending Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="deliverOrder.php" style="color:black;">Orders for Delivery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="completedOrders.php" style="color:black;">Completed Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cancelledOrders.php" style="color:black;">Cancelled Orders</a>
                    </li>
                    </ul>
                    <!--------------- PRODUCTS TABLE --------------->
                    <h6 style="font-family: 'Suez One', sans-serif; font-size: 30px; padding-top:20px;">Pending Orders</h6>
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Order Status</th>
                                <th>Items</th>
                                <th>Details</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php            
// GET DATA FOR ORDERS
$query = "SHOW COLUMNS FROM orders WHERE Field = 'status'";
$result = mysqli_query($con, $query);

// Extract enum values from the query result
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // Extract the enum values from the "Type" column
    preg_match_all("/'(.*?)'/", $row['Type'], $matches);
    $statusOptions = $matches[1];
} else {
    // Handle error if the query fails
    $statusOptions = array(); // Provide a default empty array
}

$orders = getOrderData("orders"); // FUNCTION TO FETCH ORDER DATA FROM THE DATABASE
if(mysqli_num_rows($orders) > 0){ // CHECK IF THERE ARE ANY ORDERS
    foreach($orders as $order){
        if ($order['status'] == 'Ongoing'){// ITERATE THROUGH EACH ORDER
            // Fetch user details for the current order
            $userDetails = getUserDetails($order['user_id']);
            $product = getFirstProductByOrderId($order['id']);
            if($userDetails){
                if($product !== null){
?>
                <tr style="text-align: center; vertical-align: middle;">
                    <td><?= $order['id']; ?></td>
                    <td><?= $userDetails['name']; ?></td> <!-- Display user's name -->
                    <td>
                        <form action="codes.php" method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                            <select name="status" style="padding: 8px; border-radius: 10px;">
                                <?php foreach ($statusOptions as $option): ?>
                                    <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="submit" style="margin-top: 10px;" class="btn bg-primary text-white" name="editOrderStatus" value="Update">
                        </form>
                    </td>

                    </td>
                    <td><?= $product['product_name']; ?></td>
                    <td>
                        <a href="orderDetails.php?id=<?= $order['id']; ?>"style="margin-top: 10px;" class="btn bg-primary text-white">View Details</a>
                    </td>
                    <td>
                        <form action="codes.php" method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['id'];?>">
                            <button type="submit" class="btn btn-danger text-white" style="margin-top: 10px;" name="deleteOrder_button">Delete</button>
                        </form>
                    </td>
                </tr>
<?php
                } else {
                    ?>
                    <tr style="text-align: center; vertical-align: middle;">
                        <td colspan="5" style="color:red;">Error: Failed to fetch product details for order ID: <?= $order['id']; ?></td>
                        <td>
                            <form action="codes.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id'];?>">
                                <button type="submit" class="btn btn-danger text-white" style="margin-top: 10px;" name="deleteOrder_button">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                } 
            } else {
                ?>
                    <tr style="text-align: center; vertical-align: middle;">
                        <td colspan="5" style="color:red;">Error: No user details found for order ID: <?= $order['id']; ?></td>
                        <td>
                            <form action="codes.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id'];?>">
                                <button type="submit" class="btn btn-danger text-white" style="margin-top: 10px;" name="deleteOrder_button">Delete</button>
                            </form>
                        </td>
                    </tr>

                <?php
            }
        }
    }
} else {
    ?>
    <tr>
        <td colspan="6">No ongoing orders found</td>
    </tr>
    <?php
}
?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
