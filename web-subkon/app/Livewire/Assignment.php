<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Employee;
use App\Models\ProjectAssignment;
use Illuminate\Support\Facades\Auth;

class Assignment extends Component
{
    public $projects;
    public $employees;
    public $selectedEmployees = [];

    public function mount()
    {
        // Load projects with assignments and employees
        $this->projects = Project::where('subkon_id', Auth::user()->subkon_id)->get();
        $this->employees = Employee::where('subkon_id', Auth::user()->subkon_id)->get();
    }

    public function selectEmployee($projectId, $employeeId)
    {
        // Check if employee is already selected for the project
        if (isset($this->selectedEmployees[$projectId]) && in_array($employeeId, $this->selectedEmployees[$projectId])) {
            // Deselect employee
            $this->selectedEmployees[$projectId] = array_diff($this->selectedEmployees[$projectId], [$employeeId]);
        } else {
            // Add employee to the selected list
            $this->selectedEmployees[$projectId][] = $employeeId;
        }
    }

    // public function assignEmployee($projectId, $employeeId)
    //     {
    //         // Fetch the employee to check their current status
    //         $employee = Employee::find($employeeId);

    //         // Check if the employee exists and their current status
    //         if ($employee && $employee->status === 'available') {
    //             // Create the Project Assignment
    //             ProjectAssignment::create([
    //                 'project_id' => $projectId,
    //                 'employee_id' => $employeeId,
    //                 'status' => 'assigned',
    //             ]);

    //             // Update the employee's status to 'assigned'
    //             $employee->update(['status' => 'assigned']);

    //             // Refresh projects data
    //             $this->projects = Project::with('assignments.employee')->get();

    //             // Optionally flash a success message
    //             session()->flash('message', 'Employee assigned successfully!');
    //             return redirect()->route('filament.resources.projects');
    //         } else {
    //             // Handle the case where the employee is not available
    //             session()->flash('error', 'Employee is not available for assignment.');
    //         }
    //     }
    public function assignEmployees($projectId)
{
    // Loop through the selected employees for the given project
    foreach ($this->selectedEmployees[$projectId] as $employeeId) {
        // Fetch the employee by ID
        $employee = Employee::find($employeeId);

        // Check if the employee exists and their status is 'available'
        if ($employee && $employee->status === 'available') {
            // Create the Project Assignment
            ProjectAssignment::create([
                'project_id' => $projectId,
                'employee_id' => $employeeId,
                'status' => 'assigned',
            ]);

            // Update the employee's status to 'assigned'
            $employee->update(['status' => 'assigned']);
        }
    }

    // Optionally, you can refresh the project assignments or other necessary data
    $this->projects = Project::with('assignments.employee')->get();
}


    public function render()
    {
        return view('livewire.assignment');
    }
}
