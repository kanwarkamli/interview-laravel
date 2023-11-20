<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentsResource;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudentsController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(StudentRequest $request)
    {
        $request->validated();

        if ($request->has('email')) {
            $email = $request->input('email');

            return StudentsResource::collection(
                Student::where('email', $email)->get()
            );
        } else {
            return StudentsResource::collection(
                Student::all()
            );
        }
    }
}
