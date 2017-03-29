
$test_string = '<?php echo $test_string ?>';
$test_array = [
<?php foreach ($test_array as $element): ?>
    <?php printf("'%s'", $element) ?>,
<?php endforeach; ?>
];

