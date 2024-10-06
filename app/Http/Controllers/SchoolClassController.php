<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolClassController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/schools/classes",
     *     summary="List all classes for the authenticated school",
     *     tags={"Classes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of classes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="5th Grade"),
     *                 @OA\Property(property="school_id", type="integer", example=1),
     *                 @OA\Property(property="teacher_id", type="integer", nullable=true)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $school = Auth::guard('school')->user();
        $classes = SchoolClass::where('school_id', $school->id)->get();
        return response()->json($classes, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/schools/classes",
     *     summary="Create a new class for the authenticated school",
     *     tags={"Classes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="5th Grade")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Class created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Class created successfully"),
     *             @OA\Property(property="class", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="5th Grade"),
     *                 @OA\Property(property="school_id", type="integer", example=1),
     *                 @OA\Property(property="teacher_id", type="integer", nullable=true)
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $school = Auth::guard('school')->user();
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $class = SchoolClass::create([
            'name' => $request->name,
            'school_id' => $school->id,
        ]);

        return response()->json(['message' => 'Class created successfully', 'class' => $class], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/schools/classes/{class_id}",
     *     summary="Update class details for the authenticated school",
     *     tags={"Classes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="class_id",
     *         in="path",
     *         description="ID of the class",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="6th Grade"),
     *             @OA\Property(property="teacher_id", type="integer", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Class updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Class updated successfully"),
     *             @OA\Property(property="class", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="6th Grade"),
     *                 @OA\Property(property="school_id", type="integer", example=1),
     *                 @OA\Property(property="teacher_id", type="integer", nullable=true)
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $class_id)
    {
        $school = Auth::guard('school')->user();
        $class = SchoolClass::where('school_id', $school->id)->where('id', $class_id)->firstOrFail();
        $class->update($request->only('name', 'teacher_id'));

        return response()->json(['message' => 'Class updated successfully', 'class' => $class], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/schools/classes/{class_id}",
     *     summary="Delete a class for the authenticated school",
     *     tags={"Classes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="class_id",
     *         in="path",
     *         description="ID of the class",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Class deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Class deleted successfully")
     *         )
     *     )
     * )
     */
    public function destroy($class_id)
    {
        $school = Auth::guard('school')->user();
        $class = SchoolClass::where('school_id', $school->id)->where('id', $class_id)->firstOrFail();
        $class->delete();

        return response()->json(['message' => 'Class deleted successfully'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/schools/classes/{class_id}",
     *     summary="Get details of a specific class",
     *     tags={"Classes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="class_id",
     *         in="path",
     *         description="ID of the class",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Class details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="5th Grade"),
     *             @OA\Property(property="school_id", type="integer", example=1),
     *             @OA\Property(property="teacher_id", type="integer", nullable=true),
     *             @OA\Property(property="students", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function show($class_id)
    {
        $school = Auth::guard('school')->user();
        $class = SchoolClass::where('school_id', $school->id)->with('teacher', 'students')->findOrFail($class_id);

        return response()->json($class, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/schools/classes/{class_id}/assign-teacher",
     *     summary="Assign a teacher to a class",
     *     tags={"Classes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="class_id",
     *         in="path",
     *         description="ID of the class",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"teacher_id"},
     *             @OA\Property(property="teacher_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Teacher assigned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Teacher assigned successfully"),
     *             @OA\Property(property="class", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="teacher_id", type="integer", example=2)
     *             )
     *         )
     *     )
     * )
     */
    public function assignTeacher(Request $request, $class_id)
    {
        $school = Auth::guard('school')->user();
        $class = SchoolClass::where('school_id', $school->id)->findOrFail($class_id);

        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $class->update(['teacher_id' => $request->teacher_id]);

        return response()->json(['message' => 'Teacher assigned successfully', 'class' => $class], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/schools/classes/{class_id}/assign-students",
     *     summary="Assign students to a class",
     *     tags={"Classes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="class_id",
     *         in="path",
     *         description="ID of the class",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"student_ids"},
     *             @OA\Property(property="student_ids", type="array", @OA\Items(type="integer"), example={1,2,3})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Students assigned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Students assigned successfully"),
     *             @OA\Property(property="class", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="students", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function assignStudents(Request $request, $class_id)
    {
        $school = Auth::guard('school')->user();
        $class = SchoolClass::where('school_id', $school->id)->findOrFail($class_id);

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        Student::whereIn('id', $request->student_ids)->update(['school_class_id' => $class->id]);

        return response()->json(['message' => 'Students assigned successfully', 'class' => $class->load('students')], 200);
    }
}
