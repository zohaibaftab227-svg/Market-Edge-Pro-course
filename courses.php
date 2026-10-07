<?php
$pageTitle = 'Courses';
$pageDesc  = 'Explore all digital marketing courses at Market Edge Pro. Learn SEO, SMM, AI, Web Development, Meta & Google Ads and more.';
require_once 'includes/header.php';
$db = getDB();

// Handle cart AJAX
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['id'])) {
    $added = addToCart((int)$_GET['id']);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'added' => $added, 'count' => cartCount()]);
    exit;
}

// Filter by category
$cat = sanitize($_GET['cat'] ?? 'all');
$search = sanitize($_GET['search'] ?? '');

$sql = "SELECT * FROM courses WHERE is_active=1";
$params = [];

if ($search) {
    $sql .= " AND (title LIKE ? OR description LIKE ? OR category LIKE ?)";
    $params = ["%$search%", "%$search%", "%$search%"];
}

$sql .= " ORDER BY is_featured DESC, id ASC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();

// Get all categories
$categories = $db->query("SELECT DISTINCT category FROM courses WHERE is_active=1 ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
?>
<script>var siteUrl = '<?= SITE_URL ?>';</script>

<!-- Page Header -->
<section class="page-header">
  <div class="container">
    <div class="section-tag"><i class="fas fa-graduation-cap"></i> All Courses</div>
    <h1>Our <span style="background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Courses</span></h1>
    <p>Choose from <?= $totalCourses = count($courses) ?> industry-leading courses designed to launch your digital marketing career.</p>
  </div>
</section>

<section class="courses-section" style="padding-top:40px">
  <div class="container">

    <!-- Search + Filter Bar -->
    <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:32px;align-items:center">
      <form method="GET" style="flex:1;min-width:250px">
        <div style="position:relative">
          <input type="text" name="search" value="<?= sanitize($search) ?>" placeholder="Search courses..." class="form-input" style="padding-left:42px">
          <i class="fas fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted)"></i>
        </div>
      </form>
    </div>

    <!-- Category Filters -->
    <div class="filter-bar">
      <button class="filter-btn active" data-cat="all" onclick="filterCourses('all',this)">All Courses</button>
      <?php foreach($categories as $c): ?>
      <button class="filter-btn" data-cat="<?= sanitize($c) ?>" onclick="filterCourses('<?= sanitize($c) ?>',this)"><?= sanitize($c) ?></button>
      <?php endforeach; ?>
    </div>

    <!-- Courses Grid -->
    <?php if (empty($courses)): ?>
      <div class="no-results"><i class="fas fa-search"></i><h3>No courses found</h3><p>Try a different search term or category.</p></div>
    <?php else: ?>
    <div class="courses-grid" id="coursesGrid">
      <?php foreach($courses as $course): ?>
      <div class="course-card-wrap" data-cat="<?= sanitize($course['category']) ?>">
        <div class="course-card">
          <div class="course-card-top">
            <div class="course-card-icon"><i class="<?= sanitize($course['icon']) ?>"></i></div>
            <div class="course-card-meta">
              <span class="badge badge-level" style="background:rgba(<?= $course['level']==='Advanced'?'231,76,60':'108,99,255' ?>,0.15);color:<?= levelColor($course['level']) ?>"><i class="fas fa-signal"></i> <?= sanitize($course['level']) ?></span>
              <span class="badge badge-duration"><i class="fas fa-clock"></i> <?= sanitize($course['duration']) ?></span>
              <?php if($course['is_featured']): ?><span class="badge badge-featured"><i class="fas fa-fire"></i> Popular</span><?php endif; ?>
            </div>
            <h3 class="course-card-title"><?= sanitize($course['title']) ?></h3>
            <p class="course-card-desc"><?= truncate($course['description'], 130) ?></p>
          </div>
          <div class="course-card-bottom">
            <div style="display:flex;gap:16px;font-size:12px;color:var(--text-muted);margin-bottom:4px">
              <span><i class="fas fa-user-tie"></i> <?= sanitize($course['instructor']) ?></span>
            </div>
            <div class="course-price-row">
              <span class="course-price"><?= formatPrice($course['price']) ?></span>
              <?php if($course['original_price']): ?>
                <span class="course-original-price"><?= formatPrice($course['original_price']) ?></span>
                <span class="discount-badge"><?= discountPercent($course['original_price'], $course['price']) ?>% OFF</span>
              <?php endif; ?>
            </div>
            <div class="course-card-actions">
              <button class="btn-add-cart <?= isInCart($course['id'])?'in-cart':'' ?>" data-id="<?= $course['id'] ?>">
                <i class="fas fa-<?= isInCart($course['id'])?'check':'cart-plus' ?>"></i>
                <?= isInCart($course['id'])?'Added':'Add to Cart' ?>
              </button>
              <a href="<?= SITE_URL ?>/enroll.php?id=<?= $course['id'] ?>" class="btn-enroll-now">
                <i class="fas fa-bolt"></i> Enroll Now
              </a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="container">
    <h2>Can't decide? <span style="background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Talk to us!</span></h2>
    <p>Our counselors will help you choose the perfect course based on your goals and background.</p>
    <div class="cta-actions">
      <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Hi! I need help choosing the right course." class="btn btn-primary" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp Consultation</a>
      <a href="<?= SITE_URL ?>/contact.php" class="btn btn-outline"><i class="fas fa-envelope"></i> Send Message</a>
    </div>
  </div>
</section>

<script>
function filterCourses(cat, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.course-card-wrap').forEach(card => {
    const show = cat === 'all' || card.dataset.cat === cat;
    card.style.display = show ? '' : 'none';
  });
}
</script>

<?php require_once 'includes/footer.php'; ?>
