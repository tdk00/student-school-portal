<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\SuperAdmin;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/superadmin/login",
     *     summary="SuperAdmin login",
     *     tags={"Authentication"},
     *     description="Login a SuperAdmin and get the authentication token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="superadmin"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged in",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9"),
     *             @OA\Property(property="superadmin", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Super Admin"),
     *                 @OA\Property(property="email", type="string", example="superadmin")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function loginSuperAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string', // no email validation, it's a simple string
            'password' => 'required',
        ]);

        $superAdmin = SuperAdmin::where('email', $credentials['email'])->first();

        if ($superAdmin && Hash::check($credentials['password'], $superAdmin->password)) {
            $token = $superAdmin->createToken('superadmin_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'superadmin' => $superAdmin
            ], 200);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }
    /**
     * @OA\Post(
     *     path="/api/schools/login",
     *     summary="School login",
     *     tags={"Authentication"},
     *     description="Login a school and get the authentication token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="school@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged in",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9"),
     *             @OA\Property(property="school", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Awesome School"),
     *                 @OA\Property(property="email", type="string", example="school@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function loginSchool(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $school = School::where('email', $credentials['email'])->first();

        if ($school && Hash::check($credentials['password'], $school->password)) {
            $token = $school->createToken('school_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'school' => $school
            ], 200);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    /**
     * @OA\Post(
     *     path="/api/teachers/login",
     *     summary="Teacher login",
     *     tags={"Authentication"},
     *     description="Login a teacher and get the authentication token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="teacher@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged in",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9"),
     *             @OA\Property(property="teacher", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="teacher@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function loginTeacher(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $teacher = Teacher::where('email', $credentials['email'])->first();

        if ($teacher && Hash::check($credentials['password'], $teacher->password)) {
            $token = $teacher->createToken('teacher_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'teacher' => $teacher
            ], 200);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    /**
     * @OA\Post(
     *     path="/api/students/login",
     *     summary="Student login",
     *     tags={"Authentication"},
     *     description="Login a student and get the authentication token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="student@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged in",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9"),
     *             @OA\Property(property="student", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Jane Doe"),
     *                 @OA\Property(property="email", type="string", example="student@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function loginStudent(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $student = Student::where('email', $credentials['email'])->first();

        if ($student && Hash::check($credentials['password'], $student->password)) {
            $token = $student->createToken('student_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'student' => $student
            ], 200);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}
