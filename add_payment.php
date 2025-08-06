<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'] ?? null;
    $payment_amount = $_POST['payment_amount'] ?? 0;
    $payment_date = $_POST['payment_date'] ?? date('Y-m-d');
    
    if ($member_id !== null && $payment_amount > 0) {
        $members = load_members();
        
        if (isset($members[$member_id])) {
            $member = $members[$member_id];
            
            // Add payment to history
            if (!isset($member['payment_history'])) {
                $member['payment_history'] = [];
            }
            $member['payment_history'][] = [
                'date' => $payment_date,
                'amount' => $payment_amount
            ];
            
            // Update payment amounts
            $member['amount_paid'] += $payment_amount;
            $member['pending_amount'] = max(0, $member['pending_amount'] - $payment_amount);
            $member['last_payment_date'] = $payment_date;
            
            // Save updated member
            $members[$member_id] = $member;
            save_members($members);
            
            $success = "Payment of " . formatCurrency($payment_amount) . " added successfully!";
        } else {
            $error = "Member not found!";
        }
    } else {
        $error = "Invalid payment data!";
    }
}

// Redirect back to members page
$redirect_url = 'members.php';
if (isset($success)) {
    $redirect_url .= '?success=' . urlencode($success);
}
if (isset($error)) {
    $redirect_url .= '?error=' . urlencode($error);
}

header('Location: ' . $redirect_url);
exit();
?> 