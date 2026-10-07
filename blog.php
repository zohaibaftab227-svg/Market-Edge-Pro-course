<?php
$pageTitle = 'Blog';
$pageDesc  = 'Read the latest digital marketing tips, SEO strategies, AI insights and social media marketing guides from Market Edge Pro experts.';
require_once 'includes/header.php';
$db = getDB();

$cat    = sanitize($_GET['cat'] ?? '');
$search = sanitize($_GET['search'] ?? '');

$sql    = "SELECT * FROM blog_posts WHERE is_published=1";
$params = [];

if ($cat)    { $sql .= " AND category=?"; $params[] = $cat; }
if ($search) { $sql .= " AND (title LIKE ? OR excerpt LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }

$sql .= " ORDER BY created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

$cats  = $db->query("SELECT DISTINCT category FROM blog_posts WHERE is_published=1")->fetchAll(PDO::FETCH_COLUMN);

$bgColors  = ['linear-gradient(135deg,#667eea,#764ba2)','linear-gradient(135deg,#f093fb,#f5576c)','linear-gradient(135deg,#4facfe,#00f2fe)','linear-gradient(135deg,#43e97b,#38f9d7)','linear-gradient(135deg,#fa709a,#fee140)','linear-gradient(135deg,#a18cd1,#fbc2eb)','linear-gradient(135deg,#ffecd2,#fcb69f)'];
$emojis    = ['📈','🚀','🎯','🤖','📱','🎨','🔍','💡','⚡','🌟'];
?>

<section class="page-header">
  <div class="container">
    <div class="section-tag"><i class="fas fa-blog"></i> Our Blog</div>
    <h1>Marketing <span style="background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Insights</span></h1>
    <p>Expert tips, strategies and insights to help you grow your digital marketing skills and business.</p>
  </div>
</section>

<section class="blog-section" style="padding-top:40px">
  <div class="container">
    <!-- Search -->
    <form method="GET" style="max-width:500px;margin:0 auto 32px">
      <div style="position:relative">
        <input type="text" name="search" value="<?= sanitize($search) ?>" placeholder="Search articles..." class="form-input" style="padding-left:44px">
        <i class="fas fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted)"></i>
      </div>
    </form>

    <!-- Category Filter -->
    <div class="filter-bar" style="justify-content:center">
      <a href="<?= SITE_URL ?>/blog.php" class="filter-btn <?= !$cat?'active':'' ?>">All Posts</a>
      <?php foreach($cats as $c): ?>
      <a href="<?= SITE_URL ?>/blog.php?cat=<?= urlencode($c) ?>" class="filter-btn <?= $cat===$c?'active':'' ?>"><?= sanitize($c) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($posts)): ?>
    <div class="no-results"><i class="fas fa-search"></i><h3>No articles found</h3><p>Try a different search term or category.</p></div>
    <?php else: ?>
    <div class="blog-grid">
      <?php foreach($posts as $i=>$post):
        // Increment views
        $db->prepare("UPDATE blog_posts SET views=views+1 WHERE id=?")->execute([$post['id']]);
      ?>
      <div class="blog-card">
        <div class="blog-card-img" style="background:<?= $bgColors[$i%count($bgColors)] ?>;font-size:56px">
          <?= $emojis[$i%count($emojis)] ?>
        </div>
        <div class="blog-card-body">
          <div class="blog-cat"><?= sanitize($post['category']) ?></div>
          <h3 class="blog-card-title">
            <a href="<?= SITE_URL ?>/blog-single.php?slug=<?= urlencode($post['slug']) ?>"><?= sanitize($post['title']) ?></a>
          </h3>
          <p class="blog-card-excerpt"><?= truncate($post['excerpt'], 130) ?></p>
          <div class="blog-card-footer">
            <div style="display:flex;flex-direction:column;gap:4px">
              <span style="font-size:11px"><i class="fas fa-user"></i> <?= sanitize($post['author']) ?></span>
              <span style="font-size:11px"><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($post['created_at'])) ?></span>
            </div>
            <a href="<?= SITE_URL ?>/blog-single.php?slug=<?= urlencode($post['slug']) ?>" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- Newsletter CTA -->
<section class="cta-section">
  <div class="container">
    <div class="section-tag"><i class="fas fa-envelope"></i> Stay Updated</div>
    <h2>Want More <span style="background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Marketing Tips?</span></h2>
    <p>Follow us on social media for daily digital marketing tips, tricks and strategies.</p>
    <div class="cta-actions">
      <a href="<?= FACEBOOK_URL ?>" target="_blank" class="btn btn-primary"><i class="fab fa-facebook"></i> Follow on Facebook</a>
      <a href="<?= INSTAGRAM_URL ?>" target="_blank" class="btn btn-outline"><i class="fab fa-instagram"></i> Follow on Instagram</a>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
