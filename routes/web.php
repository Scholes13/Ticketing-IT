<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\ReportingController;

// Public routes (no login required)
Route::get('/', function () {
    return redirect()->route('public.create.ticket');
});

// Ticket submission form
Route::get('/ticket/create', [PublicController::class, 'createTicket'])->name('public.create.ticket');
Route::post('/ticket/store', [PublicController::class, 'storeTicket'])->name('public.store.ticket');
Route::get('/ticket/confirmation/{ticket_number}', [PublicController::class, 'ticketConfirmation'])->name('public.ticket.confirmation');

// Ticket status check
Route::get('/ticket/check', [PublicController::class, 'checkTicketForm'])->name('public.check.ticket');
Route::post('/ticket/status', [PublicController::class, 'checkTicketStatus'])->name('public.ticket.status');
Route::get('/ticket/status/{ticket_number}', [PublicController::class, 'viewTicketStatus'])->name('public.view.ticket.status');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Admin routes (login required)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');
    
    // Reporting
    Route::get('/admin/reporting', [ReportingController::class, 'index'])->name('reporting');
    Route::get('/admin/reporting/export/excel', [ReportingController::class, 'exportExcel'])->name('reporting.export.excel');
    Route::get('/admin/reporting/export/pdf', [ReportingController::class, 'exportPdf'])->name('reporting.export.pdf');
    
    // Ticket Category Views
    Route::get('/tickets/waiting', [TicketCategoryController::class, 'waiting'])->name('tickets.waiting');
    Route::get('/tickets/in-progress', [TicketCategoryController::class, 'inProgress'])->name('tickets.in-progress');
    Route::get('/tickets/done', [TicketCategoryController::class, 'done'])->name('tickets.done');
    Route::get('/tickets/all', [TicketCategoryController::class, 'all'])->name('tickets.all');
    
    // Tickets management
    Route::resource('tickets', TicketController::class)->except(['destroy']);
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    
    // Additional ticket actions
    Route::post('/tickets/{id}/comment', [TicketController::class, 'addComment'])->name('tickets.comment');
    Route::put('/tickets/{id}/change-status', [TicketController::class, 'changeStatus'])->name('tickets.change-status');
    Route::post('/tickets/{id}/assign', [TicketController::class, 'assignTicket'])->name('tickets.assign');
    
    // Categories management
    Route::resource('admin/categories', CategoryController::class)->names('admin.categories');
    
    // Departments management
    Route::resource('admin/departments', DepartmentController::class)->names('admin.departments');
    
    // Staff management
    Route::resource('staff', AdminController::class);
});
