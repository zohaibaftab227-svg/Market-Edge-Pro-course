<?php
$pageTitle = 'Contact Us';
require_once 'includes/header.php';
$db = getDB();

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $email   = sanitize($_POST['email'] ?? '');
    $phone   = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $db->prepare("INSERT INTO contacts (name,email,phone,subject,message) VALUES (?,?,?,?,?)");
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        $success = true;
    }
}
?>
<script>var siteUrl = '<?= SITE_URL ?>';</script>

<section class="page-header">
  <div class="container">
    <div class="section-tag"><i class="fas fa-envelope"></i> Get In Touch</div>
    <h1>Contact <span style="background:var(--gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Us</span></h1>
    <p>Have questions about our courses? We're here to help. Reach out anytime!</p>
  </div>
</section>

<section style="padding:70px 0">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:60px;align-items:start">

      <!-- Contact Info -->
      <div>
        <h2 style="font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:#fff;margin-bottom:14px">Let's Talk!</h2>
        <p style="color:#8888a0;line-height:1.7;margin-bottom:32px">Whether you want to enroll, have a question about our courses, or just want to say hello — we love hearing from you. Our team responds within 24 hours.</p>

        <div style="display:flex;flex-direction:column;gap:20px;margin-bottom:36px">
          <div style="display:flex;gap:14px;align-items:flex-start">
            <div style="width:44px;height:44px;background:rgba(212,168,67,0.1);border:1px solid rgba(212,168,67,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#d4a843;flex-shrink:0"><i class="fas fa-phone"></i></div>
            <div>
              <h4 style="font-size:13px;font-weight:700;color:#fff;margin:0 0 4px">Phone / WhatsApp</h4>
              <a href="tel:+923280732259" style="color:#8888a0;font-size:14px">+92 328 0732259</a>
            </div>
          </div>
          <div style="display:flex;gap:14px;align-items:flex-start">
            <div style="width:44px;height:44px;background:rgba(212,168,67,0.1);border:1px solid rgba(212,168,67,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#d4a843;flex-shrink:0"><i class="fas fa-envelope"></i></div>
            <div>
              <h4 style="font-size:13px;font-weight:700;color:#fff;margin:0 0 4px">Email Address</h4>
              <a href="mailto:marketedgepro7@gmail.com" style="color:#8888a0;font-size:14px">marketedgepro7@gmail.com</a>
            </div>
          </div>
          <div style="display:flex;gap:14px;align-items:flex-start">
            <div style="width:44px;height:44px;background:rgba(212,168,67,0.1);border:1px solid rgba(212,168,67,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#d4a843;flex-shrink:0"><i class="fas fa-map-marker-alt"></i></div>
            <div>
              <h4 style="font-size:13px;font-weight:700;color:#fff;margin:0 0 4px">Location</h4>
              <span style="color:#8888a0;font-size:14px">Lahore, Pakistan</span>
            </div>
          </div>
        </div>

        <h4 style="font-size:14px;font-weight:700;color:#fff;margin-bottom:14px">Follow Us</h4>
        <div style="display:flex;gap:10px">
          <a href="<?= FACEBOOK_URL ?>" target="_blank" style="width:42px;height:42px;background:#16161f;border:1px solid rgba(255,255,255,0.07);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#8888a0;font-size:16px;transition:all 0.2s" onmouseover="this.style.color='#d4a843';this.style.borderColor='rgba(212,168,67,0.3)'" onmouseout="this.style.color='#8888a0';this.style.borderColor='rgba(255,255,255,0.07)'"><i class="fab fa-facebook-f"></i></a>
          <a href="<?= INSTAGRAM_URL ?>" target="_blank" style="width:42px;height:42px;background:#16161f;border:1px solid rgba(255,255,255,0.07);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#8888a0;font-size:16px;transition:all 0.2s" onmouseover="this.style.color='#d4a843';this.style.borderColor='rgba(212,168,67,0.3)'" onmouseout="this.style.color='#8888a0';this.style.borderColor='rgba(255,255,255,0.07)'"><i class="fab fa-instagram"></i></a>
          <a href="https://wa.me/923280732259" target="_blank" style="width:42px;height:42px;background:#16161f;border:1px solid rgba(255,255,255,0.07);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#8888a0;font-size:16px;transition:all 0.2s" onmouseover="this.style.color='#25d366';this.style.borderColor='rgba(37,211,102,0.3)'" onmouseout="this.style.color='#8888a0';this.style.borderColor='rgba(255,255,255,0.07)'"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>

      <!-- Contact Form -->
      <div>
        <?php if ($success): ?>
        <div style="background:#16161f;border:1px solid rgba(255,255,255,0.05);border-radius:16px;padding:48px;text-align:center">
          <div style="width:72px;height:72px;background:rgba(46,204,113,0.1);border:2px solid #2ecc71;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:30px;color:#2ecc71">
            <i class="fas fa-check"></i>
          </div>
          <h3 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#fff;margin-bottom:12px">Message Sent!</h3>
          <p style="color:#8888a0;margin-bottom:24px">Thank you! We'll get back to you within 24 hours.</p>
          <a href="https://wa.me/923280732259" target="_blank" class="btn btn-primary"><i class="fab fa-whatsapp"></i> WhatsApp Us Now</a>
        </div>
        <?php else: ?>
        <div style="background:#16161f;border:1px solid rgba(255,255,255,0.05);border-radius:16px;padding:36px">
          <h3 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:#fff;margin-bottom:24px"><i class="fas fa-paper-plane" style="color:#d4a843;margin-right:8px"></i>Send a Message</h3>

          <?php if ($error): ?>
          <div class="flash-msg flash-error"><i class="fas fa-exclamation-circle"></i> <?= sanitize($error) ?></div>
          <?php endif; ?>

          <form method="POST">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
              <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-input" placeholder="Your name" value="<?= sanitize($_POST['name'] ?? '') ?>" required>
              </div>
              <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-input" placeholder="your@email.com" value="<?= sanitize($_POST['email'] ?? '') ?>" required>
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
              <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-input" placeholder="+92 3XX XXXXXXX" value="<?= sanitize($_POST['phone'] ?? '') ?>">
              </div>
              <div class="form-group">
                <label class="form-label">Subject</label>
                <select name="subject" class="form-input">
                  <option value="">Select subject</option>
                  <option value="Course Inquiry">Course Inquiry</option>
                  <option value="Enrollment Help">Enrollment Help</option>
                  <option value="Payment Question">Payment Question</option>
                  <option value="General Question">General Question</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Message *</label>
              <textarea name="message" class="form-textarea" rows="5" placeholder="Tell us how we can help you..." required><?= sanitize($_POST['message'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px">
              <i class="fas fa-paper-plane"></i> Send Message
            </button>
          </form>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Quick WhatsApp CTA -->
<section style="padding:60px 0;background:#111118;border-top:1px solid rgba(212,168,67,0.08)">
  <div class="container" style="text-align:center">
    <div style="width:64px;height:64px;background:rgba(37,211,102,0.1);border:2px solid rgba(37,211,102,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;color:#25d366">
      <i class="fab fa-whatsapp"></i>
    </div>
    <h3 style="font-family:'Syne',sans-serif;font-size:24px;font-weight:800;color:#fff;margin-bottom:10px">Prefer to chat directly?</h3>
    <p style="color:#8888a0;margin-bottom:24px">Get instant answers on WhatsApp. We typically respond within minutes.</p>
    <a href="https://wa.me/923280732259?text=Hi Market Edge Pro! I have a question about your courses." target="_blank" class="btn btn-primary" style="padding:14px 36px;font-size:16px">
      <i class="fab fa-whatsapp"></i> Chat on WhatsApp
    </a>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
