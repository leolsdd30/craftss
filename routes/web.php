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
use App\Controllers\PasswordResetController;
use App\Controllers\ApiController;
use App\Controllers\EmailVerificationController;

/**
 * Register all web routes here.
 * The $router object is provided by public/index.php
 *
 * IMPORTANT: Specific static routes MUST be registered before dynamic
 * {param} routes that share the same prefix. The router matches in
 * registration order and stops at the first match.
 */

$router->get('/', [HomeController::class , 'index']);
$router->get('/about', [HomeController::class , 'about']);
$router->get('/contact', [HomeController::class , 'contact']);
$router->get('/privacy', [HomeController::class , 'privacy']);

// API Routes
$router->get('/api/poll', [ApiController::class , 'poll']);

// Public Search & Profile Routes
$router->get('/search', [SearchController::class , 'index']);
$router->get('/profile', [ProfileController::class , 'show']);

// Profile Management Routes — must come before /profile/{username}
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

// Password Reset Routes
$router->get('/forgot-password', [PasswordResetController::class , 'showForgotForm']);
$router->post('/forgot-password', [PasswordResetController::class , 'sendResetLink']);
$router->get('/reset-password', [PasswordResetController::class , 'showResetForm']);
$router->post('/reset-password', [PasswordResetController::class , 'processReset']);

// Email Verification Routes
$router->get('/verify-email', [EmailVerificationController::class , 'verify']);
$router->get('/verify-notice', [EmailVerificationController::class , 'notice']);
$router->post('/verify-resend', [EmailVerificationController::class , 'resend']);

// Role-based Dashboards
$router->get('/homeowner/dashboard', [HomeownerController::class , 'dashboard']);
$router->get('/craftsman/dashboard', [CraftsmanController::class , 'dashboard']);

// Job Board Routes
$router->get('/jobs', [JobBoardController::class , 'index']);
$router->get('/jobs/create', [JobBoardController::class , 'create']);
$router->post('/jobs/create', [JobBoardController::class , 'store']);
$router->get('/jobs/edit/{id}', [JobBoardController::class , 'edit']);
$router->post('/jobs/edit/{id}', [JobBoardController::class , 'update']);
$router->get('/jobs/{id}', [JobBoardController::class , 'show']);
$router->post('/jobs/quote', [JobBoardController::class , 'submitQuote']);
$router->post('/jobs/accept-quote', [JobBoardController::class , 'acceptQuote']);
$router->post('/jobs/reject-quote', [JobBoardController::class , 'rejectQuote']);
$router->post('/jobs/delete', [JobBoardController::class , 'deleteJob']);

// Booking Routes
$router->get('/bookings/create/{username}', [BookingController::class , 'create']);
$router->post('/bookings/create', [BookingController::class , 'store']);
$router->post('/bookings/accept', [BookingController::class , 'accept']);
$router->post('/bookings/decline', [BookingController::class , 'decline']);
$router->post('/bookings/complete', [BookingController::class , 'complete']);
$router->post('/bookings/counter-offer', [BookingController::class , 'counterOffer']);
$router->post('/bookings/accept-counter', [BookingController::class , 'acceptCounter']);
$router->post('/bookings/cancel-counter', [BookingController::class , 'cancelCounter']);
$router->post('/bookings/confirm-completion', [BookingController::class , 'confirmCompletion']);

// Review Routes
$router->get('/reviews/create/{bookingId}', [ReviewController::class , 'create']);
$router->post('/reviews/create', [ReviewController::class , 'store']);

// Favorite Routes
$router->post('/favorites/toggle', [FavoriteController::class , 'toggle']);

// ─── Messaging Routes ──────────────────────────────────────────────────────
// IMPORTANT ORDER: All static /messages/xxx routes MUST be registered
// before /messages/{username} — otherwise the dynamic route swallows them.

// Base inbox
$router->get('/messages', [MessageController::class , 'inbox']);

// Static sub-routes — registered BEFORE the dynamic {username} route
$router->get('/messages/requests', [MessageController::class , 'requests']);
$router->get('/messages/conversation', [MessageController::class , 'conversation']);
$router->get('/messages/poll', [MessageController::class , 'poll']);
$router->get('/messages/unread-count', [MessageController::class , 'unreadCount']);
$router->get('/messages/user-info', [MessageController::class , 'userInfo']);
$router->get('/messages/poll-inbox', [MessageController::class , 'pollInbox']);

// Dynamic route — must come after all static /messages/xxx routes
$router->get('/messages/{username}', [MessageController::class , 'inbox']);

// POST routes (order doesn't matter for these since no dynamic GET conflict)
$router->post('/messages/send', [MessageController::class , 'send']);
$router->post('/messages/accept-request', [MessageController::class , 'acceptRequest']);
$router->post('/messages/decline-request', [MessageController::class , 'declineRequest']);
$router->post('/messages/pin', [MessageController::class , 'pin']);
$router->post('/messages/mute', [MessageController::class , 'mute']);
$router->post('/messages/delete', [MessageController::class , 'delete']);
$router->post('/messages/delete-message', [MessageController::class , 'deleteSingleMessage']);
$router->post('/messages/mark-read', [MessageController::class , 'markRead']);
$router->post('/messages/folder', [MessageController::class , 'setFolder']);
// ───────────────────────────────────────────────────────────────────────────

// Notification Routes
$router->get('/notifications', [NotificationController::class , 'index']);
$router->post('/notifications/mark-all-read', [NotificationController::class , 'markAllRead']);
$router->get('/notifications/read', [NotificationController::class , 'markRead']);
$router->post('/notifications/read', [NotificationController::class , 'markRead']);
$router->get('/notifications/unread-count', [NotificationController::class , 'unreadCount']);
$router->post('/notifications/delete',     [NotificationController::class, 'delete']);
$router->post('/notifications/delete-all', [NotificationController::class, 'deleteAll']);

// Admin Routes
$router->get('/admin/dashboard', [AdminController::class , 'dashboard']);
$router->get('/admin/users', [AdminController::class , 'users']);
$router->post('/admin/users/toggle-status', [AdminController::class , 'toggleUserStatus']);
$router->get('/admin/verifications', [AdminController::class , 'verifications']);
$router->post('/admin/verifications/toggle', [AdminController::class , 'toggleVerification']);