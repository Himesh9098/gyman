<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Get data using helper functions
$members = load_members();
$total_members = count($members);
$active_members = 0;
$due_members = getDueMembers();
$expiring_soon = getExpiringSoonMembers();
$expired_today = getExpiredTodayMembers();
$total_due = getTotalDueAmount();
$monthly_income = getMonthlyIncome();

foreach ($members as $member) {
    if (isset($member['plan_end']) && $member['plan_end'] >= date('Y-m-d')) {
        $active_members++;
    }
}
?>
<div class="row mb-4">
    <div class="col-md-3 mb-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total Members</h5>
                <p class="display-6"><?php echo $total_members; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Active Members</h5>
                <p class="display-6"><?php echo $active_members; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title"><?php echo t('monthly_income'); ?></h5>
                <p class="display-6"><?php echo formatCurrency($monthly_income); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title"><?php echo t('total_due'); ?></h5>
                <p class="display-6"><?php echo formatCurrency($total_due); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Due Members Section -->
<?php if (count($due_members) > 0): ?>
<div class="row mb-4">
    <div class="col-12">
        <h4><?php echo t('due_members'); ?></h4>
        <div class="row">
            <?php foreach ($due_members as $member): ?>
            <div class="col-md-4 mb-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($member['name']); ?></h5>
                        <p class="mb-1">Plan: <?php echo htmlspecialchars($member['plan']); ?></p>
                        <p class="mb-1">Contact: <?php echo htmlspecialchars($member['contact']); ?></p>
                        <p class="mb-1"><?php echo t('pending_amount'); ?>: <?php echo formatCurrency($member['pending_amount']); ?></p>
                        <span class="badge bg-warning text-dark">Payment Due</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Expiring Members Section -->
<div class="row mb-4">
    <div class="col-12">
        <h4><?php echo t('expiring_soon'); ?></h4>
        <div class="row">
            <?php if (count($expiring_soon) === 0): ?>
                <div class="col-12"><div class="alert alert-success">No memberships expiring soon!</div></div>
            <?php endif; ?>
            <?php foreach ($expiring_soon as $member): ?>
            <div class="col-md-4 mb-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($member['name']); ?></h5>
                        <p class="mb-1">Plan: <?php echo htmlspecialchars($member['plan']); ?></p>
                        <p class="mb-1">Expiry: <?php echo formatDate($member['plan_end']); ?></p>
                        <p class="mb-1"><?php echo t('pending_amount'); ?>: <?php echo formatCurrency($member['pending_amount']); ?></p>
                        <span class="badge bg-warning text-dark">Expires in <?php echo $member['days_left']; ?> days</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Expired Today Section -->
<?php if (count($expired_today) > 0): ?>
<div class="row mb-4">
    <div class="col-12">
        <h4><?php echo t('expired_today'); ?></h4>
        <div class="row">
            <?php foreach ($expired_today as $member): ?>
            <div class="col-md-4 mb-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($member['name']); ?></h5>
                        <p class="mb-1">Plan: <?php echo htmlspecialchars($member['plan']); ?></p>
                        <p class="mb-1">Expiry: <?php echo formatDate($member['plan_end']); ?></p>
                        <p class="mb-1"><?php echo t('pending_amount'); ?>: <?php echo formatCurrency($member['pending_amount']); ?></p>
                        <span class="badge bg-danger">Expires Today</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>