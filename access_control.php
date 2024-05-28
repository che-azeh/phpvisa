<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

if (empty($_GET['x-www-form-urlencoded'])) { // For access through iFrames
	header('Content-type: application/json');
}