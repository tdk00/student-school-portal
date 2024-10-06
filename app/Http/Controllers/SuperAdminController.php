<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/superadmin/schools",
     *     summary="Create a new school",
     *     tags={"SuperAdmin"},
     *     description="Allows SuperAdmin to create a new school",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="New School"),
     *             @OA\Property(property="email", type="string", example="school@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="School added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="School added successfully"),
     *             @OA\Property(property="school", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="New School"),
     *                 @OA\Property(property="email", type="string", example="school@example.com"),
     *                 @OA\Property(property="created_at", type="string", example="2024-10-06T10:15:30.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-10-06T10:15:30.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 ),
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="The email field must be a valid email address.")
     *                 ),
     *                 @OA\Property(property="password", type="array",
     *                     @OA\Items(type="string", example="The password field must be at least 6 characters.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:schools',
            'password' => 'required|string|min:6',
        ]);

        // Create a new school
        $school = School::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message' => 'School added successfully', 'school' => $school], 201);
    }
}

