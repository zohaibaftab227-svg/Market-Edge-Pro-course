<?php
$pageTitle = 'Enroll';
require_once 'includes/header.php';
$db = getDB();

$courseId = (int)($_GET['id'] ?? 0);

// Fetch course
if ($courseId) {
    $stmt = $db->prepare("SELECT * FROM courses WHERE id=? AND is_active=1");
    $stmt->execute([$courseId]);
    $course = $stmt->fetch();
} else {
    $course = null;
}

if (!$course) {
    ?>
    <section class="page-header"><div class="container"><h1>Course Not Found</h1><p>The course you are looking for does not exist.</p><a href="<?= SITE_URL ?>/courses.php" class="btn btn-primary" style="margin-top:20px"><i class="fas fa-arrow-left"></i> Browse Courses</a></div></section>
    <?php
    require_once 'includes/footer.php';
    exit;
}

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = sanitize($_POST['student_name'] ?? '');
    $email    = sanitize($_POST['student_email'] ?? '');
    $phone    = sanitize($_POST['student_phone'] ?? '');
    $city     = sanitize($_POST['student_city'] ?? '');
    $edu      = sanitize($_POST['education'] ?? '');
    $exp      = sanitize($_POST['experience'] ?? '');
    $payment  = sanitize($_POST['payment_method'] ?? 'Bank Transfer');
    $message  = sanitize($_POST['message'] ?? '');

    if (!$name || !$email || !$phone || !$city) {
        $error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO enrollments (course_id,student_name,student_email,student_phone,student_city,education,experience,payment_method,message,status) VALUES (?,?,?,?,?,?,?,?,?,'pending')");
            $stmt->execute([$course['id'],$name,$email,$phone,$city,$edu,$exp,$payment,$message]);
            // Update enrollment count
            $db->prepare("UPDATE courses SET created_at=created_at WHERE id=?")->execute([$course['id']]);
            // Remove from cart
            removeFromCart($course['id']);
            // Redirect to success page
            redirect(SITE_URL . '/enroll-success.php?name=' . urlencode($name) . '&course=' . urlencode($course['title']));
        } catch (Exception $e) {
            $error = 'Enrollment failed. Please try again or contact us on WhatsApp.';
        }
    }
}
?>
<script>var siteUrl = '<?= SITE_URL ?>';</script>

<section class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= SITE_URL ?>/courses.php">Courses</a>
      <i class="fas fa-chevron-right" style="font-size:10px"></i>
      <span>Enrollment</span>
    </div>
    <div class="section-tag"><i class="fas fa-graduation-cap"></i> Enrollment</div>
    <h1>Enroll in <span style="background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text"><?= sanitize($course['title']) ?></span></h1>
    <p>Fill in your details below to complete your enrollment. We'll contact you within 24 hours.</p>
  </div>
</section>

