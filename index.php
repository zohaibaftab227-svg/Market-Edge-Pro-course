 <?php
$pageTitle = 'Home';
$pageDesc  = 'Market Edge Pro - Pakistan\'s Leading Digital Marketing Academy. Learn SEO, SMM, AI, Web Development & more with expert instructors.';
require_once 'includes/header.php';
$db = getDB();

// Fetch all active courses
$featuredCourses = $db->query("SELECT * FROM courses WHERE is_active=1 ORDER BY is_featured DESC, id ASC")->fetchAll();

// Fetch all published blog posts
$latestBlogs = $db->query("SELECT * FROM blog_posts WHERE is_published=1 ORDER BY created_at DESC")->fetchAll();

// Stats
$totalCourses     = $db->query("SELECT COUNT(*) FROM courses WHERE is_active=1")->fetchColumn();
$totalEnrollments = $db->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();
?>

<script>var siteUrl = '<?= SITE_URL ?>';</script>

<!-- HERO -->
<section class="hero">
  <div class="container">
    <div class="hero-content">
      <div class="hero-text">
        <div class="hero-badge"><i class="fas fa-fire"></i> Pakistan's #1 Digital Marketing Academy</div>
        <h1 class="hero-title">
          Master <span class="gradient-text">Digital Marketing</span><br>
          &amp; Grow Your Career
        </h1>
        <p class="hero-desc">Join thousands of students who transformed their careers with Market Edge Pro. Learn practical skills in SEO, SMM, AI, Web Development and more — taught by industry experts.</p>
        <div class="hero-actions">
          <a href="<?= SITE_URL ?>/courses.php" class="btn btn-primary"><i class="fas fa-graduation-cap"></i> Explore Courses</a>
          <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Hi! I want to learn more about your courses." class="btn btn-outline" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
        </div>
        <div class="hero-stats">
          <div class="hero-stat"><h3><span class="counter" data-target="<?= $totalCourses ?>">0</span>+</h3><p>Courses</p></div>
          <div class="hero-stat"><h3><span class="counter" data-target="<?= max($totalEnrollments, 500) ?>">0</span>+</h3><p>Students Enrolled</p></div>
          <div class="hero-stat"><h3><span class="counter" data-target="98">0</span>%</h3><p>Success Rate</p></div>
          <div class="hero-stat"><h3><span class="counter" data-target="5">0</span>+</h3><p>Years Experience</p></div>
        </div>
      </div>

      <div class="hero-visual">
        <div class="hero-card-main">
          <h3><i class="fas fa-star" style="color:#f39c12"></i> Courses</h3>
          <div class="course-preview-list">
            <?php foreach($featuredCourses as $fc): ?>
            <div class="course-preview-item">
              <div class="course-icon"><i class="<?= sanitize($fc['icon']) ?>"></i></div>
              <div>
                <div class="course-name"><?= sanitize($fc['title']) ?></div>
                <div class="course-meta"><?= sanitize($fc['duration']) ?> • <?= sanitize($fc['level']) ?></div>
              </div>
              <span class="course-price-badge"><?= formatPrice($fc['price']) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="floating-card floating-card-1">
          <i class="fas fa-trophy" style="color:#f39c12"></i>
          <div><span>Top Rated</span><small>4.9/5 Rating</small></div>
        </div>
        <div class="floating-card floating-card-2">
          <i class="fas fa-users" style="color:#2ecc71"></i>
          <div><span>Live Classes</span><small>Every Week</small></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHY CHOOSE US -->
<section class="why-section">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i class="fas fa-award"></i> Why Choose Us</div>
      <h2 class="section-title">Everything You Need to <span>Succeed Online</span></h2>
      <p class="section-desc">We provide industry-leading education with real-world projects and mentorship from Pakistan's top digital marketers.</p>
    </div>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-laptop-code"></i></div>
        <h3>Live Practical Training</h3>
        <p>Learn by doing with real projects, live campaigns, and hands-on assignments that build your actual portfolio.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-certificate"></i></div>
        <h3>Recognized Certificate</h3>
        <p>Earn an industry-recognized certificate upon completion that employers and clients actually value.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-user-tie"></i></div>
        <h3>Expert Instructors</h3>
        <p>Learn from professionals with 5+ years of real agency experience managing campaigns for major brands.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-briefcase"></i></div>
        <h3>Job Assistance</h3>
        <p>Get career guidance, CV review, interview prep, and connections to our network of hiring partners.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-headset"></i></div>
        <h3>Lifetime Support</h3>
        <p>Access course materials forever and get support through our exclusive alumni WhatsApp community.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-money-bill-wave"></i></div>
        <h3>Affordable Pricing</h3>
        <p>Get world-class digital marketing education at Pakistan's most affordable rates with flexible installment options.</p>
      </div>
    </div>
  </div>
</section>

