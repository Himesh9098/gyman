<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
include 'includes/header.php';

$members = load_members();
$member_id = $_GET['id'] ?? null;
$success = '';
if ($member_id !== null && isset($members[$member_id])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = 'assets/img/member_' . $member_id . '_' . time() . '.' . $ext;
        if (!is_dir('assets/img')) mkdir('assets/img', 0777, true);
        move_uploaded_file($_FILES['photo']['tmp_name'], $filename);
        $members[$member_id]['photo'] = $filename;
        save_members($members);
        $success = 'Photo uploaded successfully!';
    }
    $member = $members[$member_id];
}
?>

<style>
.id-card-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.member-sidebar {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.member-item {
    background: rgba(255,255,255,0.9);
    border-radius: 10px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
}

.member-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.member-item.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #fff;
}

.id-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    overflow: hidden;
    position: relative;
    max-width: 400px;
    margin: 0 auto;
}

.id-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
}

.id-card-header {
    background: rgba(255,255,255,0.1);
    padding: 20px;
    text-align: center;
    color: white;
}

.id-card-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.id-card-body {
    padding: 25px;
    color: white;
}

.id-card-info {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    backdrop-filter: blur(10px);
}

.id-card-label {
    font-size: 0.8rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.id-card-value {
    font-size: 1.1rem;
    font-weight: 600;
    margin-top: 5px;
}

.upload-section {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    margin-top: 20px;
}

.form-control-modern {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    padding: 12px 15px;
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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

.photo-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    color: white;
    font-size: 2rem;
}

.gym-logo {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.validity-badge {
    background: rgba(255,255,255,0.2);
    border-radius: 20px;
    padding: 5px 15px;
    font-size: 0.8rem;
    display: inline-block;
    margin-top: 10px;
}

.icon-large {
    font-size: 2rem;
    margin-bottom: 10px;
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

<div class="id-card-container">
    <div class="container">
        <h2 class="text-center text-white mb-4">
            <i class="fas fa-id-card icon-large"></i>
            <br>Digital ID Card Generator
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

            <!-- Success Message -->
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Digital ID Card -->
            <div class="id-card">
                <div class="id-card-header">
                    <div class="gym-logo">
                        <i class="fas fa-dumbbell"></i> GYM MEMBERSHIP
                    </div>
                    <div class="mb-3">
                        <?php if (!empty($member['photo']) && file_exists($member['photo'])): ?>
                            <img src="<?php echo $member['photo']; ?>" class="id-card-photo" alt="Member Photo">
                        <?php else: ?>
                            <div class="photo-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h4 class="mb-2"><?php echo htmlspecialchars($member['name']); ?></h4>
                    <div class="validity-badge">
                        <i class="fas fa-calendar-check"></i> 
                        Valid: <?php echo formatDate($member['start_date']); ?> - <?php echo formatDate($member['end_date']); ?>
                    </div>
                </div>
                
                <div class="id-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="id-card-info">
                                <div class="id-card-label">Plan Type</div>
                                <div class="id-card-value"><?php echo htmlspecialchars($member['plan']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="id-card-info">
                                <div class="id-card-label">Age</div>
                                <div class="id-card-value"><?php echo htmlspecialchars($member['age']); ?> years</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="id-card-info">
                                <div class="id-card-label">Weight</div>
                                <div class="id-card-value"><?php echo htmlspecialchars($member['weight']); ?> kg</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="id-card-info">
                                <div class="id-card-label">Contact</div>
                                <div class="id-card-value"><?php echo htmlspecialchars($member['contact']); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="id-card-info">
                        <div class="id-card-label">Membership ID</div>
                        <div class="id-card-value">#<?php echo str_pad($member['id'], 6, '0', STR_PAD_LEFT); ?></div>
                    </div>
                </div>
            </div>

            <!-- Photo Upload Section -->
            <div class="upload-section">
                <h5 class="text-center mb-4">
                    <i class="fas fa-camera"></i> Update Member Photo
                </h5>
                <form method="post" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">
                            <i class="fas fa-image"></i> Select Photo
                        </label>
                        <input type="file" name="photo" class="form-control form-control-modern" 
                               accept="image/*" required>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> 
                            Supported formats: JPG, PNG, GIF (Max 5MB)
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-modern w-100">
                            <i class="fas fa-upload"></i> Upload Photo
                        </button>
                    </div>
                </form>
            </div>

        <?php else: ?>
            <div class="text-center py-5">
                <div class="id-card" style="max-width: 500px; margin: 0 auto;">
                    <div class="id-card-header">
                        <i class="fas fa-id-card fa-4x mb-3"></i>
                        <h3>Digital ID Card Generator</h3>
                        <p class="mb-0">Select a member from the list below to view their digital ID card</p>
                    </div>
                </div>
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
                         onclick="window.location.href='id_card.php?id=<?php echo $i; ?>'">
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