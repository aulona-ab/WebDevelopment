<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the total price sent from the form
    $total_price = $_POST['total_price'];

    // Display the total price
    echo "<h3>Total Price: €" . $total_price . "</h3>";
}
?>

<!-- Form to collect card details -->
<form action="complete_purchase.php" method="POST">
    <label for="card_number">Card Number:</label>
    <input type="text" id="card_number" name="card_number" required>

    <label for="card_expiry">Expiry Date:</label>
    <input type="text" id="card_expiry" name="card_expiry" required>

    <label for="card_cvc">CVC:</label>
    <input type="text" id="card_cvc" name="card_cvc" required>

    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

    <input type="submit" value="Complete Purchase">
</form>
