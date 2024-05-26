<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../dbConnect.php';

try {
    $stmt = $conn->prepare("SELECT * FROM messages ORDER BY date DESC LIMIT 20");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<div class="recent-payments shadow mb-5">
    <div class="bg-primary-subtle rounded p-1">
        <h5 class="pb-0 p-2 fw-bold">Messages</h5>
        <div class="table-container">';
    echo '<div class="accordion accordion-flush" id="accordionFlushExample">';

    foreach ($results as $index => $result) {
        $accordionId = 'flush-collapse' . ($index + 1);

        echo '<div class="accordion-item border border-1 border-dark">';
        echo '<h2 class="accordion-header">';
        echo '<button class="accordion-button collapsed fw-bold bg-warning-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#' . $accordionId . '" aria-expanded="false" aria-controls="' . $accordionId . '">';
        echo $result['name'] . ' - ' . $result['subject'];
        echo '</button>';
        echo '</h2>';
        echo '<div id="' . $accordionId . '" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">';
        echo '<div class="accordion-body bg-warning-subtle">
        Date: <span class="fw-bold">' . $result['date'] . '</span><br> 
        Email: <span class="fw-bold">' . $result['email'] . '</span><br> 
        Mobile Number: <span class="fw-bold">' . $result['mobile'] . '</span><br> 
        Message: <span class="fw-bold">' . htmlspecialchars($result['message']) . '</span><br>
        <a class="btn btn-danger mt-2 shadow" href="./AdminComponents/MessagesDelete.php?msgId=' . $result['id'] . '">Delete</a>
        </div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div></div>
    </div>
</div>';
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
