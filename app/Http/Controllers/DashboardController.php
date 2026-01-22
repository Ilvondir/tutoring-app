<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Homework;
use App\Models\LearningSession;
use App\Repositories\Eloquent\ChartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    protected ChartRepository $chartRepository;

    public function __construct(ChartRepository $chartRepository)
    {
        $this->chartRepository = $chartRepository;
    }

    /**
     * @return Response
     */
    public function dashboard()
    {
        $role = Auth::user()->roles[0]->name;

        $stats = [
            'student' => [
                'finished_homeworks' => $role == 'student' ?
                    Homework::whereStudentId(Auth::id())
                        ->whereNotNull('complete_date')
                        ->count()
                    : 0,
                'finished_exercises' => $role == 'student' ?
                    Exercise::whereHas('homework', function ($query) {
                        $query->where('student_id', Auth::id());
                    })
                        ->whereNotNull('complete_date')
                        ->count()
                    : 0,
                'learning_time' => $role == 'student' ?
                    LearningSession::whereUserId(Auth::id())
                        ->sum('seconds')
                    : 0,
            ],
            'teacher' => [
                'created_homeworks' => $role == 'teacher' ?
                    Homework::whereTeacherId(Auth::id())->count()
                    : 0,
                'created_exercises' => $role == 'teacher' ?
                    Exercise::whereHas('homework', function ($query) {
                        $query->where('teacher_id', Auth::id());
                    })->count()
                    : 0,
            ]
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
        ]);
    }

    //
    public function charts()
    {

    }
}
