<div class="p-4">
    <div class="flex space-x-8">
        <!-- Projects List -->
        <div class="w-2/3">
            @foreach ($projects as $project)
            <div class="space-y-4">
                <h2 class="text-lg font-bold mb-4">Detail Proyek</h2>
                    <div class="block max-w-lg p-6 bg-white border border-gray-200 rounded-lg shadow  dark:bg-gray-800 dark:border-gray-700">
                        <h3 class="text-lg font-bold">{{ $project->name }}</h3>
                        <h2 class="text-lg font-bold">Dibutuhkan:{{ $project->total_needed }}</h2>
                        <h2 class="text-lg font-bold">Sertifikat Keahlian: 
                            {{ is_array($project->certificates_skills) 
                                ? implode(', ', array_column($project->certificates_skills, 'skill')) 
                                : 'No certificates' }}
                        </h2>

                        <!-- Employee Assignment List -->
                        <ul class="mt-2">
                            @foreach ($project->assignments as $assignment)
                                <li class="py-2">
                                    {{ $assignment->employee->name }}
                                    <span class="text-sm text-gray-500">({{ $assignment->status }})</span>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Employee Selection Buttons -->
                        <div class="mt-4">
                            <h4 class="text-sm font-bold mb-2">Assign Employees:</h4>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ($employees as $employee)
                                    <button
                                        wire:click="selectEmployee({{ $project->id }}, {{ $employee->id }})"
                                        class="px-4 py-2 rounded
                                        {{ isset($selectedEmployees[$project->id]) && in_array($employee->id, $selectedEmployees[$project->id]) ? 'bg-blue-500 text-white' : 'bg-gray-200' }}"
                                        {{ $employee->status === 'assigned' ? 'disabled' : '' }}
                                        style="{{ $employee->status === 'assigned' ? 'cursor: not-allowed; opacity: 0.5;' : '' }}"
                                    >
                                        {{ $employee->name }} - {{ $employee->speciality }}
                                    </button>
                                @endforeach
                            </div>

                            <!-- Submit Button -->
                            <button
                                wire:click="assignEmployees({{ $project->id }})"
                                class="mt-4 px-4 py-2 bg-green-500 text-white rounded">
                                Submit Assignments
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

