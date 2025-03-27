    <?php 
    session_start();
    include('connection.php');

    if( isset($_GET['order_id'])){
            $order_id = $_GET['order_id'];
            $order_status = "Paid";
            $user_id = $_SESSION['user_id'];
            $payment_date = date('Y-m-d H:i:s');


           // $transaction_id = $_GET['transaction_id']; //co the loi vi khong co transaction_id o payment
            //change payment order_status to paid
            $stmt = $conn->prepare("UPDATE orders SET order_status=? WHERE order_id=?");
            $stmt->bind_param("si", $order_status, $order_id);
            $stmt->execute();

            //store payment info
            $stmt1 =  $conn->prepare("INSERT INTO payments (order_id,user_id,payment_date)
                            VALUES (?,?,?);");
            $stmt1->bind_param('iis',$order_id,$user_id,$payment_date);
       
            $stmt1->execute();

            //go to user account

            header("location: ../account.php?payment_massage=paid successfully, thanks for shopping with us");
    }else{
        header("location: index.php");
        exit();

    }


    
    ?>



