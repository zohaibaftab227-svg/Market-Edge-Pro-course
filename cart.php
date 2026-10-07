<?php
require_once 'includes/config.php';

header('Content-Type: application/json');

$action   = sanitize($_GET['action'] ?? '');
$courseId = (int)($_GET['id'] ?? 0);

if ($action === 'add' && $courseId) {
    $db = getDB();
    // Verify course exists and is active
    $stmt = $db->prepare("SELECT id FROM courses WHERE id=? AND is_active=1");
    $stmt->execute([$courseId]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Course not found']);
        exit;
    }
    $cart = getCart();
    if (in_array($courseId, $cart)) {
        echo json_encode(['success' => true, 'already' => true, 'count' => count($cart)]);
    } else {
        addToCart($courseId);
        $newCount = count(getCart());
        echo json_encode(['success' => true, 'added' => true, 'count' => $newCount]);
    }
    exit;
}

if ($action === 'remove' && $courseId) {
    removeFromCart($courseId);
    echo json_encode(['success' => true, 'count' => count(getCart())]);
    exit;
}

if ($action === 'count') {
    echo json_encode(['count' => cartCount()]);
    exit;
}

// Render cart page (GET without action)
$pageTitle = 'My Cart';
require_once 'includes/header.php';
$db    = getDB();
$cart  = getCart();
$items = [];
$total = 0;

if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $db->prepare("SELECT * FROM courses WHERE id IN ($placeholders) AND is_active=1");
    $stmt->execute($cart);
    $items = $stmt->fetchAll();
    foreach ($items as $item) $total += $item['price'];
}
?>
<script>var siteUrl = '<?= SITE_URL ?>';</script>

<section class="page-header">
  <div class="container">
    <div class="section-tag"><i class="fas fa-shopping-cart"></i> Cart</div>
    <h1>My <span style="background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Cart</span></h1>
    <p><?= count($items) ?> course(s) selected for enrollment.</p>
  </div>
</section>

<section class="cart-section" style="padding:60px 0">
  <div class="container">
    <?php if (empty($items)): ?>
    <div class="no-results" style="text-align:center;padding:80px 20px">
      <i class="fas fa-shopping-cart" style="font-size:64px;color:var(--primary);opacity:0.3;display:block;margin-bottom:20px"></i>
      <h3 style="color:#fff;margin-bottom:12px">Your cart is empty</h3>
      <p style="color:var(--text-muted);margin-bottom:28px">Add some courses and start your learning journey!</p>
      <a href="<?= SITE_URL ?>/courses.php" class="btn btn-primary"><i class="fas fa-graduation-cap"></i> Browse Courses</a>
    </div>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:1fr 340px;gap:32px;align-items:start">
      <div>
        <div class="admin-card" style="margin-bottom:0">
          <div class="admin-card-header"><h2><i class="fas fa-list"></i> Selected Courses</h2></div>
          <?php foreach ($items as $course): ?>
          <div class="cart-item" style="display:flex;align-items:center;gap:16px;padding:20px;border-bottom:1px solid rgba(255,255,255,0.05)">
            <div style="width:52px;height:52px;background:rgba(108,99,255,0.12);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:var(--primary);flex-shrink:0">
              <i class="<?= sanitize($course['icon']) ?>"></i>
            </div>
            <div style="flex:1">
              <h3 style="font-size:16px;font-weight:700;color:#fff;margin-bottom:6px"><?= sanitize($course['title']) ?></h3>
              <div style="display:flex;gap:10px;font-size:12px">
                <span style="color:var(--primary)"><i class="fas fa-clock"></i> <?= sanitize($course['duration']) ?></span>
                <span style="color:var(--text-muted)"><i class="fas fa-signal"></i> <?= sanitize($course['level']) ?></span>
              </div>
            </div>
            <div style="text-align:right;flex-shrink:0">
              <div style="font-size:20px;font-weight:800;color:var(--primary);margin-bottom:6px"><?= formatPrice($course['price']) ?></div>
              <?php if($course['original_price']): ?>
              <div style="font-size:12px;color:var(--text-muted);text-decoration:line-through"><?= formatPrice($course['original_price']) ?></div>
              <?php endif; ?>
            </div>
            <button class="btn-remove" data-id="<?= $course['id'] ?>" style="background:rgba(231,76,60,0.1);border:1px solid rgba(231,76,60,0.2);color:#e74c3c;width:36px;height:36px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.2s" onmouseover="this.style.background='#e74c3c';this.style.color='#fff'" onmouseout="this.style.background='rgba(231,76,60,0.1)';this.style.color='#e74c3c'">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="admin-card" style="position:sticky;top:120px">
        <div class="admin-card-header"><h2><i class="fas fa-receipt"></i> Order Summary</h2></div>
        <div style="padding:8px 0">
          <?php foreach($items as $item): ?>
          <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.04);font-size:13px">
            <span style="color:var(--text-muted)"><?= truncate($item['title'], 22) ?></span>
            <span style="color:#fff;font-weight:600"><?= formatPrice($item['price']) ?></span>
          </div>
          <?php endforeach; ?>
          <div style="display:flex;justify-content:space-between;padding:16px 0 0;margin-top:8px;border-top:2px solid var(--border-glow,rgba(108,99,255,0.3))">
            <span style="font-weight:700;font-size:15px;color:#fff">Total</span>
            <span style="font-size:22px;font-weight:800;color:var(--primary)"><?= formatPrice($total) ?></span>
          </div>
          <?php if(count($items) === 1): ?>
          <a href="<?= SITE_URL ?>/enroll.php?id=<?= $items[0]['id'] ?>" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:20px;padding:14px">
            <i class="fas fa-bolt"></i> Proceed to Enroll
          </a>
          <?php else: ?>
          <a href="<?= SITE_URL ?>/enroll.php?id=<?= $items[0]['id'] ?>" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:20px;padding:14px">
            <i class="fas fa-bolt"></i> Enroll in Courses
          </a>
          <?php endif; ?>
          <a href="<?= SITE_URL ?>/courses.php" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:10px">
            <i class="fas fa-plus"></i> Add More Courses
          </a>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
