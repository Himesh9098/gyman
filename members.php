<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php'; // Add translation support
include 'includes/header.php';

$members = load_members();
$delete_id = $_GET['delete'] ?? null;
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

if ($delete_id !== null) {
    unset($members[$delete_id]);
    $members = array_values($members);
    save_members($members);
    $success = 'Member deleted successfully!';
}
?>

<style>
.members-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.members-table-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    overflow: hidden;
}

.members-table {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-modern {
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.member-row {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.member-row:hover {
    background: rgba(102, 126, 234, 0.1);
    transform: translateX(5px);
}

.member-row.due-payment {
    border-left-color: #ffc107;
    background: rgba(255, 193, 7, 0.1);
}

.member-row.expired {
    border-left-color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

.member-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.1rem;
}

.payment-badge {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-active {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.status-expired {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
}

.modal-modern .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.modal-modern .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px 20px 0 0;
}

.modal-modern .modal-body {
    padding: 25px;
}

.icon-large {
    font-size: 2rem;
    margin-bottom: 15px;
}

.section-title {
    color: white;
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
}

.alert-modern {
    border-radius: 15px;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.add-member-section {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    padding: 30px;
    margin-bottom: 30px;
    text-align: center;
}

.add-member-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 15px 40px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 30px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.add-member-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    color: white;
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.stats-cards {
    margin-bottom: 30px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #667eea;
    margin-bottom: 5px;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}
</style>

<div class="members-container">
    <div class="container">
        <h2 class="section-title">
            <i class="fas fa-users icon-large"></i>
            <br>Member Management
        </h2>
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Add Member Section -->
        <div class="add-member-section">
            <h4 class="mb-4">
                <i class="fas fa-user-plus"></i> Add New Member
            </h4>
            <p class="text-muted mb-4">Click the button below to add a new member to the gym</p>
            <a href="add_member.php" class="btn add-member-btn">
                <i class="fas fa-plus"></i> Add New Member
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row stats-cards">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($members); ?></div>
                    <div class="stat-label">Total Members</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($members, function($m) { return strtotime($m['end_date']) >= time(); })); ?></div>
                    <div class="stat-label">Active Members</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($members, function($m) { return $m['pending_amount'] > 0; })); ?></div>
                    <div class="stat-label">Due Payments</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($members, function($m) { return strtotime($m['end_date']) < time(); })); ?></div>
                    <div class="stat-label">Expired Members</div>
                </div>
            </div>
        </div>

        <!-- Members List -->
        <div class="members-table-card">
            <div class="card-header bg-transparent border-0 p-4">
                <h4 class="mb-0">
                    <i class="fas fa-list"></i> Member List
                </h4>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 members-table">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-user"></i> Member</th>
                            <th><i class="fas fa-dumbbell"></i> Plan</th>
                            <th><i class="fas fa-money-bill-wave"></i> Payment</th>
                            <th><i class="fas fa-calendar-check"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $i => $m): ?>
                            <?php 
                            $isExpired = strtotime($m['end_date']) < time();
                            $hasPendingPayment = $m['pending_amount'] > 0;
                            $rowClass = '';
                            if ($isExpired) $rowClass = 'expired';
                            elseif ($hasPendingPayment) $rowClass = 'due-payment';
                            ?>
                            <tr class="member-row <?php echo $rowClass; ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar me-3">
                                            <?php echo substr($m['name'], 0, 1); ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($m['name']); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-phone"></i> <?php echo htmlspecialchars($m['contact']); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?php echo htmlspecialchars($m['plan']); ?></span>
                                </td>
                                <td>
                                    <?php if ($hasPendingPayment): ?>
                                        <span class="payment-badge">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <?php echo formatCurrency($m['pending_amount']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Paid
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($isExpired): ?>
                                        <span class="status-badge status-expired">
                                            <i class="fas fa-times-circle"></i> Expired
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-active">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="edit_member.php?id=<?php echo $i; ?>" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" data-bs-target="#paymentModal<?php echo $i; ?>" 
                                                title="<?php echo t('add_payment'); ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <a href="members.php?delete=<?php echo $i; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Delete this member?');" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modals -->
<?php foreach ($members as $i => $m): ?>
<div class="modal fade modal-modern" id="paymentModal<?php echo $i; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-money-bill-wave"></i> 
                    <?php echo t('add_payment'); ?> - <?php echo htmlspecialchars($m['name']); ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="add_payment.php">
                <div class="modal-body">
                    <input type="hidden" name="member_id" value="<?php echo $i; ?>">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-money-bill"></i> Payment Amount
                        </label>
                        <input type="number" name="payment_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-calendar"></i> Payment Date
                        </label>
                        <input type="date" name="payment_date" class="form-control" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Current Pending: <?php echo formatCurrency($m['pending_amount']); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-modern">
                        <i class="fas fa-plus"></i> <?php echo t('add_payment'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php include 'includes/footer.php'; ?>