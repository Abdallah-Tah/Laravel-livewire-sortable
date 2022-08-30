   <div>
       <div class="flex justify-end items-center px-4 text-right sm:px-6">
           <x-jet-button wire:click="createShowModal">
               {{ __('Create Task') }}
           </x-jet-button>
       </div>

       <br />
       <table class="min-w-full divide-y divide-gray-200">
           <thead class="bg-gray-50 text-center">
               <tr>
                   <th
                       class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                       {{ __('Create At') }}
                   </th>
                   <th
                       class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                       {{ __('Name') }}
                   </th>
                   <th
                       class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                       {{ __('Priority') }}
                   </th>
                   <th
                       class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                       {{ __('Action') }}
                   </th>
               </tr>
           </thead>
           <tbody class="bg-white divide-y divide-gray-200">
               @if ($tasks->count())
                   @foreach ($tasks as $task)
                       <tr class="hover:bg-gray-50">
                           <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                               {{ $task->created_at->format('Y-m-d H:i:s') }}
                           </td>
                           <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                               {{ $task->name }}
                           </td>
                           <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                               @if ($task->priority == 1)
                                   <span
                                       class="bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-600 dark:text-red-100">{{ __('High') }}</span>
                               @elseif ($task->priority == 0)
                                   <span
                                       class="bg-indigo-100 text-indigo-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-indigo-600 dark:text-indigo-100">{{ __('Low') }}</span>
                               @endif
                           </td>
                           <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                               <x-jet-button wire:click="editShowModal({{ $task->id }})"
                                   wire:loading.attr="disabled">
                                   {{ __('Edit') }}
                               </x-jet-button>
                               <x-jet-danger-button wire:click="deleteShowModal({{ $task->id }})"
                                   wire:loading.attr="disabled">
                                   {{ __('Delete') }}
                               </x-jet-danger-button>
                           </td>
                       </tr>
                   @endforeach
               @else
                   <tr>
                       <td colspan="3" class="px-6 py-4 text-center-sm whitespace-nowrap">No Tasks found.</td>
                   </tr>
               @endif
           </tbody>
       </table>
       <br />
       {{ $tasks->links() }}


       <x-jet-dialog-modal wire:model="modalFormVisible">
           <x-slot name="title">
               {{ __('Task') }} <strong>{{ $name }}</strong>
           </x-slot>

           <x-slot name="content">
               <div class="mt-4">
                   <x-jet-label for="name" value="{{ __('Name') }}" />
                   <x-jet-input id="name" class="block mt-1 w-full" type="text"
                       wire:model.debounce.500ms="name" name="name" required autofocus />
                   @error('name')
                       <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                   @enderror
               </div>
           </x-slot>

           <x-slot name="footer">
               <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                   {{ __('Cancel') }}
               </x-jet-secondary-button>

               @if ($taskId)
                   <x-jet-button class="ml-2" wire:click="updateTask" wire:loading.attr="disabled">
                       {{ __('Update') }}
                   </x-jet-button>
               @else
                   <x-jet-button class="ml-2" wire:click="createTask" wire:loading.attr="disabled">
                       {{ __('Create') }}
                   </x-jet-button>
               @endif
           </x-slot>
       </x-jet-dialog-modal>


       {{-- The Delete Modal --}}

       <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">
           <x-slot name="title">
               {{ __('Delete Task') }}
           </x-slot>

           <x-slot name="content">
               {{ __('Are you sure you want to delete this') }} <strong>{{ $name }}</strong>
               {{ __('? Once the Task is deleted, all of its resources and data will be permanently deleted.') }}
           </x-slot>

           <x-slot name="footer">
               <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                   {{ __('Cancel') }}
               </x-jet-secondary-button>

               <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                   {{ __('Delete') }}
               </x-jet-danger-button>
           </x-slot>
       </x-jet-dialog-modal>
   </div>
