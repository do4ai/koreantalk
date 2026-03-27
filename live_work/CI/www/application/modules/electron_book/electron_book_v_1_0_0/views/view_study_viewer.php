<?php
$asset_base_url = isset($asset_base_url) ? rtrim($asset_base_url, '/') : '/book-viewer';
$viewer_template = isset($viewer_template) && $viewer_template ? basename($viewer_template) : 'viewer-speaking.php';
include FCPATH.'book-viewer/'.$viewer_template;
