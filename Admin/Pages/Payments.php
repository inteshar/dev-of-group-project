<?php
require_once '../../dbConnect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./Assets/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" integrity="sha256-2TnSHycBDAm2wpZmgdi0z81kykGPJAkiUY+Wf97RbvY=" crossorigin="anonymous" />
    <title>Admin</title>
    <!-- Include html2pdf.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

</head>

<body>
    <?php
    include '../../Admin/AdminComponents/SideBar.php';
    // require_once '../dbConnect.php';

    $stmt = $conn->prepare("SELECT members.*, loan_account.* FROM members INNER JOIN loan_account ON members.id = loan_account.member_id WHERE loan_account.status = 1 AND loan_account.next_payment <= CURDATE() AND loan_account.remaining_payment > 0 ORDER BY loan_account.account_opened_on DESC LIMIT 10");
    $stmt->execute();
    $recent_acc = $stmt->fetchAll(PDO::FETCH_ASSOC);


    ?>
    <div class="container">
                <div class="mt-3">
                        <?php
                        if (isset($_GET['msg'])) {
                            $message = $_GET['msg'];
                            echo $message;
                        }
                        ?>
                    </div>
                <h2 class="pb-2 pt-5 p-2 fw-bold">Today's Payments</h2>
                <div class="row row-cols-1 row-cols-md-3 g-4 mb-5 pb-5">
                    
                    <?php
                    if ($recent_acc){
                        foreach ($recent_acc as $rec) {
                          $openedDate = new DateTime($rec['account_opened_on']);
                          $planDays = new DateInterval('P' . $rec['plan'] . 'D');
                          $openedDate->add($planDays);
                          $dueDate = $openedDate->format('Y-m-d');

                          echo "<div class='col-md-2'>
                          <div class='card shadow bg-light'>
                        <img src='../MembersFiles/" . $rec['name'] . "/" . $rec['photo'] . "' class='card-img-top' alt='".$rec['name']."'>
                        <div class='card-body'>
                          <h5 class='card-title fw-bold'>".$rec['name']."</h5>
                          <p class='card-text border-start border-3 border-primary ps-2 shadow-sm rounded-3'>Pending Payment: <span class='fw-bold text-success'><br>Rs. ".number_format($rec['emi'], 2)."</span></p>
                          <p class='card-text border-start border-3 border-primary ps-2 shadow-sm rounded-3'>Payment Date: <span class='fw-bold text-success'><br>".$rec['next_payment']."</span></p>
                          <p class='card-text border-start border-3 border-primary ps-2 shadow-sm rounded-3'>Remaining Loan Amount: <span class='fw-bold text-danger'><br>Rs. ".number_format($rec['remaining_payment'], 2)."</span></p>
                          <p class='card-text border-start border-3 border-primary ps-2 shadow-sm rounded-3'>Due Date: <span class='fw-bold text-danger'><br>".$dueDate."</span></p>
                        </div>
                        <div class='card-footer'>
                          <small class='text-muted'>
                          <button data-bs-toggle='modal' data-bs-target='#staticBackdrop' class='btn btn-success w-100 fw-bold'
                          data-name='".$rec['name']."'
                          data-emi='".$rec['emi']."'
                          data-photo='".$rec['photo']."'
                          data-id='".$rec['member_id']."'
                          data-remaining='".$rec['remaining_payment']."'
                          data-due='".$dueDate."'
                          onclick='updateModal(this)'>Pay</button>
                          </small>
                        </div>
                      </div>
                      </div>
                      ";
                      }
                    }else{
                      echo "<p class='p-3 bg-success-subtle text-success fw-bold fs-6 text-center rounded shadow-sm'>No pending payments for today.</p>";
                    }
                    ?>
        </div>
    </div>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Payment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <form action="../AdminComponents/AcceptPayment.php" method="POST">
        <p class="p-2 bg-secondary-subtle text-center text-danger fw-bold">Please double check the details before payment.</p>
        
            <div class="mb-3">
            <label for="modalName" class="form-label">Name</label>
            <input type="text" id="modalName" class="form-control fw-bold text-danger fs-4" value="data-name" disabled>
            </div>
            <div class="mb-3">
            <label for="modalEmi" class="form-label">Amount (Rs.)</label>
            <input type="text" id="modalEmi" name="payment" class="form-control fw-bold text-danger fs-4" inputmode="numeric">
            </div>

            <input type="text" id="modalId" name="memberId" class="form-control fw-bold text-danger fs-4" hidden>
            <input type="text" id="modalName2" name="name" class="form-control fw-bold text-danger fs-4" hidden>
            
                <button type="submit" class="btn btn-success w-100 fw-bold">Save Payment</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>

    <script>
    function updateModal(element) {
        document.getElementById('modalName').value = element.getAttribute('data-name');
        document.getElementById('modalName2').value = element.getAttribute('data-name');
        document.getElementById('modalEmi').value = element.getAttribute('data-emi');
        document.getElementById('modalId').value = element.getAttribute('data-id');
    }
    </script>

</body>

</html>
