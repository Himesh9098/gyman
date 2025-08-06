<?php
if (session_status() === PHP_SESSION_NONE) session_start();
function t($key) {
    $lang = $_SESSION['lang'] ?? 'en';
    $labels = [
        'en' => [
            'member_management' => 'Member Management',
            'add_member' => 'Add Member',
            'edit_member' => 'Edit Member',
            'name' => 'Name',
            'age' => 'Age',
            'weight' => 'Weight (kg)',
            'contact' => 'Contact',
            'start_date' => 'Membership Start Date',
            'end_date' => 'Membership End Date',
            'plan' => 'Plan Type',
            'workout' => 'Workout Plan',
            'diet' => 'Diet Plan',
            'actions' => 'Actions',
            'update' => 'Update',
            'add' => 'Add',
            'cancel' => 'Cancel',
            'payment' => 'Payment',
            'pending_amount' => 'Pending Amount',
            'amount_paid' => 'Amount Paid',
            'add_payment' => 'Add Payment',
            'payment_history' => 'Payment History',
            'due_members' => 'Due Members',
            'expiring_soon' => 'Expiring Soon',
            'expired_today' => 'Expired Today',
            'total_due' => 'Total Due Amount',
            'monthly_income' => 'Monthly Income',
            'send_reminder' => 'Send Reminder',
            'reminder_sent' => 'Reminder Sent',
            'email_template' => 'Email Template',
            'whatsapp_template' => 'WhatsApp Template'
        ],
        'hi' => [
            'member_management' => 'सदस्य प्रबंधन',
            'add_member' => 'सदस्य जोड़ें',
            'edit_member' => 'सदस्य संपादित करें',
            'name' => 'नाम',
            'age' => 'आयु',
            'weight' => 'वजन (किग्रा)',
            'contact' => 'संपर्क',
            'start_date' => 'सदस्यता प्रारंभ तिथि',
            'end_date' => 'सदस्यता समाप्ति तिथि',
            'plan' => 'प्लान प्रकार',
            'workout' => 'वर्कआउट प्लान',
            'diet' => 'डाइट प्लान',
            'actions' => 'क्रियाएँ',
            'update' => 'अपडेट',
            'add' => 'जोड़ें',
            'cancel' => 'रद्द करें',
            'payment' => 'भुगतान',
            'pending_amount' => 'बकाया राशि',
            'amount_paid' => 'भुगतान की गई राशि',
            'add_payment' => 'भुगतान जोड़ें',
            'payment_history' => 'भुगतान इतिहास',
            'due_members' => 'बकाया सदस्य',
            'expiring_soon' => 'जल्द समाप्त हो रहे',
            'expired_today' => 'आज समाप्त हुए',
            'total_due' => 'कुल बकाया राशि',
            'monthly_income' => 'मासिक आय',
            'send_reminder' => 'रिमाइंडर भेजें',
            'reminder_sent' => 'रिमाइंडर भेजा गया',
            'email_template' => 'ईमेल टेम्पलेट',
            'whatsapp_template' => 'व्हाट्सएप टेम्पलेट'
        ]
    ];
    return $labels[$lang][$key] ?? $key;
}

// Helper functions for payment tracking and reminders
function load_members() {
    $file = 'members.json';
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    return json_decode($json, true) ?: [];
}

function save_members($members) {
    file_put_contents('members.json', json_encode($members, JSON_PRETTY_PRINT));
}

function getDueMembers() {
    $members = load_members();
    $due_members = [];
    foreach ($members as $member) {
        if (isset($member['pending_amount']) && $member['pending_amount'] > 0) {
            $due_members[] = $member;
        }
    }
    return $due_members;
}

function getExpiringSoonMembers($days = 7) {
    $members = load_members();
    $expiring_members = [];
    $today = date('Y-m-d');
    $target_date = date('Y-m-d', strtotime("+$days days"));
    
    foreach ($members as $member) {
        if (isset($member['plan_end']) && $member['plan_end'] >= $today && $member['plan_end'] <= $target_date) {
            $member['days_left'] = (strtotime($member['plan_end']) - strtotime($today)) / 86400;
            $expiring_members[] = $member;
        }
    }
    return $expiring_members;
}

function getExpiredTodayMembers() {
    $members = load_members();
    $expired_members = [];
    $today = date('Y-m-d');
    
    foreach ($members as $member) {
        if (isset($member['plan_end']) && $member['plan_end'] === $today) {
            $expired_members[] = $member;
        }
    }
    return $expired_members;
}

function getTotalDueAmount() {
    $due_members = getDueMembers();
    $total = 0;
    foreach ($due_members as $member) {
        $total += $member['pending_amount'];
    }
    return $total;
}

function getMonthlyIncome() {
    $members = load_members();
    $current_month = date('Y-m');
    $total = 0;
    
    foreach ($members as $member) {
        if (isset($member['last_payment_date'])) {
            $payment_month = date('Y-m', strtotime($member['last_payment_date']));
            if ($payment_month === $current_month && isset($member['amount_paid'])) {
                $total += $member['amount_paid'];
            }
        }
    }
    return $total;
}

function sendReminderEmail($member) {
    $subject = "Membership Renewal Reminder - " . $member['name'];
    $message = "Dear " . $member['name'] . ",\n\n";
    $message .= "Your gym membership is expiring on " . $member['plan_end'] . ".\n";
    $message .= "Plan: " . $member['plan'] . "\n";
    $message .= "Pending Amount: ₹" . $member['pending_amount'] . "\n\n";
    $message .= "Please renew your membership to continue enjoying our facilities.\n\n";
    $message .= "Best regards,\nGym Management Team";
    
    return [
        'to' => $member['contact'] . '@example.com',
        'subject' => $subject,
        'message' => $message
    ];
}

function sendReminderWhatsApp($member) {
    $message = "Hi " . $member['name'] . "! 🏋️‍♂️\n\n";
    $message .= "Your gym membership expires on " . $member['plan_end'] . ".\n";
    $message .= "Plan: " . $member['plan'] . "\n";
    $message .= "Pending: ₹" . $member['pending_amount'] . "\n\n";
    $message .= "Renew now to keep your fitness journey going! 💪\n\n";
    $message .= "Contact us: +91-9876543210";
    
    return [
        'to' => '+91' . $member['contact'],
        'message' => $message
    ];
}

function logReminder($member_id, $type, $content) {
    $log_file = 'reminder_log.json';
    $logs = [];
    if (file_exists($log_file)) {
        $logs = json_decode(file_get_contents($log_file), true) ?: [];
    }
    
    $logs[] = [
        'member_id' => $member_id,
        'type' => $type,
        'content' => $content,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents($log_file, json_encode($logs, JSON_PRETTY_PRINT));
}

function formatCurrency($amount) {
    return '₹' . number_format($amount, 0);
}

function formatDate($date) {
    return date('d M Y', strtotime($date));
}