<!-- FEATURED COURSES -->
<section class="courses-section">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i class="fas fa-graduation-cap"></i> Our Courses</div>
      <h2 class="section-title">Trending <span>Courses</span></h2>
      <p class="section-desc">Hand-picked courses designed to get you job-ready or freelance-ready in the shortest time possible.</p>
    </div>
    <div class="courses-grid">
      <?php foreach($featuredCourses as $course): ?>
      <div class="course-card-wrap" data-cat="<?= sanitize($course['category']) ?>">
        <div class="course-card">
          <div class="course-card-top">
            <div class="course-card-icon"><i class="<?= sanitize($course['icon']) ?>"></i></div>
            <div class="course-card-meta">
              <span class="badge badge-level"><i class="fas fa-signal"></i> <?= sanitize($course['level']) ?></span>
              <span class="badge badge-duration"><i class="fas fa-clock"></i> <?= sanitize($course['duration']) ?></span>
              <?php if($course['is_featured']): ?><span class="badge badge-featured"><i class="fas fa-fire"></i> Hot</span><?php endif; ?>
            </div>
            <h3 class="course-card-title"><?= sanitize($course['title']) ?></h3>
            <p class="course-card-desc"><?= truncate($course['description'], 110) ?></p>
          </div>
          <div class="course-card-bottom">
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
    <div style="text-align:center;margin-top:40px">
      <a href="<?= SITE_URL ?>/courses.php" class="btn btn-outline"><i class="fas fa-th-large"></i> View All Courses</a>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i class="fas fa-heart"></i> Student Reviews</div>
      <h2 class="section-title">What Our <span>Students Say</span></h2>
    </div>
    <div class="testimonials-grid">
      <?php
      $testimonials = [
        ['name'=>'Ali Hassan','title'=>'Freelancer | Faisalabad','text'=>'Market Edge Pro completely changed my career. Within 3 months of completing the Digital Marketing course, I landed my first international client and now earn PKR 80,000/month working from home.','initial'=>'A'],
        ['name'=>'Fatima Malik','title'=>'Social Media Manager | Lahore','text'=>'The SMM course was incredibly practical. I learned how to run Meta ads, manage content calendars, and measure ROI. Got hired as a Social Media Manager right after graduating!','initial'=>'F'],
        ['name'=>'Usman Ahmed','title'=>'SEO Specialist | Karachi','text'=>'Best investment I ever made. The SEO course taught me everything from keyword research to technical SEO. Now I rank multiple client websites on Google first page.','initial'=>'U'],
        ['name'=>'Sara Khan','title'=>'Content Creator | Islamabad','text'=>'The Content Designing course is amazing. The instructor explains everything step by step. I now design for 5 regular clients and my rates keep increasing as my portfolio grows.','initial'=>'S'],
        ['name'=>'Bilal Qureshi','title'=>'AI Automation Expert | Lahore','text'=>'The Agentic AI course is ahead of its time. I learned n8n, Make.com, and AI automation. Now I offer automation services to businesses and charge premium rates.','initial'=>'B'],
        ['name'=>'Amna Sheikh','title'=>'Web Developer | Rawalpindi','text'=>'Went from zero coding knowledge to building complete websites in 12 weeks. The hands-on approach and project-based learning made all the difference for me.','initial'=>'A'],
      ];
      foreach($testimonials as $t): ?>
      <div class="testimonial-card">
        <div class="stars">★★★★★</div>
        <p class="testimonial-text">"<?= $t['text'] ?>"</p>
        <div class="testimonial-author">
          <div class="author-avatar"><?= $t['initial'] ?></div>
          <div>
            <div class="author-name"><?= $t['name'] ?></div>
            <div class="author-title"><?= $t['title'] ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- LATEST BLOG -->
<section class="blog-section">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i class="fas fa-blog"></i> Latest Articles</div>
      <h2 class="section-title">From Our <span>Blog</span></h2>
      <p class="section-desc">Stay updated with the latest digital marketing trends, strategies, and insights from our expert team.</p>
    </div>
    <div class="blog-grid">
      <?php
      $blogEmojis = ['📈','🚀','🎯','🤖','📱','🎨','🔍','💡'];
      $bgColors   = ['linear-gradient(135deg,#667eea,#764ba2)','linear-gradient(135deg,#f093fb,#f5576c)','linear-gradient(135deg,#4facfe,#00f2fe)','linear-gradient(135deg,#43e97b,#38f9d7)','linear-gradient(135deg,#fa709a,#fee140)','linear-gradient(135deg,#a18cd1,#fbc2eb)'];
      foreach($latestBlogs as $i=>$post): ?>
      <div class="blog-card">
        <div class="blog-card-img" style="background:<?= $bgColors[$i%count($bgColors)] ?>"><?= $blogEmojis[$i%count($blogEmojis)] ?></div>
        <div class="blog-card-body">
          <div class="blog-cat"><?= sanitize($post['category']) ?></div>
          <h3 class="blog-card-title"><a href="<?= SITE_URL ?>/blog-single.php?slug=<?= urlencode($post['slug']) ?>"><?= sanitize($post['title']) ?></a></h3>
          <p class="blog-card-excerpt"><?= truncate($post['excerpt'], 120) ?></p>
          <div class="blog-card-footer">
            <span><i class="fas fa-user"></i> <?= sanitize($post['author']) ?></span>
            <a href="<?= SITE_URL ?>/blog-single.php?slug=<?= urlencode($post['slug']) ?>" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:40px">
      <a href="<?= SITE_URL ?>/blog.php" class="btn btn-outline"><i class="fas fa-blog"></i> View All Posts</a>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="container">
    <div class="section-tag"><i class="fas fa-rocket"></i> Start Today</div>
    <h2>Ready to <span style="background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Transform</span> Your Career?</h2>
    <p>Join 500+ students who are already building profitable digital careers. Enroll today and get started immediately.</p>
    <div class="cta-actions">
      <a href="<?= SITE_URL ?>/courses.php" class="btn btn-primary"><i class="fas fa-graduation-cap"></i> Browse Courses</a>
      <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Hi! I want to enroll in a course." class="btn btn-white" target="_blank"><i class="fab fa-whatsapp"></i> Chat on WhatsApp</a>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
