<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

use App\Models\Bengkel;

$bengkels = Bengkel::select('id', 'nama', 'foto', 'admin_id')->get();
echo json_encode($bengkels, JSON_PRETTY_PRINT) . "\n";
