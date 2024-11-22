<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Employee;
use App\Models\ProjectAssignment;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;

class Assignment extends Component
{
    public $projects;
    public $employees;
    public $selectedEmployees = [];
    public $projectId;

    public function mount()
    {
        $subkonId = Auth::user()->subkon_id;
        $this->projects = Project::where('subkon_id', $subkonId)->get();
        $this->employees = Employee::where('subkon_id', $subkonId)->get();
    }

    public function selectEmployee($projectId, $employeeId)
    {
        // Toggle employee selection for the project
        if (isset($this->selectedEmployees[$projectId]) && in_array($employeeId, $this->selectedEmployees[$projectId])) {
            $this->selectedEmployees[$projectId] = array_diff($this->selectedEmployees[$projectId], [$employeeId]);
        } else {
            $this->selectedEmployees[$projectId][] = $employeeId;
        }
    }

    public function assignEmployee($projectId, $employeeId)
    {
        // Assign a single employee to a project if available
        $employee = Employee::find($employeeId);
         dd('masukk', $projectId);
        if ($employee && $employee->status === 'available') {
            $this->createProjectAssignment($projectId, $employee);
            session()->flash('message', 'Employee assigned successfully!');
            return redirect()->route('sandana/projects');
        }

        session()->flash('error', 'Employee is not available for assignment.');
    }

    public function assignEmployees($projectId)
    {
       
        // Assign all selected employees to a project
        if (!isset($this->selectedEmployees[$projectId])) {
            session()->flash('error', 'No employees selected for assignment.');
            return;
        }


        foreach ($this->selectedEmployees[$projectId] as $employeeId) {
            $employee = Employee::find($employeeId);

            if ($employee && $employee->status === 'available') {
                $this->createProjectAssignment($projectId, $employee);
            }
        }

        $this->refreshProjects();
        session()->flash('message', 'Selected employees assigned successfully!');
        return redirect()->route('sandana.projects');
        
    }

    public function newbie(){
        dd('masuk');
       
    }

    public function sendBack(){
         return redirect()->route('sandana.projects');
    }

    protected function createProjectAssignment($projectId, $employee)
    {
        ProjectAssignment::create([
            'project_id' => $projectId,
            'employee_id' => $employee->id,
            'status' => 'assigned',
        ]);
        

        $employee->update(['status' => 'assigned']);
    }

    protected function refreshProjects()
    {
        $this->projects = Project::with('assignments.employee')->get();
    }

    public function store()
    {
        dd('masuk');
        $this->assignEmployees($this->projectId);
        // return redirect()->route('sandana/projects');
    }

    public function render()
    {
        //ini bikin halaman baru
        return view('livewire.assignment');
    }
}

