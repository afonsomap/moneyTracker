<?php
// Initialize SQLite database
try {
    $db = new SQLite3('bankroll.db');
} catch (Exception $e) {
    die("Failed to connect to database: " . $e->getMessage());
}

// Create tables if they don't exist
$db->exec('
    CREATE TABLE IF NOT EXISTS bankroll (
        id INTEGER PRIMARY KEY,
        amount REAL DEFAULT 0.00
    )
');
$db->exec('
    CREATE TABLE IF NOT EXISTS transactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        "timestamp" TEXT,
        money_added REAL,
        loss REAL,
        gain REAL,
        description TEXT,
        new_bankroll REAL
    )
');

// Ensure one row exists in bankroll table
$stmt = $db->prepare('SELECT COUNT(*) as count FROM bankroll');
if ($stmt === false) {
    die("Failed to prepare SELECT statement: " . $db->lastErrorMsg());
}
$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);
if ($row['count'] == 0) {
    $stmt = $db->prepare('INSERT INTO bankroll (id, amount) VALUES (:id, :amount)');
    if ($stmt === false) {
        die("Failed to prepare INSERT bankroll statement: " . $db->lastErrorMsg());
    }
    $stmt->bindValue(':id', 1, SQLITE3_INTEGER);
    $stmt->bindValue(':amount', 0.00, SQLITE3_FLOAT);
    $stmt->execute();
}

