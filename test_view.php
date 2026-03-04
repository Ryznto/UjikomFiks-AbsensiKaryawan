<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->handle(
    $request = \Illuminate\Http\Request::capture()
);

ob_start();

try {
    // Test if the view file exists
    $viewPath = base_path('resources/views/admin/dashboard.blade.php');
    echo "View file path: $viewPath\n";
    echo "File exists: " . (file_exists($viewPath) ? "YES" : "NO") . "\n";
    echo "File size: " . filesize($viewPath) . " bytes\n\n";
    
    // Test rendering the view
    $view = view('admin.dashboard', [
        'totalKaryawan' => 5,
        'hadirHariIni' => 4,
        'terlambatHariIni' => 1,
        'alfaHariIni' => 0,
        'presensiHariIni' => []
    ]);
    
    echo "View object created: " . get_class($view) . "\n";
    
    $rendered = $view->render();
    
    echo "\n✓ View rendered successfully\n";
    echo "Output length: " . strlen($rendered) . " chars\n";
    echo "\nFirst 800 chars:\n";
    echo substr($rendered, 0, 800) . "\n";
} catch (\Exception $e) {
    echo "✗ Error:\n";
    echo $e->getMessage() . "\n\n";
    echo $e->getFile() . " on line " . $e->getLine() . "\n";
}

$output = ob_get_clean();
file_put_contents(__DIR__ . '/test_output.txt', $output);
echo $output;
