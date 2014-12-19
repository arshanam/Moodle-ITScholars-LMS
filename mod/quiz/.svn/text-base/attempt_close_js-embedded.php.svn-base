<?php defined('MOODLE_INTERNAL') or die('Direct access to this script is forbidden.');?>

<div class="controls">
<?php
if (!empty($popup)) {
?>

<script type="text/javascript">
//<![CDATA[

document.write('<input type="button" value="<?php print_string('closewindow') ?>" '+
               'onclick="javascript: window.opener.location.href=\'view-embedded.php?id=<?php echo $cm->id ?>\'; '+
               'window.close();" />');
//]]>
</script>
<noscript>
<div>
<?php print_string('closewindow'); ?>
</div>
</noscript>

<?php
} else {
	// SMS: 7/19/2014 Changed to support embedded version
	// print_single_button("view.php", array( 'id' => $cm->id ), get_string('finishreview', 'quiz'));
	echo "<center>";
    print_single_button("view-embedded.php", array( 'id' => $cm->id ), get_string('finishreview', 'quiz'));
	echo "</center>";
	// SMS End Change
}
?>
</div>
