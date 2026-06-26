<button id="backToTop" class="back-to-top" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>
</main>
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h3>CArtifyX</h3>
                <p>Luxury minimalist fashion shopping inspired by India&apos;s favorite style discovery experience.</p>
                <div class="socials">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h4>Shop</h4>
                <a href="<?= BASE_URL ?>products.php">All Products</a>
                <a href="<?= BASE_URL ?>category.php?slug=women">Women</a>
                <a href="<?= BASE_URL ?>category.php?slug=men">Men</a>
                <a href="<?= BASE_URL ?>category.php?slug=footwear">Footwear</a>
            </div>
            <div class="col-6 col-lg-2">
                <h4>Account</h4>
                <a href="<?= BASE_URL ?>profile.php">Profile</a>
                <a href="<?= BASE_URL ?>orders.php">Orders</a>
                <a href="<?= BASE_URL ?>wishlist.php">Wishlist</a>
                <a href="<?= BASE_URL ?>admin/login.php">Admin</a>
            </div>
            <div class="col-lg-4">
                <h4>Newsletter</h4>
                <p>Get private sale alerts and new-season edits.</p>
                <form class="newsletter" method="post" action="<?= BASE_URL ?>contact.php">
                    <input type="email" name="newsletter_email" placeholder="Email address" required>
                    <button type="submit">Join</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">Copyright <?= date('Y') ?> CArtifyX. Crafted for premium fashion retail.</div>
    </div>
</footer>
<div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content quick-modal">
            <button type="button" class="btn-close ms-auto m-3" data-bs-dismiss="modal"></button>
            <div class="row g-0">
                <div class="col-md-6"><img id="quickImage" src="" alt="" class="w-100 h-100 object-fit-cover"></div>
                <div class="col-md-6 p-4">
                    <p id="quickBrand" class="brand"></p>
                    <h3 id="quickName"></h3>
                    <div id="quickPrice" class="price fs-4 mb-3"></div>
                    <p id="quickDescription" class="text-muted"></p>
                    <a id="quickLink" class="lux-btn mt-2" href="#">View Details</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/main.js"></script>
<script src="<?= BASE_URL ?>assets/js/cart.js"></script>
</body>
</html>