// Handle transaction deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_transaction'])) {
    $transaction_id = intval($_POST['delete_transaction']);
    
    // Delete the transaction
    $stmt = $db->prepare('DELETE FROM transactions WHERE id = :id');
    if ($stmt === false) {
        die("Failed to prepare DELETE statement: " . $db->lastErrorMsg());
    }
    $stmt->bindValue(':id', $transaction_id, SQLITE3_INTEGER);
    $stmt->execute();

    // Recalculate bankroll from remaining transactions
    $stmt = $db->prepare('SELECT money_added, loss, gain FROM transactions ORDER BY "timestamp" ASC');
    if ($stmt === false) {
        die("Failed to prepare SELECT transactions statement: " . $db->lastErrorMsg());
    }
    $result = $stmt->execute();
    $new_bankroll = 0.00;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $new_bankroll += $row['money_added'] - $row['loss'] + $row['gain'];
    }

    // Update bankroll in database
    $stmt = $db->prepare('UPDATE bankroll SET amount = :amount WHERE id = :id');
    if ($stmt === false) {
        die("Failed to prepare UPDATE bankroll statement: " . $db->lastErrorMsg());
    }
    $stmt->bindValue(':amount', $new_bankroll, SQLITE3_FLOAT);
    $stmt->bindValue(':id', 1, SQLITE3_INTEGER);
    $stmt->execute();

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle form submission for new transactions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_transaction'])) {
    $money_added = isset($_POST['money_added']) ? floatval($_POST['money_added']) : 0;
    $loss = isset($_POST['loss']) ? floatval($_POST['loss']) : 0;
    $gain = isset($_POST['gain']) ? floatval($_POST['gain']) : 0;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Get current bankroll
    $stmt = $db->prepare('SELECT amount FROM bankroll WHERE id = :id');
    if ($stmt === false) {
        die("Failed to prepare SELECT bankroll statement: " . $db->lastErrorMsg());
    }
    $stmt->bindValue(':id', 1, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $bankroll = $result->fetchArray(SQLITE3_ASSOC)['amount'];

    // Update bankroll
    $new_bankroll = $bankroll + $money_added - $loss + $gain;

    // Update bankroll in database
    $stmt = $db->prepare('UPDATE bankroll SET amount = :amount WHERE id = :id');
    if ($stmt === false) {
        die("Failed to prepare UPDATE bankroll statement: " . $db->lastErrorMsg());
    }
    $stmt->bindValue(':amount', $new_bankroll, SQLITE3_FLOAT);
    $stmt->bindValue(':id', 1, SQLITE3_INTEGER);
    $stmt->execute();

    // Log transaction
    $timestamp = date('Y-m-d H:i:s');
    $stmt = $db->prepare('INSERT INTO transactions ("timestamp", money_added, loss, gain, description, new_bankroll) VALUES (:timestamp, :money_added, :loss, :gain, :description, :new_bankroll)');
    if ($stmt === false) {
        die("Failed to prepare INSERT transactions statement: " . $db->lastErrorMsg());
    }
    $stmt->bindValue(':timestamp', $timestamp, SQLITE3_TEXT);
    $stmt->bindValue(':money_added', $money_added, SQLITE3_FLOAT);
    $stmt->bindValue(':loss', $loss, SQLITE3_FLOAT);
    $stmt->bindValue(':gain', $gain, SQLITE3_FLOAT);
    $stmt->bindValue(':description', $description, SQLITE3_TEXT);
    $stmt->bindValue(':new_bankroll', $new_bankroll, SQLITE3_FLOAT);
    $stmt->execute();

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Get current bankroll for display
$stmt = $db->prepare('SELECT amount FROM bankroll WHERE id = :id');
if ($stmt === false) {
    die("Failed to prepare SELECT bankroll statement: " . $db->lastErrorMsg());
}
$stmt->bindValue(':id', 1, SQLITE3_INTEGER);
$result = $stmt->execute();
$bankroll = $result->fetchArray(SQLITE3_ASSOC)['amount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bankroll Tracker</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Bankroll Tracker</h1>
        <h2>Current Bankroll: <span class="bankroll-amount <?php echo $bankroll < 0 ? 'negative-bankroll' : ''; ?>">$<?php echo number_format($bankroll, 2); ?></span></h2>
        <form id="bankrollForm" method="POST" action="">
            <div class="input-group">
                <label for="money_added">Money Added:</label>
                <input type="number" id="money_added" name="money_added" step="0.01" min="0" placeholder="Enter money added">
            </div>
            <div class="input-group">
                <label for="loss">Loss:</label>
                <input type="number" id="loss" name="loss" step="0.01" min="0" placeholder="Enter loss amount">
            </div>
            <div class="input-group">
                <label for="gain">Gain:</label>
                <input type="number" id="gain" name="gain" step="0.01" min="0" placeholder="Enter gain amount">
            </div>
            <div class="input-group">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" placeholder="Enter transaction description">
            </div>
            <button type="submit" class="submit-btn">Update Bankroll</button>
        </form>
        <div class="history">
            <h3>Transaction History</h3>
            <?php
            $stmt = $db->prepare('SELECT id, "timestamp", money_added, loss, gain, description, new_bankroll FROM transactions ORDER BY "timestamp" DESC');
            if ($stmt === false) {
                die("Failed to prepare SELECT transactions statement: " . $db->lastErrorMsg());
            }
            $result = $stmt->execute();
            $has_transactions = false;
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                if (!$has_transactions) {
                    echo '<table class="history-table">';
                    echo '<tr><th>Date</th><th>Money Added</th><th>Loss</th><th>Gain</th><th>Description</th><th>New Bankroll</th><th>Action</th></tr>';
                    $has_transactions = true;
                }
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['timestamp']) . '</td>';
                echo '<td>$' . number_format($row['money_added'], 2) . '</td>';
                echo '<td>$' . number_format($row['loss'], 2) . '</td>';
                echo '<td>$' . number_format($row['gain'], 2) . '</td>';
                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                echo '<td>$' . number_format($row['new_bankroll'], 2) . '</td>';
                echo '<td><form class="delete-form" method="POST" action=""><input type="hidden" name="delete_transaction" value="' . $row['id'] . '"><button type="submit" class="delete-btn" title="Delete Transaction"><i class="fas fa-trash"></i></button></form></td>';
                echo '</tr>';
            }
            if ($has_transactions) {
                echo '</table>';
            } else {
                echo '<p class="no-transactions">No transactions yet.</p>';
            }
            $db->close();
            ?>
        </div>
    </div>
</body>
</html>