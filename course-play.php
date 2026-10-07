<?php
$pageTitle = 'Course Player';
require_once 'includes/header.php';
requireStudent();

$db = getDB();
$courseId = (int)($_GET['id'] ?? 0);
$lessonId = (int)($_GET['lesson'] ?? 0);
$studentId = $_SESSION['student_id'];

// Verify enrollment
$stmt = $db->prepare("
    SELECT e.id FROM enrollments e 
    WHERE e.course_id = ? AND (e.student_id = ? OR e.student_email = ?) AND e.status = 'confirmed'
");
$stmt->execute([$courseId, $studentId, $_SESSION['student_email']]);
if (!$stmt->fetch()) {
    flash('error', 'You are not enrolled in this course or your enrollment is not yet confirmed.');
    redirect(SITE_URL . '/student-dashboard.php');
}

// Fetch course & lessons
$stmt = $db->prepare("SELECT * FROM courses WHERE id=?");
$stmt->execute([$courseId]);
$course = $stmt->fetch();

$stmt = $db->prepare("SELECT * FROM lessons WHERE course_id=? ORDER BY order_num ASC, id ASC");
$stmt->execute([$courseId]);
$lessons = $stmt->fetchAll();

// Current lesson
$currentLesson = null;
if ($lessonId) {
    foreach($lessons as $l) { if($l['id'] == $lessonId) { $currentLesson = $l; break; } }
}
if (!$currentLesson && !empty($lessons)) {
    $currentLesson = $lessons[0];
}
?>

<section style="background: #0a0a0c; min-height: 100vh; padding-top: 20px">
  <div class="container-fluid" style="max-width: 1600px; margin: 0 auto; padding: 0 20px">
    
    <!-- Player Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; color: #fff">
      <div style="display: flex; align-items: center; gap: 15px">
        <a href="<?= SITE_URL ?>/student-dashboard.php" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #fff"><i class="fas fa-arrow-left"></i></a>
        <div>
          <h1 style="font-size: 18px; margin: 0"><?= sanitize($course['title']) ?></h1>
          <small style="color: var(--text-muted)"><?= $currentLesson ? 'Lesson: ' . sanitize($currentLesson['title']) : 'No lessons found' ?></small>
        </div>
      </div>
      <div style="display: flex; gap: 10px">
        <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" class="btn btn-sm btn-outline" style="border-color: rgba(255,255,255,0.2); color: #fff"><i class="fab fa-whatsapp"></i> Get Support</a>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 25px; align-items: start">
      
      <!-- Left: Video & Content -->
      <div class="player-left">
        <?php if ($currentLesson): ?>
        <div style="position: relative; padding-top: 56.25%; background: #000; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1)">
          <?php if (strpos($currentLesson['video_url'], 'youtube.com') !== false || strpos($currentLesson['video_url'], 'youtu.be') !== false): 
            $vid = '';
            if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $currentLesson['video_url'], $match)) {
              $vid = $match[1];
            }
          ?>
          <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%" src="https://www.youtube.com/embed/<?= $vid ?>" frameborder="0" allowfullscreen></iframe>
          <?php else: ?>
          <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted)">
            <div style="text-align: center">
              <i class="fas fa-video-slash" style="font-size: 48px; margin-bottom: 15px"></i>
              <p>Video URL: <a href="<?= sanitize($currentLesson['video_url']) ?>" target="_blank" style="color: var(--primary)"><?= sanitize($currentLesson['video_url']) ?></a></p>
            </div>
          </div>
          <?php endif; ?>
        </div>
        
        <div style="margin-top: 30px; background: var(--card-bg); border: 1px solid var(--card-border); padding: 30px; border-radius: 12px; color: #fff">
          <h2 style="font-size: 22px; margin-bottom: 15px"><?= sanitize($currentLesson['title']) ?></h2>
          <div class="lesson-content" style="color: var(--text-muted); line-height: 1.7">
            <?= $currentLesson['content'] ?: 'No additional description for this lesson.' ?>
          </div>
        </div>
        <?php else: ?>
        <div style="padding: 100px; text-align: center; background: var(--card-bg); border-radius: 12px; color: var(--text-muted)">
          <i class="fas fa-film" style="font-size: 64px; margin-bottom: 20px"></i>
          <h3>No lessons available yet</h3>
          <p>Please check back later or contact support.</p>
        </div>
        <?php endif; ?>
      </div>

      <!-- Right: Playlist -->
      <div class="player-right" style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 12px; overflow: hidden; position: sticky; top: 20px">
        <div style="padding: 20px; border-bottom: 1px solid var(--card-border); background: rgba(255,255,255,0.02)">
          <h3 style="font-size: 16px; margin: 0; color: #fff">Course Content</h3>
          <small style="color: var(--text-muted)"><?= count($lessons) ?> Lessons</small>
        </div>
        <div style="max-height: calc(100vh - 180px); overflow-y: auto">
          <?php foreach($lessons as $i=>$l): ?>
          <a href="?id=<?= $courseId ?>&lesson=<?= $l['id'] ?>" style="display: flex; gap: 15px; padding: 15px 20px; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s; background: <?= ($currentLesson && $currentLesson['id'] == $l['id']) ? 'rgba(108,99,255,0.1)' : 'transparent' ?>">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: <?= ($currentLesson && $currentLesson['id'] == $l['id']) ? 'var(--primary)' : 'rgba(255,255,255,0.1)' ?>; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; flex-shrink: 0">
              <?= $i+1 ?>
            </div>
            <div style="flex: 1">
              <div style="font-size: 14px; color: <?= ($currentLesson && $currentLesson['id'] == $l['id']) ? '#fff' : 'var(--text-muted)' ?>; font-weight: 500; line-height: 1.4"><?= sanitize($l['title']) ?></div>
              <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px"><i class="far fa-clock"></i> <?= sanitize($l['duration'] ?: '10:00') ?></div>
            </div>
            <?php if($currentLesson && $currentLesson['id'] == $l['id']): ?>
            <div style="color: var(--primary); font-size: 14px"><i class="fas fa-play"></i></div>
            <?php endif; ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
