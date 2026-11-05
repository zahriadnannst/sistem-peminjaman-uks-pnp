<footer class="bg-white border-top py-3 mt-5">
  <div class="container text-center text-secondary small">
    <div class="mb-1">
        © <?= date("Y"); ?> Sistem Peminjaman UKS PNP
    </div>
    <a href="<?= BASE_URL ?>auth/admin_login.php" class="text-decoration-none text-muted opacity-50 hover-opacity-100" style="font-size: 0.7rem;">
        <i class="bi bi-lock"></i> Panel Admin
    </a>
  </div>
</footer>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS ANIMATION -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    once: true
  });
</script>

</body>
</html>
