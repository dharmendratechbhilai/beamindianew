<footer id="footer" class="footer">
  <div class="copyright">
    &copy; Copyright <?= date('Y') ?> <strong><span><a href="<?= base_url() ?>" target="_blank"><?= SITE_NAME; ?></a></span></strong>. All Rights
    Reserved. Page rendered in <strong>{elapsed_time}</strong> seconds.
  </div>
  <div class="credits">
    <?= date('d-m-Y H:i:s'); ?> - <?= date_default_timezone_get(); ?>
  </div>
</footer>