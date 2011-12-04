<div class="import-results">
	new: <?php echo count($parts['new']) ?><br />
	<pre><?php print_r(array_keys($parts['new'])) ?></pre>
	existing: <?php echo count($parts['existing']) ?><br />
	<pre><?php print_r(array_keys($parts['existing'])) ?></pre>
	excluded: <?php echo count($parts['excluded']) ?>
	<pre><?php print_r(array_keys($parts['excluded'])) ?></pre>
</div>