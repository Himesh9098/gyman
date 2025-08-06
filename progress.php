<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
include 'includes/header.php';

$members = load_members();
$member_id = $_GET['id'] ?? null;
$success = '';
if ($member_id !== null && isset($members[$member_id])) {
    if (!isset($members[$member_id]['progress'])) $members[$member_id]['progress'] = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $record = [
            'month' => $_POST['month'] ?? '',
            'weight' => $_POST['weight'] ?? '',
            'bmi' => $_POST['bmi'] ?? '',
            'attendance' => $_POST['attendance'] ?? '',
        ];
        $members[$member_id]['progress'][] = $record;
        save_members($members);
        $success = 'Progress added successfully!';
    }
    $progress = $members[$member_id]['progress'];
    $member = $members[$member_id];
}
?>

<style>
.progress-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.member-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.progress-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-card {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.member-list-item {
    background: rgba(255,255,255,0.9);
    border-radius: 10px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
}

.member-list-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.member-list-item.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #fff;
}

.progress-table {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.progress-form {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-modern {
    border-radius: 25px;
    padding: 10px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.progress-bar-custom {
    height: 8px;
    border-radius: 10px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.icon-large {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.member-details-card {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.member-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 2rem;
    margin: 0 auto 15px;
}

.member-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.member-info-item {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 15px;
    text-align: center;
}

.member-info-label {
    font-size: 0.8rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.member-info-value {
    font-size: 1.1rem;
    font-weight: 600;
}

.members-list-section {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    margin-top: 20px;
}

.members-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.member-grid-item {
    background: rgba(255,255,255,0.9);
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
}

.member-grid-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-color: #667eea;
}

.member-grid-item.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.member-grid-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    margin: 0 auto 10px;
}

.member-grid-name {
    font-weight: 600;
    margin-bottom: 5px;
}

.member-grid-plan {
    font-size: 0.8rem;
    opacity: 0.8;
}
</style>

<div class="progress-container">
    <div class="container">
        <h2 class="text-center text-white mb-4">
            <i class="fas fa-chart-line icon-large"></i>
            <br>Progress Tracking
        </h2>

        <?php if ($member_id !== null && isset($member)): ?>
            <!-- Selected Member Details -->
            <div class="member-details-card">
                <div class="text-center mb-4">
                    <div class="member-avatar">
                        <?php echo substr($member['name'], 0, 1); ?>
                    </div>
                    <h3 class="mb-3"><?php echo htmlspecialchars($member['name']); ?></h3>
                    <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($member['plan']); ?></span>
                </div>
                
                <div class="member-info-grid">
                    <div class="member-info-item">
                        <div class="member-info-label">Age</div>
                        <div class="member-info-value"><?php echo htmlspecialchars($member['age']); ?> years</div>
                    </div>
                    <div class="member-info-item">
                        <div class="member-info-label">Weight</div>
                        <div class="member-info-value"><?php echo htmlspecialchars($member['weight']); ?> kg</div>
                    </div>
                    <div class="member-info-item">
                        <div class="member-info-label">Contact</div>
                        <div class="member-info-value"><?php echo htmlspecialchars($member['contact']); ?></div>
                    </div>
                    <div class="member-info-item">
                        <div class="member-info-label">Membership</div>
                        <div class="member-info-value"><?php echo formatDate($member['start_date']); ?> - <?php echo formatDate($member['end_date']); ?></div>
                    </div>
                    <div class="member-info-item">
                        <div class="member-info-label">BMI</div>
                        <div class="member-info-value"><?php echo number_format($member['weight'] / pow($member['age']/100, 2), 1); ?></div>
                    </div>
                    <div class="member-info-item">
                        <div class="member-info-label">Status</div>
                        <div class="member-info-value">
                            <?php if (strtotime($member['end_date']) < time()): ?>
                                <span class="badge bg-danger">Expired</span>
                            <?php else: ?>
                                <span class="badge bg-success">Active</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card p-3 text-center">
                        <i class="fas fa-weight icon-large"></i>
                        <h5>Current Weight</h5>
                        <h3><?php echo $member['weight']; ?> kg</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card p-3 text-center">
                        <i class="fas fa-heartbeat icon-large"></i>
                        <h5>BMI</h5>
                        <h3><?php echo number_format($member['weight'] / pow($member['age']/100, 2), 1); ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card p-3 text-center">
                        <i class="fas fa-calendar-check icon-large"></i>
                        <h5>Plan</h5>
                        <h3><?php echo htmlspecialchars($member['plan']); ?></h3>
                    </div>
                </div>
            </div>

            <!-- Progress Form -->
            <div class="progress-form mb-4">
                <h4 class="text-center mb-4">
                    <i class="fas fa-chart-line"></i> Add Progress Record
                </h4>
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="post" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-calendar"></i> Month</label>
                        <input type="month" name="month" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-weight"></i> Weight (kg)</label>
                        <input type="number" step="0.1" name="weight" class="form-control" placeholder="Enter weight" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-heartbeat"></i> BMI</label>
                        <input type="number" step="0.1" name="bmi" class="form-control" placeholder="Enter BMI" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-calendar-check"></i> Attendance</label>
                        <input type="number" name="attendance" class="form-control" placeholder="Days attended" required>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary btn-modern">
                            <i class="fas fa-plus"></i> Add Progress Record
                        </button>
                    </div>
                </form>
            </div>

            <!-- Progress History -->
            <div class="progress-card p-4">
                <h4 class="text-center mb-4">
                    <i class="fas fa-history"></i> Progress History
                </h4>
                <?php if (count($progress) > 0): ?>
                    <div class="progress-table">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-calendar"></i> Month</th>
                                    <th><i class="fas fa-weight"></i> Weight (kg)</th>
                                    <th><i class="fas fa-heartbeat"></i> BMI</th>
                                    <th><i class="fas fa-calendar-check"></i> Attendance</th>
                                    <th><i class="fas fa-chart-line"></i> Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($progress as $p): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($p['month']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($p['weight']); ?> kg</td>
                                        <td><?php echo htmlspecialchars($p['bmi']); ?></td>
                                        <td><?php echo htmlspecialchars($p['attendance']); ?> days</td>
                                        <td>
                                            <div class="progress progress-bar-custom">
                                                <div class="progress-bar" style="width: <?php echo min(100, ($p['attendance'] / 30) * 100); ?>%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No progress records yet</h5>
                        <p class="text-muted">Start tracking your fitness journey!</p>
                    </div>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="member-card p-5 text-center">
                <i class="fas fa-user-plus fa-4x text-muted mb-4"></i>
                <h3 class="text-muted">Select a Member</h3>
                <p class="text-muted">Choose a member from the list below to view and add their progress records.</p>
            </div>
        <?php endif; ?>

        <!-- All Members List -->
        <div class="members-list-section">
            <h4 class="text-center mb-4">
                <i class="fas fa-users"></i> All Members
            </h4>
            <div class="members-grid">
                <?php foreach ($members as $i => $m): ?>
                    <div class="member-grid-item <?php if ($member_id == $i) echo 'active'; ?>" 
                         onclick="window.location.href='progress.php?id=<?php echo $i; ?>'">
                        <div class="member-grid-avatar">
                            <?php echo substr($m['name'], 0, 1); ?>
                        </div>
                        <div class="member-grid-name"><?php echo htmlspecialchars($m['name']); ?></div>
                        <div class="member-grid-plan"><?php echo htmlspecialchars($m['plan']); ?></div>
                        <?php if (strtotime($m['end_date']) < time()): ?>
                            <small class="text-danger"><i class="fas fa-times-circle"></i> Expired</small>
                        <?php else: ?>
                            <small class="text-success"><i class="fas fa-check-circle"></i> Active</small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php include 'includes/footer.php'; ?>