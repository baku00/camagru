<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/utils/events.js?token<?= mt_rand() ?>" defer></script>
<script src="<?= $_ENV['BASE_URL'] ?>/assets/js/utils/config.js?token<?= mt_rand() ?>" defer></script>
<script>
	document.addEventListener('DOMContentLoaded', () => {
		Config.getInstance().setApiUrl('<?= $_ENV['BASE_URL'] ?>');
	});
</script>