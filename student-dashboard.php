<?php
$pageTitle = 'Student Dashboard';
require_once 'includes/header.php';
requireStudent();

$db = getDB();
$studentId = $_SESSION['student_id'];

// Fetch enrolled courses
$stmt = $db->prepare("
    SELECT c.*, e.enrolled_at, e.status as enroll_status 
    FROM enrollments e 
    JOIN courses c ON e.course_id = c.id 
    WHERE e.student_id = ? OR e.student_email = ?
    ORDER BY e.enrolled_at DESC
");
$stmt->execute([$studentId, $_SESSION['student_email']]);
$enrollments = $stmt->fetchAll();

// Group by status
$confirmed = array_filter($enrollments, fn($e) => $e['enroll_status'] === 'confirmed');
$pending   = array_filter($enrollments, fn($e) => $e['enroll_status'] === 'pending');
?>

<section class="page-header" style="padding: 40px 0">
  <div class="container">
    <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap">
      <div style="width: 80px; height: 80px; background: var(--gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 32px; font-weight: 800">
        <?= strtoupper(substr($_SESSION['student_name'], 0, 1)) ?>
      </div>
      <div>
        <h1 style="margin: 0; font-size: 28px">Welcome, <?= explode(' ', $_SESSION['student_name'])[0] ?>!</h1>
        <p style="color: var(--text-muted); margin: 5px 0 0">Manage your courses and learning progress.</p>
      </div>
    </div>
  </div>
</section>

<section style="padding: 40px 0 80px">
  <div class="container">
    <?php if (empty($enrollments)): ?>
    <div style="text-align: center; padding: 60px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius)">
      <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px"><i class="fas fa-graduation-cap"></i></div>
      <h3>No courses yet</h3>
      <p style="color: var(--text-muted); margin-bottom: 30px">You haven't enrolled in any courses yet. Start your learning journey today!</p>
      <a href="<?= SITE_URL ?>/courses.php" class="btn btn-primary">Browse All Courses</a>
    </div>
    <?php else: ?>
    
    <!-- Confirmed Courses -->
    <div style="margin-bottom: 50px">
      <h2 style="font-size: 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px">
        <i class="fas fa-play-circle" style="color: var(--primary)"></i> My Active Courses
      </h2>
      
      <?php if (empty($confirmed)): ?>
      <div style="padding: 30px; background: rgba(255,255,255,0.03); border: 1px dashed var(--card-border); border-radius: var(--radius); text-align: center; color: var(--text-muted)">
        Your course enrollments are waiting for confirmation.
      </div>
      <?php else: ?>
      <div class="courses-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr))">
        <?php foreach($confirmed as $c): ?>
        <div class="course-card">
          <div class="course-card-top" style="padding-bottom: 20px">
            <div class="course-card-icon"><i class="<?= sanitize($c['icon']) ?>"></i></div>
            <h3 class="course-card-title"><?= sanitize($c['title']) ?></h3>
            <p class="course-card-desc"><?= truncate($c['description'], 80) ?></p>
          </div>
          <div class="course-card-bottom" style="border-top: 1px solid var(--card-border); padding-top: 20px">
            <a href="<?= SITE_URL ?>/course-play.php?id=<?= $c['id'] ?>" class="btn btn-primary" style="width: 100%; justify-content: center">
              <i class="fas fa-play"></i> Start Learning
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Pending/Others -->
    <?php if (!empty($pending)): ?>
    <div>
      <h2 style="font-size: 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; color: var(--text-muted)">
        <i class="fas fa-clock"></i> Pending Confirmations
      </h2>
      <div style="display: flex; flex-direction: column; gap: 15px">
        <?php foreach($pending as $p): ?>
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius); flex-wrap: wrap; gap: 15px">
          <div style="display: flex; align-items: center; gap: 15px">
             <div style="width: 40px; height: 40px; background: rgba(243,156,18,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #f39c12">
               <i class="<?= sanitize($p['icon']) ?>"></i>
             </div>
             <div>
               <h4 style="margin: 0"><?= sanitize($p['title']) ?></h4>
               <small style="color: var(--text-muted)">Enrolled on <?= date('M d, Y', strtotime($p['enrolled_at'])) ?></small>
             </div>
          </div>
          <div style="display: flex; align-items: center; gap: 10px">
            <span class="badge" style="background: rgba(243,156,18,0.2); color: #f39c12">Pending Approval</span>
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Hi! I paid for the <?= urlencode($p['title']) ?> course. Please confirm my enrollment." target="_blank" class="btn btn-sm btn-outline" style="font-size: 11px">
              <i class="fab fa-whatsapp"></i> Send Proof
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php endif; ?>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
