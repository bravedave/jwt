<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/  ?>

<div class="markdown-body">
<?= Parsedown::instance()->text( file_get_contents( __DIR__ . '/../../../readme.md')); ?>
</div>
