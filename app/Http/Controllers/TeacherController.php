<?php
namespace App\Http\Controllers;
namespace App\Http\Controllers;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/teachers/class",
     *     summary="View the class assigned to the authenticated teacher",
     *     tags={"Teachers"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Class details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="5th Grade"),
     *             @OA\Property(property="school_id", type="integer", example=1),
     *             @OA\Property(property="teacher_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No class assigned to this teacher"
     *     )
     * )
     */
    public function class()
    {
        $teacher = Auth::guard('teacher')->user();  // Authenticated teacher

        // Get the class the teacher is assigned to
        $class = SchoolClass::where('teacher_id', $teacher->id)->first();

        if (!$class) {
            return response()->json(['message' => 'No class assigned to this teacher'], 404);
        }

        return response()->json($class, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/teachers/class/students",
     *     summary="View the students in the class assigned to the authenticated teacher",
     *     tags={"Teachers"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of students",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="class_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No class assigned to this teacher"
     *     )
     * )
     */
    public function students()
    {
        $teacher = Auth::guard('teacher')->user();  // Authenticated teacher

        // Get the class the teacher is assigned to
        $class = SchoolClass::where('teacher_id', $teacher->id)->first();

        if (!$class) {
            return response()->json(['message' => 'No class assigned to this teacher'], 404);
        }

        // Get the students in the class
        $students = $class->students;

        return response()->json($students, 200);
    }
}

