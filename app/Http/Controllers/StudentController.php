<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display the authenticated student's profile.
     *
     * @OA\Get(
     *     path="/api/students/profile",
     *     summary="View student's profile",
     *     tags={"Students"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Student profile details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="class_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found"
     *     )
     * )
     */
    public function profile()
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json([
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'class_id' => $student->school_class_id,
        ], 200);
    }

    /**
     * Display the authenticated student's school details.
     *
     * @OA\Get(
     *     path="/api/students/school",
     *     summary="View student's school details",
     *     tags={"Students"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="School details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="ABC School"),
     *             @OA\Property(property="email", type="string", example="school@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="School not found"
     *     )
     * )
     */
    public function school()
    {
        $student = Auth::guard('student')->user();
        $school = $student->school;

        if (!$school) {
            return response()->json(['message' => 'School not found'], 404);
        }

        return response()->json([
            'id' => $school->id,
            'name' => $school->name,
            'email' => $school->email,
        ], 200);
    }

    /**
     * Display the authenticated student's class details.
     *
     * @OA\Get(
     *     path="/api/students/class",
     *     summary="View student's class details",
     *     tags={"Students"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Class details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="5th Grade"),
     *             @OA\Property(property="school_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Class not found"
     *     )
     * )
     */
    public function schoolClass()
    {
        $student = Auth::guard('student')->user();
        $class = $student->schoolClass;

        if (!$class) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        return response()->json([
            'id' => $class->id,
            'name' => $class->name,
            'school_id' => $class->school_id,
        ], 200);
    }

    /**
     * Display the authenticated student's teacher details.
     *
     * @OA\Get(
     *     path="/api/students/teacher",
     *     summary="View student's teacher details",
     *     tags={"Students"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Teacher details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Mr. Smith"),
     *             @OA\Property(property="email", type="string", example="mrsmith@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Teacher not found"
     *     )
     * )
     */
    public function teacher()
    {
        $student = Auth::guard('student')->user();
        $class = $student->schoolClass;
        $teacher = $class ? $class->teacher : null;

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        return response()->json([
            'id' => $teacher->id,
            'name' => $teacher->name,
            'email' => $teacher->email,
        ], 200);
    }
}
