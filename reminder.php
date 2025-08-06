<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
include 'includes/header.php';

$reminders_sent = [];
$expiring_members = getExpiringSoonMembers(7);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'] ?? null;
    $reminder_type = $_POST['reminder_type'] ?? '';
    
    if ($member_id !== null && $reminder_type) {
        $members = load_members();
        if (isset($members[$member_id])) {
            $member = $members[$member_id];
            
            if ($reminder_type === 'email') {
                $email_data = sendReminderEmail($member);
                logReminder($member['id'], 'email', $email_data['message']);
                $reminders_sent[] = [
                    'member' => $member['name'],
                    'type' => 'Email',
                    'to' => $email_data['to'],
                    'subject' => $email_data['subject'],
                    'message' => $email_data['message']
                ];
            } elseif ($reminder_type === 'whatsapp') {
                $whatsapp_data = sendReminderWhatsApp($member);
                logReminder($member['id'], 'whatsapp', $whatsapp_data['message']);
                $reminders_sent[] = [
                    'member' => $member['name'],
                    'type' => 'WhatsApp',
                    'to' => $whatsapp_data['to'],
                    'message' => $whatsapp_data['message']
                ];
            }
        }
    }
}
?>

<h3>Automated Reminder System</h3>

<!-- Reminders Sent -->
<?php if (count($reminders_sent) > 0): ?>
<div class="alert alert-success">
    <h5>Reminders Sent Successfully!</h5>
    <?php foreach ($reminders_sent as $reminder): ?>
    <div class="mb-2">
        <strong><?php echo $reminder['type']; ?> to <?php echo htmlspecialchars($reminder['member']); ?></strong><br>
        <small>To: <?php echo htmlspecialchars($reminder['to']); ?></small>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Expiring Members -->
<div class="row">
    <div class="col-md-8">
        <h4>Members Expiring in Next 7 Days</h4>
        <?php if (count($expiring_members) === 0): ?>
            <div class="alert alert-success">No members expiring in the next 7 days!</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Plan</th>
                            <th>Expiry Date</th>
                            <th>Days Left</th>
                            <th>Pending Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expiring_members as $i => $member): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['name']); ?></td>
                            <td><?php echo htmlspecialchars($member['plan']); ?></td>
                            <td><?php echo formatDate($member['plan_end']); ?></td>
                            <td><?php echo $member['days_left']; ?> days</td>
                            <td><?php echo formatCurrency($member['pending_amount']); ?></td>
                            <td>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="member_id" value="<?php echo $i; ?>">
                                    <input type="hidden" name="reminder_type" value="email">
                                    <button type="submit" class="btn btn-sm btn-primary">Send Email</button>
                                </form>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="member_id" value="<?php echo $i; ?>">
                                    <input type="hidden" name="reminder_type" value="whatsapp">
                                    <button type="submit" class="btn btn-sm btn-success">Send WhatsApp</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Reminder Templates</h5>
                
                <h6>Email Template</h6>
                <div class="alert alert-info">
                    <strong>Subject:</strong> Membership Renewal Reminder - [Member Name]<br><br>
                    <strong>Message:</strong><br>
                    Dear [Member Name],<br><br>
                    Your gym membership is expiring on [Expiry Date].<br>
                    Plan: [Plan Type]<br>
                    Pending Amount: ₹[Amount]<br><br>
                    Please renew your membership to continue enjoying our facilities.<br><br>
                    Best regards,<br>Gym Management Team
                </div>
                
                <h6>WhatsApp Template</h6>
                <div class="alert alert-success">
                    Hi [Member Name]! 🏋️‍♂️<br><br>
                    Your gym membership expires on [Expiry Date].<br>
                    Plan: [Plan Type]<br>
                    Pending: ₹[Amount]<br><br>
                    Renew now to keep your fitness journey going! 💪<br><br>
                    Contact us: +91-9876543210
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reminder Log -->
<?php
$log_file = 'reminder_log.json';
$logs = [];
if (file_exists($log_file)) {
    $logs = json_decode(file_get_contents($log_file), true) ?: [];
}
if (count($logs) > 0):
?>
<div class="row mt-4">
    <div class="col-12">
        <h4>Recent Reminder Log</h4>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Member ID</th>
                        <th>Type</th>
                        <th>Content</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($logs, -10) as $log): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                        <td><?php echo htmlspecialchars($log['member_id']); ?></td>
                        <td><?php echo htmlspecialchars($log['type']); ?></td>
                        <td><small><?php echo htmlspecialchars(substr($log['content'], 0, 100)) . '...'; ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?> 