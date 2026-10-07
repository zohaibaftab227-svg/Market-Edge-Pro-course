<?php
$pageTitle = 'Enrollment Successful';
require_once 'includes/header.php';
$name   = sanitize($_GET['name'] ?? 'Student');
$course = sanitize($_GET['course'] ?? 'the course');
?>
<script>var siteUrl = '<?= SITE_URL ?>';</script>
<section style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:80px 24px">
  <div style="text-align:center;max-width:560px;margin:0 auto">
    <div style="width:100px;height:100px;background:rgba(46,204,113,0.1);border:2px solid #2ecc71;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 28px;font-size:44px;color:#2ecc71">
      <i class="fas fa-check"></i>
    </div>
    <div style="display:inline-block;background:rgba(212,168,67,0.1);border:1px solid rgba(212,168,67,0.25);color:#d4a843;padding:5px 16px;border-radius:100px;font-size:12px;font-weight:700;letter-spacing:1px;margin-bottom:20px">ENROLLMENT RECEIVED</div>
    <h1 style="font-family:'Syne',sans-serif;font-size:36px;font-weight:800;color:#fff;margin-bottom:16px">Congratulations, <?= $name ?>! 🎉</h1>
    <p style="color:#8888a0;font-size:17px;margin-bottom:12px">Your enrollment for <strong style="color:#d4a843"><?= $course ?></strong> has been received successfully.</p>
    <p style="color:#8888a0;font-size:15px;margin-bottom:36px">Our team will contact you within <strong style="color:#fff">24 hours</strong> on WhatsApp or Email to confirm your enrollment and share payment details.</p>

    <div style="background:#16161f;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:24px;margin-bottom:32px;text-align:left">
      <h4 style="font-family:'Syne',sans-serif;color:#fff;margin:0 0 16px;font-size:15px"><i class="fas fa-list-check" style="color:#d4a843;margin-right:8px"></i> What happens next?</h4>
      <div style="display:flex;flex-direction:column;gap:12px">
        <?php $steps = ['Our team reviews your enrollment request','We contact you within 24 hours on WhatsApp','You complete the payment','You get access to course materials']; ?>
        <?php foreach($steps as $i=>$s): ?>
        <div style="display:flex;gap:12px;align-items:flex-start">
          <span style="width:24px;height:24px;border-radius:50%;background:rgba(212,168,67,0.15);color:#d4a843;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0"><?= $i+1 ?></span>
          <span style="color:#8888a0;font-size:14px"><?= $s ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
      <a href="https://wa.me/923280732259?text=Hi! I just enrolled in <?= urlencode($course) ?>. My name is <?= urlencode($name) ?>." target="_blank" class="btn btn-primary">
        <i class="fab fa-whatsapp"></i> Contact on WhatsApp
      </a>
      <a href="<?= SITE_URL ?>/courses.php" class="btn btn-outline">
        <i class="fas fa-graduation-cap"></i> Browse More Courses
      </a>
    </div>
  </div>
</section>
<?php require_once 'includes/footer.php'; ?>
