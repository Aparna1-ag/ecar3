<?php
include 'conn.php';

// Log the incoming request data
error_log("Incoming request data: " . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['production_id'], $_POST['sale_amount'])) {
    $rowId = $_POST['production_id'];
    $saleAmount = $_POST['sale_amount'];

    // Log received data
    error_log("Received production_id: $rowId, sale_amount: $saleAmount");

    // Perform the necessary SQL query to insert data into another table
    $insertSql = "INSERT INTO finished_costing (production_id, cost) VALUES (?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->execute([$rowId, $saleAmount]);

    // Log the executed SQL query
    error_log("Executed SQL query: $insertSql");

    if ($stmt->rowCount() > 0) {
        echo "Data saved successfully!";
    } else {
        echo "Error saving data.";
    }
} else {
    echo "Invalid request.";
}
?>
