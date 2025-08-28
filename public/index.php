<?php

declare(strict_types=1);

// Autoload
$vendorAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require $vendorAutoload;
}

// Simple PSR-4 autoloader fallback if composer not installed yet
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Security headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'");

// Start session
$appConfig = require __DIR__ . '/../config/app.php';
session_name($appConfig['session_name']);
session_start();

// Bootstrap router
use App\routing\Router;
use App\security\Csrf;

$router = new Router($appConfig);

// Public routes
$router->get('/', [App\controllers\PageController::class, 'landing']);
$router->get('/signup', [App\controllers\AuthController::class, 'showSignup']);
$router->post('/signup', [App\controllers\AuthController::class, 'signup']);
$router->get('/login', [App\controllers\AuthController::class, 'showLogin']);
$router->post('/login', [App\controllers\AuthController::class, 'login']);
$router->post('/logout', [App\controllers\AuthController::class, 'logout']);

// Authenticated pages
$router->get('/dashboard', [App\controllers\PageController::class, 'dashboard']);

// Courses
$router->get('/courses', [App\controllers\CourseController::class, 'list']);
$router->get('/course/(?P<slug>[a-zA-Z0-9\-]+)', [App\controllers\CourseController::class, 'detail']);
$router->post('/validate-referral', [App\controllers\CourseController::class, 'validateReferral']);
$router->post('/purchase-course', [App\controllers\CourseController::class, 'purchaseCourse']);
$router->post('/verify-payment', [App\controllers\CourseController::class, 'verifyPayment']);

// Media token
$router->get('/media/token', [App\controllers\MediaController::class, 'token']);

// Forms
$router->post('/consult/book', [App\controllers\FormsController::class, 'bookConsult']);
$router->post('/products/order', [App\controllers\FormsController::class, 'orderProduct']);
$router->post('/forgot-password', [App\controllers\FormsController::class, 'forgotPassword']);

// Account & History
$router->get('/account', [App\controllers\AccountController::class, 'show']);
$router->put('/account', [App\controllers\AccountController::class, 'update']);
$router->get('/history', [App\controllers\HistoryController::class, 'index']);

// Admin
$router->get('/admin', [App\controllers\AdminController::class, 'dashboard']);
$router->post('/admin/verify-payment', [App\controllers\AdminController::class, 'verifyPayment']);

// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

