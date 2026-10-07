<?php
$pageTitle = 'Student Sign Up';
require_once 'includes/header.php';

if (isStudentLoggedIn()) {
    redirect(SITE_URL . '/student-dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = sanitize($_POST['full_name'] ?? '');
    $email    = sanitize($_POST['email'] ?? '');
    $phone    = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($name && $email && $password) {
        if ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            $db = getDB();
            // Check if email exists
            $stmt = $db->prepare("SELECT id FROM students WHERE email=? LIMIT 1");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email is already registered. Please login.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO students (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $hashed]);
                
                $studentId = $db->lastInsertId();
                $_SESSION['student_id']    = $studentId;
                $_SESSION['student_name']  = $name;
                $_SESSION['student_email'] = $email;

                flash('success', 'Registration successful! Welcome to Market Edge Pro.');
                redirect(SITE_URL . '/student-dashboard.php');
            }
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}
?>

<section class="page-header" style="padding: 60px 0 40px">
  <div class="container" style="max-width: 500px">
    <div style="background: var(--card-bg); border: 1px solid var(--card-border); padding: 40px; border-radius: var(--radius); box-shadow: 0 10px 30px rgba(0,0,0,0.2)">
      <div style="text-align: center; margin-bottom: 30px">
        <div style="width: 60px; height: 60px; background: var(--gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 24px">
          <i class="fas fa-user-plus"></i>
        </div>
        <h1 style="font-size: 24px; margin-bottom: 8px">Create Account</h1>
        <p style="color: var(--text-muted); font-size: 14px">Join Pakistan's leading marketing academy</p>
      </div>

      <?php if ($error): ?>
      <div class="flash-msg flash-error" style="margin-bottom: 20px">
        <i class="fas fa-exclamation-circle"></i> <?= sanitize($error) ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group" style="margin-bottom: 16px">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-input" placeholder="Enter your full name" value="<?= sanitize($_POST['full_name'] ?? '') ?>" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-input" placeholder="name@example.com" value="<?= sanitize($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-input" placeholder="e.g. 03001234567" value="<?= sanitize($_POST['phone'] ?? '') ?>">
        </div>
        <div class="form-group" style="margin-bottom: 16px">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-input" placeholder="Minimum 6 characters" required>
        </div>
        <div class="form-group" style="margin-bottom: 24px">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="confirm_password" class="form-input" placeholder="Repeat your password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 14px">
          <i class="fas fa-user-check"></i> Register Now
        </button>
      </form>

      <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-muted)">
        Already have an account? <a href="<?= SITE_URL ?>/student-login.php" style="color: var(--primary); font-weight: 600">Login</a>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
