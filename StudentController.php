<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        return view('students.index', [
            'students' => Student::query()->latest('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('students.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $photo = $data['photo'] ?? null;
        unset($data['photo']);

        if ($photo) {
            $data['photo_path'] = $photo->store('student-photos', 'public');
        }

        Student::create($data);

        return redirect()->route('students.index')
            ->with('success', 'Student record added successfully.');
    }

    public function show(Student $student): View
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $this->validatedData($request);
        $photo = $data['photo'] ?? null;
        unset($data['photo']);

        if ($photo) {
            $newPhotoPath = $photo->store('student-photos', 'public');

            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }

            $data['photo_path'] = $newPhotoPath;
        }

        $student->update($data);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student record updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        if ($student->photo_path) {
            Storage::disk('public')->delete($student->photo_path);
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student record deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'lastname' => ['required', 'string', 'max:50'],
            'firstname' => ['required', 'string', 'max:50'],
            'province' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:50'],
            'school' => ['required', 'string', 'max:50'],
            'program' => ['required', 'string', 'max:10'],
            'year' => ['required', 'integer', 'between:1,10'],
            'birthday' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:102400'],
        ]);
    }
}
