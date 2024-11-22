<div class="p-4">
    

<nav class="bg-white border-gray-200 dark:bg-gray-900">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
  <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Flowbite</span>
  </a>
  <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
      <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
        <span class="sr-only">Open user menu</span>
        <img class="w-8 h-8 rounded-full" src="" alt="user photo">
      </button>
      <!-- Dropdown menu -->
      <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
        <div class="px-4 py-3">
          <span class="block text-sm text-gray-900 dark:text-white">Bonnie Green</span>
          <span class="block text-sm  text-gray-500 truncate dark:text-gray-400">name@flowbite.com</span>
        </div>
        <ul class="py-2" aria-labelledby="user-menu-button">
          <li>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Dashboard</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Earnings</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a>
          </li>
        </ul>
      </div>
      <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-user" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
  </div>
  </div>
</nav>

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
                            <!-- <form wire:submit="store" enctype="multipart/form-data">
                             
                            <button
                                type="submit"
                                wire:click="assignEmployees({{ $project->id }})"
                                class="mt-4 px-4 py-2 bg-green-500 text-white rounded">
                                Submit Assignments 
                            </button>
                            </form> -->
                            <form action="{{route('sandana.projects')}}" method="get">
  
                            <button type="submit" class="mt-4 px-4 py-2 bg-green-500 text-white rounded">
                                Submit Assignments
                            </button>
                        </form>
                            
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

