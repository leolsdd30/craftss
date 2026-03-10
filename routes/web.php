<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\HomeownerController;
use App\Controllers\CraftsmanController;
use App\Controllers\JobBoardController;
use App\Controllers\SearchController;
use App\Controllers\ProfileController;

use App\Controllers\BookingController;
use App\Controllers\ReviewController;
use App\Controllers\FavoriteController;
use App\Controllers\NotificationController;
use App\Controllers\AdminController;
use App\Controllers\MessageController;

/**
 * Register all web routes here.
 * The $router object is provided by public/index.php
 */

$router->get('/', [HomeController::class , 'index']);
$router->get('/about', [HomeController::class , 'about']);

// Public Search & Profile Routes
$router->get('/search', [SearchController::class , 'index']);
$router->get('/profile', [ProfileController::class , 'show']);

// Profile Management Routes
$router->get('/profile/edit', [ProfileController::class , 'edit']);
$router->post('/profile/edit', [ProfileController::class , 'update']);
$router->post('/profile/publish', [ProfileController::class , 'publish']);
$router->get('/profile/check-username', [ProfileController::class , 'checkUsername']);

// Dynamic Profile Route (Must be after other /profile/... routes)
$router->get('/profile/{username}', [ProfileController::class , 'show']);

// Authentication Routes
$router->get('/login', [AuthController::class , 'showLoginForm']);
$router->post('/login', [AuthController::class , 'processLogin']);
$router->get('/register', [AuthController::class , 'showRegisterForm']);
$router->post('/register', [AuthController::class , 'processRegister']);
$router->post('/logout', [AuthController::class , 'logout']);

// Role-based Dashboards
$router->get('/homeowner/dashboard', [HomeownerController::class , 'dashboard']);
$router->get('/craftsman/dashboard', [CraftsmanController::class , 'dashboard']);

// Job Board Routes
$router->get('/jobs', [JobBoardController::class , 'index']);
$router->get('/jobs/create', [JobBoardController::class , 'create']);
$router->post('/jobs/create', [JobBoardController::class , 'store']);
$router->get('/jobs/{id}', [JobBoardController::class , 'show']);
$router->post('/jobs/quote', [JobBoardController::class , 'submitQuote']);
$router->post('/jobs/accept-quote', [JobBoardController::class , 'acceptQuote']);
$router->post('/jobs/reject-quote', [JobBoardController::class , 'rejectQuote']);

// Booking Routes
$router->get('/bookings/create', [BookingController::class , 'create']);
$router->post('/bookings/create', [BookingController::class , 'store']);
$router->post('/bookings/accept', [BookingController::class , 'accept']);
$router->post('/bookings/decline', [BookingController::class , 'decline']);
$router->post('/bookings/complete', [BookingController::class , 'complete']);
$router->post('/bookings/counter-offer', [BookingController::class , 'counterOffer']);
$router->post('/bookings/accept-counter', [BookingController::class , 'acceptCounter']);
$router->post('/bookings/cancel-counter', [BookingController::class , 'cancelCounter']);
$router->post('/bookings/confirm-completion', [BookingController::class , 'confirmCompletion']);

// Review Routes
$router->get('/reviews/create', [ReviewController::class , 'create']);
$router->post('/reviews/create', [ReviewController::class , 'store']);

// Favorite Routes
$router->post('/favorites/toggle', [FavoriteController::class , 'toggle']);

// Messaging Routes
$router->get('/messages', [MessageController::class , 'inbox']);
$router->get('/messages/conversation', [MessageController::class , 'conversation']);
$router->post('/messages/send', [MessageController::class , 'send']);
$router->get('/messages/poll', [MessageController::class , 'poll']);
$router->get('/messages/unread-count', [MessageController::class , 'unreadCount']);
$router->post('/messages/accept-request', [MessageController::class , 'acceptRequest']);
$router->post('/messages/decline-request', [MessageController::class , 'declineRequest']);

// Notification Routes
$router->get('/notifications', [NotificationController::class , 'index']);
$router->post('/notifications/mark-all-read', [NotificationController::class , 'markAllRead']);
$router->get('/notifications/read', [NotificationController::class , 'markRead']);
$router->get('/notifications/unread-count', [NotificationController::class , 'unreadCount']);

// Admin Routes
$router->get('/admin/dashboard', [AdminController::class , 'dashboard']);
$router->get('/admin/users', [AdminController::class , 'users']);
$router->post('/admin/users/toggle-status', [AdminController::class , 'toggleUserStatus']);
$router->get('/admin/verifications', [AdminController::class , 'verifications']);
$router->post('/admin/verifications/toggle', [AdminController::class , 'toggleVerification']);
