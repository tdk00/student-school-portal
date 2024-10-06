<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('superadmin/login', [AuthController::class, 'loginSuperAdmin']);
Route::post('schools/login', [AuthController::class, 'loginSchool']);
Route::post('teachers/login', [AuthController::class, 'loginTeacher']);
Route::post('students/login', [AuthController::class, 'loginStudent']);

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::middleware('schooladmin')->group(function () {

            Route::prefix('schools/classes')->group(function () {
                Route::get('/', [SchoolClassController::class, 'index'])->name('schools.classes.index');  // List all classes
                Route::post('/', [SchoolClassController::class, 'store'])->name('schools.classes.store'); // Create new class
                Route::put('{class_id}', [SchoolClassController::class, 'update'])->name('schools.classes.update'); // Update class
                Route::delete('{class_id}', [SchoolClassController::class, 'destroy'])->name('schools.classes.destroy'); // Delete class
                Route::get('{class_id}', [SchoolClassController::class, 'show'])->name('schools.classes.show'); // Show class details

                Route::post('{class_id}/assign-teacher', [SchoolClassController::class, 'assignTeacher'])->name('schools.classes.assign.teacher'); // Assign a teacher to class
                Route::post('{class_id}/assign-students', [SchoolClassController::class, 'assignStudents'])->name('schools.classes.assign.students'); // Assign students to class
            });


            Route::prefix('school')->group(function () {
                Route::get('profile', [SchoolController::class, 'profile'])->name('schools.profile');  // View school profile
                Route::put('profile', [SchoolController::class, 'updateProfile'])->name('schools.profile.update'); // Update school profile

                // Teacher Management Routes
                Route::get('teachers', [SchoolController::class, 'listTeachers'])->name('schools.teachers.list'); // List all teachers in the school
                Route::post('teachers', [SchoolController::class, 'storeTeacher'])->name('schools.teachers.store');  // Add new teacher to the school
                Route::put('teachers/{teacher_id}', [SchoolController::class, 'updateTeacher'])->name('schools.teachers.update'); // Update existing teacher
                Route::delete('teachers/{teacher_id}', [SchoolController::class, 'destroyTeacher'])->name('schools.teachers.destroy'); // Delete teacher from the school

                // Student Management Routes
                Route::get('students', [SchoolController::class, 'listStudents'])->name('schools.students.list'); // List all students in the school
                Route::post('students', [SchoolController::class, 'storeStudent'])->name('schools.students.store');  // Add new student to the school
                Route::get('students/{student_id}', [StudentController::class, 'show'])->name('schools.students.show');  // View a student's details
                Route::put('students/{student_id}', [SchoolController::class, 'updateStudent'])->name('schools.students.update'); // Update existing student
                Route::delete('students/{student_id}', [SchoolController::class, 'destroyStudent'])->name('schools.students.destroy'); // Delete student from the school
                // Show student details
            });
        });

        // Teacher Routes
        Route::middleware('teacher')->prefix('teachers')->group(function () {
            Route::get('class', [TeacherController::class, 'class'])->name('teachers.class'); // View the teacher's assigned class
            Route::get('class/students', [TeacherController::class, 'students'])->name('teachers.class.students'); // View the students in the teacher's class
        });

        // Super Admin Routes
        Route::middleware('superadmin')->prefix('superadmin')->group(function () {
            Route::post('schools', [SuperAdminController::class, 'store'])->name('superadmin.schools.store'); // Create new school
            Route::post('schools/{school_id}/students', [SchoolController::class, 'storeStudent'])->name('superadmin.schools.students.store'); // Super Admin store student
        });

        Route::middleware('student')->prefix('students')->group(function () {
            Route::get('profile', [StudentController::class, 'profile'])->name('students.profile'); // View student's profile
            Route::get('school', [StudentController::class, 'school'])->name('students.school'); // View student's school details
            Route::get('class', [StudentController::class, 'schoolClass'])->name('students.class'); // View student's class details
            Route::get('teacher', [StudentController::class, 'teacher'])->name('students.teacher'); // View student's teacher details
        });

    });

