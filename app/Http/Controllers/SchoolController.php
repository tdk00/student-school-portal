<?php
namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/school/profile",
     *     summary="Get the school profile of the authenticated school",
     *     tags={"School"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="School profile",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Springfield High School"),
     *             @OA\Property(property="email", type="string", example="school@example.com"),
     *             @OA\Property(property="profile_details", type="string", example="A great school in Springfield")
     *         )
     *     )
     * )
     */
    public function profile()
    {
        $school = Auth::guard('school')->user();  // Get school from token

        return response()->json($school, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/school/profile",
     *     summary="Update the authenticated school's profile",
     *     tags={"School"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="New School Name"),
     *             @OA\Property(property="email", type="string", example="newemail@example.com"),
     *             @OA\Property(property="profile_details", type="string", example="Updated profile details")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profile updated successfully")
     *         )
     *     )
     * )
     */
    public function updateProfile(Request $request)
    {
        $school = Auth::guard('school')->user();

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'profile_details' => 'nullable|string|max:1000',
        ]);

        // Update the school profile
        $school->update($request->only('name', 'email', 'profile_details'));

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/school/teachers",
     *     summary="List all teachers in the authenticated school",
     *     tags={"School"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of teachers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com")
     *             )
     *         )
     *     )
     * )
     */
    public function listTeachers()
    {
        $school = Auth::guard('school')->user();

        $teachers = Teacher::where('school_id', $school->id)->get();

        return response()->json($teachers, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/school/students",
     *     summary="List all students in the authenticated school",
     *     tags={"School"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of students",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Jane Doe"),
     *                 @OA\Property(property="email", type="string", example="janedoe@example.com"),
     *                 @OA\Property(property="class", type="string", example="5th Grade")
     *             )
     *         )
     *     )
     * )
     */
    public function listStudents()
    {
        $school = Auth::guard('school')->user();

        $students = Student::where('school_id', $school->id)->get();

        return response()->json($students, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/school/teachers",
     *     summary="Add a new teacher to the authenticated school",
     *     tags={"School"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Teacher added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Teacher added successfully"),
     *             @OA\Property(property="teacher", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com")
     *             )
     *         )
     *     )
     * )
     */
    public function storeTeacher(Request $request)
    {
        $school = Auth::guard('school')->user();

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:teachers',
            'password' => 'required|string|min:6',
        ]);

        // Create a new teacher
        $teacher = Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'school_id' => $school->id,
        ]);

        return response()->json(['message' => 'Teacher added successfully', 'teacher' => $teacher], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/school/students",
     *     summary="Add a new student to the authenticated school",
     *     tags={"School"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "class"},
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", example="janedoe@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Student added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Student added successfully"),
     *             @OA\Property(property="student", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Jane Doe"),
     *                 @OA\Property(property="email", type="string", example="janedoe@example.com"),
     *             )
     *         )
     *     )
     * )
     */
    public function storeStudent(Request $request)
    {
        $school = Auth::guard('school')->user();

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:students',
            'password' => 'required|string|min:6',
        ]);

        // Create a new student
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'school_id' => $school->id,
        ]);

        return response()->json(['message' => 'Student added successfully', 'student' => $student], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/school/teachers/{teacher_id}",
     *     summary="Update a teacher in the authenticated school",
     *     tags={"School"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="teacher_id",
     *         in="path",
     *         required=true,
     *         description="ID of the teacher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Teacher updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Teacher updated successfully"),
     *             @OA\Property(property="teacher", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com")
     *             )
     *         )
     *     )
     * )
     */
    public function updateTeacher(Request $request, $teacher_id)
    {
        $school = Auth::guard('school')->user();

        // Find the teacher in the school
        $teacher = Teacher::where('school_id', $school->id)->where('id', $teacher_id)->firstOrFail();

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:teachers,email,' . $teacher_id,
            'password' => 'nullable|string|min:6',
        ]);

        // Update the teacher's information
        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $teacher->password,
        ]);

        return response()->json(['message' => 'Teacher updated successfully', 'teacher' => $teacher], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/school/students/{student_id}",
     *     summary="Update a student in the authenticated school",
     *     tags={"School"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="student_id",
     *         in="path",
     *         required=true,
     *         description="ID of the student",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", example="janedoe@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123", nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Student updated successfully"),
     *             @OA\Property(property="student", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Jane Doe"),
     *                 @OA\Property(property="email", type="string", example="janedoe@example.com"),
     *             )
     *         )
     *     )
     * )
     */
    public function updateStudent(Request $request, $student_id)
    {
        $school = Auth::guard('school')->user();

        // Find the student in the school
        $student = Student::where('school_id', $school->id)->where('id', $student_id)->firstOrFail();

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:students,email,' . $student_id,
            'password' => 'nullable|string|min:6',
        ]);

        // Update the student's information
        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $student->password,
        ]);

        return response()->json(['message' => 'Student updated successfully', 'student' => $student], 200);
    }
}
