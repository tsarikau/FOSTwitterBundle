<script type="text/javascript">
    <?php if ($configMap): ?>
    twttr.anywhere.config({ <?php echo $configMap ?> });
    <?php endif; ?>

    twttr.anywhere(function (T) {
        <?php echo $scripts ?>
    });
</script>
