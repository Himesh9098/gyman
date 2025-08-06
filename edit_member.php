<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
include 'includes/header.php';

$member_id = $_GET['id'] ?? null;
$success = '';
$error = '';

if ($member_id === null) {
    header('Location: members.php?error=Member ID not provided');
    exit();
}

$members = load_members();
if (!isset($members[$member_id])) {
    header('Location: members.php?error=Member not found');
    exit();
}

$member = $members[$member_id];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'] ?? '',
        'age' => $_POST['age'] ?? '',
        'weight' => $_POST['weight'] ?? '',
        'contact' => $_POST['contact'] ?? '',
        'start_date' => $_POST['start_date'] ?? '',
        'end_date' => $_POST['end_date'] ?? '',
        'plan' => $_POST['plan'] ?? '',
        'workout' => $_POST['workout'] ?? '',
        'diet' => $_POST['diet'] ?? '',
        'plan_start' => $_POST['plan_start'] ?? '',
        'plan_end' => $_POST['plan_end'] ?? '',
        'amount_paid' => $_POST['amount_paid'] ?? 0,
        'pending_amount' => $_POST['pending_amount'] ?? 0,
        'last_payment_date' => $_POST['last_payment_date'] ?? null,
        'payment_history' => $member['payment_history'] ?? []
    ];
    
    // Validate required fields
    $required_fields = ['name', 'age', 'weight', 'contact', 'start_date', 'end_date', 'plan'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (empty($missing_fields)) {
        $members[$member_id] = array_merge($member, $data);
        save_members($members);
        
        // Redirect to members page with success message
        header('Location: members.php?success=Member updated successfully!');
        exit();
    } else {
        $error = 'Please fill in all required fields: ' . implode(', ', $missing_fields);
    }
}
?>

<style>
.edit-member-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.form-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    padding: 40px;
    max-width: 800px;
    margin: 0 auto;
}

.form-control-modern {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    padding: 12px 15px;
    background: rgba(255,255,255,0.9);
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white;
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

.form-section {
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
}

.form-section-title {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.icon-large {
    font-size: 2rem;
    margin-bottom: 15px;
}

.back-btn {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 25px;
    padding: 10px 25px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    margin-bottom: 20px;
}

.back-btn:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateY(-2px);
}

.member-info-badge {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 20px;
    font-weight: 600;
}
</style>

<div class="edit-member-container">
    <div class="container">
        <div class="text-center mb-4">
            <a href="members.php" class="back-btn">
                <i class="fas fa-arrow-left me-2"></i> Back to Members
            </a>
        </div>
        
        <h2 class="section-title">
            <i class="fas fa-user-edit icon-large"></i>
            <br>Edit Member
        </h2>
        
        <div class="text-center mb-4">
            <div class="member-info-badge">
                <i class="fas fa-user"></i> <?php echo htmlspecialchars($member['name']); ?>
            </div>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="post">
                <!-- Personal Information -->
                <div class="form-section">
                    <h5 class="form-section-title">
                        <i class="fas fa-user"></i> Personal Information
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-user"></i> Full Name *
                            </label>
                            <input type="text" name="name" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-birthday-cake"></i> Age *
                            </label>
                            <input type="number" name="age" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['age']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-weight"></i> Weight (kg) *
                            </label>
                            <input type="number" name="weight" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['weight']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-phone"></i> Contact Number *
                            </label>
                            <input type="text" name="contact" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['contact']); ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Membership Details -->
                <div class="form-section">
                    <h5 class="form-section-title">
                        <i class="fas fa-calendar-alt"></i> Membership Details
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-plus"></i> Start Date *
                            </label>
                            <input type="date" name="start_date" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['start_date']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-minus"></i> End Date *
                            </label>
                            <input type="date" name="end_date" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['end_date']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-dumbbell"></i> Plan Type *
                            </label>
                            <select name="plan" class="form-control form-control-modern" required>
                                <option value="">Select Plan</option>
                                <option value="Basic" <?php echo $member['plan'] === 'Basic' ? 'selected' : ''; ?>>Basic</option>
                                <option value="Premium" <?php echo $member['plan'] === 'Premium' ? 'selected' : ''; ?>>Premium</option>
                                <option value="VIP" <?php echo $member['plan'] === 'VIP' ? 'selected' : ''; ?>>VIP</option>
                                <option value="Student" <?php echo $member['plan'] === 'Student' ? 'selected' : ''; ?>>Student</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-check"></i> Plan Start Date
                            </label>
                            <input type="date" name="plan_start" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['plan_start']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-times"></i> Plan End Date
                            </label>
                            <input type="date" name="plan_end" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['plan_end']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-day"></i> Last Payment Date
                            </label>
                            <input type="date" name="last_payment_date" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['last_payment_date']); ?>">
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="form-section">
                    <h5 class="form-section-title">
                        <i class="fas fa-money-bill-wave"></i> Payment Information
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-money-bill"></i> Amount Paid
                            </label>
                            <input type="number" name="amount_paid" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['amount_paid']); ?>" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-exclamation-triangle"></i> Pending Amount
                            </label>
                            <input type="number" name="pending_amount" class="form-control form-control-modern" 
                                   value="<?php echo htmlspecialchars($member['pending_amount']); ?>" step="0.01">
                        </div>
                    </div>
                </div>

                <!-- Fitness Plans -->
                <div class="form-section">
                    <h5 class="form-section-title">
                        <i class="fas fa-running"></i> Fitness Plans
                    </h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-dumbbell"></i> Workout Plan
                            </label>
                            <textarea name="workout" class="form-control form-control-modern" rows="3" 
                                      placeholder="Enter workout plan details..."><?php echo htmlspecialchars($member['workout']); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-apple-alt"></i> Diet Plan
                            </label>
                            <textarea name="diet" class="form-control form-control-modern" rows="3" 
                                      placeholder="Enter diet plan details..."><?php echo htmlspecialchars($member['diet']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-modern me-3">
                        <i class="fas fa-save"></i> Update Member
                    </button>
                    <a href="members.php" class="btn btn-secondary btn-modern">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php include 'includes/footer.php'; ?> 