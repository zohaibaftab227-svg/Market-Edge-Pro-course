<?php
$pageTitle = 'Student Login';
require_once 'includes/header.php';

if (isStudentLoggedIn()) {
    redirect(SITE_URL . '/student-dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $db   = getDB();
        $stmt = $db->prepare("SELECT * FROM students WHERE email=? LIMIT 1");
        $stmt->execute([$email]);
        $student = $stmt->fetch();

        if ($student && password_verify($password, $student['password'])) {
            if ($student['status'] == 0) {
                $error = 'Your account has been deactivated. Please contact support.';
            } else {
                $_SESSION['student_id']    = $student['id'];
                $_SESSION['student_name']  = $student['full_name'];
                $_SESSION['student_email'] = $student['email'];

                flash('success', 'Welcome back, ' . $student['full_name'] . '!');
                redirect(SITE_URL . '/student-dashboard.php');
            }
        } else {
            $error = 'Invalid email or password. Please try again.';
        }
    } else {
        $error = 'Please enter both email and password.';
    }
}
?>

<section class="page-header" style="padding: 60px 0 40px">
  <div class="container" style="max-width: 450px">
    <div style="background: var(--card-bg); border: 1px solid var(--card-border); padding: 40px; border-radius: var(--radius); box-shadow: 0 10px 30px rgba(0,0,0,0.2)">
      <div style="text-align: center; margin-bottom: 30px">
        <div style="width: 60px; height: 60px; background: var(--gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #fff; font-size: 24px">
          <i class="fas fa-user-graduate"></i>
        </div>
        <h1 style="font-size: 24px; margin-bottom: 8px">Student Login</h1>
        <p style="color: var(--text-muted); font-size: 14px">Access your courses and learning dashboard</p>
      </div>

      <?php if ($error): ?>
      <div class="flash-msg flash-error" style="margin-bottom: 20px">
        <i class="fas fa-exclamation-circle"></i> <?= sanitize($error) ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group" style="margin-bottom: 16px">
          <label class="form-label">Email Address</label>
          <div style="position: relative">
            <input type="email" name="email" class="form-input" placeholder="name@example.com" value="<?= sanitize($_POST['email'] ?? '') ?>" style="padding-left: 42px" required autofocus>
            <i class="fas fa-envelope" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted)"></i>
          </div>
        </div>
        <div class="form-group" style="margin-bottom: 24px">
          <label class="form-label">Password</label>
          <div style="position: relative">
            <input type="password" name="password" class="form-input" placeholder="••••••••" style="padding-left: 42px" required>
            <i class="fas fa-lock" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted)"></i>
          </div>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 14px">
          <i class="fas fa-sign-in-alt"></i> Login to Dashboard
        </button>
      </form>

      <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--text-muted)">
        Don't have an account? <a href="<?= SITE_URL ?>/student-signup.php" style="color: var(--primary); font-weight: 600">Sign Up</a>
      </div>
      <div style="text-align: center; margin-top: 15px">
        <a href="<?= SITE_URL ?>/index.php" style="color: var(--text-muted); font-size: 13px"><i class="fas fa-arrow-left"></i> Back to Home</a>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