<section style="padding:60px 0">
  <div class="container">
    <div style="display:grid;grid-template-columns:1.5fr 1fr;gap:40px;align-items:start">

      <!-- Form -->
      <div class="admin-card">
        <div class="admin-card-header"><h2><i class="fas fa-user-edit" style="color:#d4a843"></i> Student Information</h2></div>
        <div style="padding:28px">
          <?php if ($error): ?>
          <div class="flash-msg flash-error"><i class="fas fa-exclamation-circle"></i> <?= sanitize($error) ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="student_name" class="form-input" placeholder="Your full name" value="<?= sanitize($_POST['student_name'] ?? '') ?>" required>
              </div>
              <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="student_email" class="form-input" placeholder="your@email.com" value="<?= sanitize($_POST['student_email'] ?? '') ?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <input type="tel" name="student_phone" class="form-input" placeholder="+92 3XX XXXXXXX" value="<?= sanitize($_POST['student_phone'] ?? '') ?>" required>
              </div>
              <div class="form-group">
                <label class="form-label">City *</label>
                <input type="text" name="student_city" class="form-input" placeholder="e.g. Lahore" value="<?= sanitize($_POST['student_city'] ?? '') ?>" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Education Level</label>
                <select name="education" class="form-select">
                  <option value="">Select education</option>
                  <?php foreach(['Matric','Intermediate','Bachelor\'s','Master\'s','Other'] as $e): ?>
                  <option value="<?= $e ?>" <?= ($_POST['education']??'')===$e?'selected':'' ?>><?= $e ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Prior Experience</label>
                <select name="experience" class="form-select">
                  <option value="None">No experience</option>
                  <option value="Less than 1 year">Less than 1 year</option>
                  <option value="1-2 years">1-2 years</option>
                  <option value="3-5 years">3-5 years</option>
                  <option value="5+ years">5+ years</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Payment Method</label>
              <select name="payment_method" class="form-select">
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="JazzCash">JazzCash</option>
                <option value="EasyPaisa">EasyPaisa</option>
                <option value="Cash">Cash</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Message / Question (Optional)</label>
              <textarea name="message" class="form-textarea" rows="3" placeholder="Any questions about the course?"><?= sanitize($_POST['message'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:15px;font-size:16px">
              <i class="fas fa-graduation-cap"></i> Complete Enrollment
            </button>
            <p style="text-align:center;font-size:12px;color:#8888a0;margin-top:12px">
              <i class="fas fa-shield-alt" style="color:#d4a843"></i> Your information is safe and will not be shared with third parties.
            </p>
          </form>
        </div>
      </div>

      <!-- Course Summary -->
      <div>
        <div class="admin-card" style="position:sticky;top:120px">
          <div class="admin-card-header"><h2><i class="fas fa-info-circle" style="color:#d4a843"></i> Course Summary</h2></div>
          <div style="padding:24px">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.05)">
              <div style="width:52px;height:52px;background:rgba(212,168,67,0.12);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#d4a843;flex-shrink:0">
                <i class="<?= sanitize($course['icon']) ?>"></i>
              </div>
              <div>
                <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0 0 4px"><?= sanitize($course['title']) ?></h3>
                <span style="font-size:12px;color:#8888a0"><?= sanitize($course['category']) ?></span>
              </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px">
              <div style="display:flex;justify-content:space-between;font-size:13px">
                <span style="color:#8888a0"><i class="fas fa-clock" style="color:#d4a843;width:16px"></i> Duration</span>
                <span style="color:#fff;font-weight:600"><?= sanitize($course['duration']) ?></span>
              </div>
              <div style="display:flex;justify-content:space-between;font-size:13px">
                <span style="color:#8888a0"><i class="fas fa-signal" style="color:#d4a843;width:16px"></i> Level</span>
                <span style="color:#fff;font-weight:600"><?= sanitize($course['level']) ?></span>
              </div>
              <div style="display:flex;justify-content:space-between;font-size:13px">
                <span style="color:#8888a0"><i class="fas fa-user-tie" style="color:#d4a843;width:16px"></i> Instructor</span>
                <span style="color:#fff;font-weight:600"><?= sanitize($course['instructor']) ?></span>
              </div>
            </div>
            <div style="padding-top:16px;border-top:2px solid rgba(212,168,67,0.2)">
              <div style="display:flex;justify-content:space-between;align-items:center">
                <span style="color:#8888a0">Course Fee</span>
                <div style="text-align:right">
                  <div style="font-size:24px;font-weight:800;color:#d4a843;font-family:'Syne',sans-serif"><?= formatPrice($course['price']) ?></div>
                  <?php if($course['original_price']): ?>
                  <div style="font-size:12px;color:#8888a0;text-decoration:line-through"><?= formatPrice($course['original_price']) ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div style="margin-top:16px;padding:12px;background:rgba(37,211,102,0.06);border:1px solid rgba(37,211,102,0.15);border-radius:8px;font-size:12px;color:#2ecc71;text-align:center">
              <i class="fab fa-whatsapp"></i> Questions? <a href="https://wa.me/923280732259?text=I want to enroll in <?= urlencode($course['title']) ?>" target="_blank" style="color:#2ecc71;font-weight:700">WhatsApp Us</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
