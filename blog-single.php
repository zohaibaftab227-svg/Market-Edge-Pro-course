<?php
require_once 'includes/config.php';
$db   = getDB();
$slug = sanitize($_GET['slug'] ?? '');

if (!$slug) { redirect(SITE_URL . '/blog.php'); }

$stmt = $db->prepare("SELECT * FROM blog_posts WHERE slug=? AND is_published=1 LIMIT 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) { redirect(SITE_URL . '/blog.php'); }

// Increment views
$db->prepare("UPDATE blog_posts SET views=views+1 WHERE id=?")->execute([$post['id']]);

// Related posts
$related = $db->prepare("SELECT * FROM blog_posts WHERE category=? AND id!=? AND is_published=1 ORDER BY RAND() LIMIT 3");
$related->execute([$post['category'], $post['id']]);
$relatedPosts = $related->fetchAll();

$pageTitle = $post['title'];
require_once 'includes/header.php';

$bgColors = ['linear-gradient(135deg,#667eea,#764ba2)','linear-gradient(135deg,#f093fb,#f5576c)','linear-gradient(135deg,#4facfe,#00f2fe)','linear-gradient(135deg,#43e97b,#38f9d7)','linear-gradient(135deg,#fa709a,#fee140)'];
?>
<script>var siteUrl = '<?= SITE_URL ?>';</script>

<section class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= SITE_URL ?>/blog.php">Blog</a>
      <i class="fas fa-chevron-right" style="font-size:10px"></i>
      <span><?= sanitize($post['category']) ?></span>
    </div>
  </div>
</section>

<section style="padding:60px 0">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:48px;align-items:start">

      <article>
        <!-- Hero Image -->
        <div style="height:300px;border-radius:16px;overflow:hidden;margin-bottom:32px;background:<?= $bgColors[0] ?>;display:flex;align-items:center;justify-content:center;font-size:80px">
          <?php
          $emojis = ['📈','🚀','🎯','🤖','📱','🎨','🔍','💡'];
          echo $emojis[$post['id'] % count($emojis)];
          ?>
        </div>

        <!-- Meta -->
        <div style="display:flex;flex-wrap:wrap;gap:16px;margin-bottom:20px;font-size:13px;color:#8888a0">
          <span><i class="fas fa-folder" style="color:#d4a843;margin-right:5px"></i><?= sanitize($post['category']) ?></span>
          <span><i class="fas fa-user" style="color:#d4a843;margin-right:5px"></i><?= sanitize($post['author']) ?></span>
          <span><i class="fas fa-calendar" style="color:#d4a843;margin-right:5px"></i><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
          <span><i class="fas fa-eye" style="color:#d4a843;margin-right:5px"></i><?= number_format($post['views']) ?> views</span>
        </div>

        <!-- Title -->
        <h1 style="font-family:'Syne',sans-serif;font-size:clamp(24px,3.5vw,40px);font-weight:800;color:#fff;line-height:1.2;margin-bottom:24px">
          <?= sanitize($post['title']) ?>
        </h1>

        <!-- Excerpt -->
        <?php if($post['excerpt']): ?>
        <p style="font-size:17px;color:#8888a0;line-height:1.7;margin-bottom:32px;padding:20px;background:#16161f;border-left:3px solid #d4a843;border-radius:8px">
          <?= sanitize($post['excerpt']) ?>
        </p>
        <?php endif; ?>

        <!-- Content -->
        <div class="blog-single-content" style="color:#8888a0;line-height:1.8;font-size:16px">
          <?= $post['content'] ?>
        </div>

        <!-- Share -->
        <div style="margin-top:40px;padding:24px;background:#16161f;border:1px solid rgba(255,255,255,0.05);border-radius:14px">
          <p style="color:#8888a0;font-size:14px;margin-bottom:14px"><i class="fas fa-share-alt" style="color:#d4a843;margin-right:8px"></i>Share this article</p>
          <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL.'/blog-single.php?slug='.$post['slug']) ?>" target="_blank" class="btn btn-sm" style="background:rgba(59,89,152,0.15);color:#3b5998;border:1px solid rgba(59,89,152,0.2)"><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="https://wa.me/?text=<?= urlencode($post['title'].' - '.SITE_URL.'/blog-single.php?slug='.$post['slug']) ?>" target="_blank" class="btn btn-sm" style="background:rgba(37,211,102,0.1);color:#25d366;border:1px solid rgba(37,211,102,0.2)"><i class="fab fa-whatsapp"></i> WhatsApp</a>
          </div>
        </div>

        <!-- Related Posts -->
        <?php if (!empty($relatedPosts)): ?>
        <div style="margin-top:48px">
          <h3 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#fff;margin-bottom:24px">Related Articles</h3>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px">
            <?php foreach($relatedPosts as $i=>$rp): ?>
            <a href="<?= SITE_URL ?>/blog-single.php?slug=<?= urlencode($rp['slug']) ?>" style="display:block;background:#16161f;border:1px solid rgba(255,255,255,0.05);border-radius:12px;overflow:hidden;transition:all 0.3s;text-decoration:none" onmouseover="this.style.borderColor='rgba(212,168,67,0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.05)'">
              <div style="height:100px;background:<?= $bgColors[$i%count($bgColors)] ?>;display:flex;align-items:center;justify-content:center;font-size:40px"><?= $emojis[($rp['id'])%count($emojis)] ?></div>
              <div style="padding:14px">
                <div style="font-size:10px;color:#d4a843;margin-bottom:6px;font-weight:700;text-transform:uppercase"><?= sanitize($rp['category']) ?></div>
                <h4 style="font-size:13px;color:#fff;font-weight:600;line-height:1.3"><?= truncate($rp['title'], 50) ?></h4>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </article>

      <!-- Sidebar -->
      <aside style="position:sticky;top:120px">
        <div style="background:#16161f;border:1px solid rgba(255,255,255,0.05);border-radius:14px;padding:24px;margin-bottom:20px">
          <h4 style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:#fff;margin-bottom:18px"><i class="fas fa-fire" style="color:#d4a843;margin-right:8px"></i>Quick Enroll</h4>
          <p style="color:#8888a0;font-size:13px;line-height:1.6;margin-bottom:16px">Ready to master digital marketing? Join our practical courses today.</p>
          <a href="<?= SITE_URL ?>/courses.php" class="btn btn-primary" style="width:100%;justify-content:center"><i class="fas fa-graduation-cap"></i> View Courses</a>
          <a href="https://wa.me/923280732259" target="_blank" class="btn" style="width:100%;justify-content:center;margin-top:8px;background:rgba(37,211,102,0.1);color:#25d366;border:1px solid rgba(37,211,102,0.2)"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
        </div>
        <div style="background:#16161f;border:1px solid rgba(255,255,255,0.05);border-radius:14px;padding:24px">
          <h4 style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:#fff;margin-bottom:18px"><i class="fas fa-link" style="color:#d4a843;margin-right:8px"></i>Follow Us</h4>
          <div style="display:flex;flex-direction:column;gap:10px">
            <a href="<?= FACEBOOK_URL ?>" target="_blank" style="display:flex;align-items:center;gap:10px;color:#8888a0;font-size:13px;text-decoration:none;padding:8px;border-radius:8px;transition:all 0.2s" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='none'">
              <i class="fab fa-facebook-f" style="color:#3b5998;width:16px"></i> Market Edge Pro
            </a>
            <a href="<?= INSTAGRAM_URL ?>" target="_blank" style="display:flex;align-items:center;gap:10px;color:#8888a0;font-size:13px;text-decoration:none;padding:8px;border-radius:8px;transition:all 0.2s" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='none'">
              <i class="fab fa-instagram" style="color:#e1306c;width:16px"></i> @marketedgepro23
            </a>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="container">
    <h2>Start Your <span style="background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Marketing Journey</span> Today</h2>
    <p>Join hundreds of students who learned digital marketing with Market Edge Pro.</p>
    <div class="cta-actions">
      <a href="<?= SITE_URL ?>/courses.php" class="btn btn-primary"><i class="fas fa-graduation-cap"></i> Browse Courses</a>
      <a href="<?= SITE_URL ?>/blog.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Blog</a>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
