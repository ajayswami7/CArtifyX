<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    flash('success', !empty($_POST['newsletter_email']) ? 'You are subscribed to CArtifyX updates.' : 'Thanks for contacting us.');
    redirect('contact.php');
}
$pageTitle = 'Contact - CArtifyX';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><div class="container"><span class="eyebrow">Contact</span><h1>We are listening</h1><p>Questions about orders, styling, or collaborations? Send a note.</p></div></section>
<section class="section-pad"><div class="container"><div class="row g-4"><div class="col-lg-7"><div class="form-panel"><form method="post"><input name="name" placeholder="Name" required><input type="email" name="email" placeholder="Email" required><textarea name="message" rows="5" placeholder="Message" required></textarea><button class="lux-btn">Send Message</button></form></div></div><div class="col-lg-5"><div class="info-tile h-100"><h3>CArtifyX Support</h3><p>Email: support@cartifyx.local</p><p>Hours: 10 AM - 7 PM IST</p><p>Location: Mumbai, India</p></div></div></div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
