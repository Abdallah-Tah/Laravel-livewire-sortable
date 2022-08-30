<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class Tasks extends Component
{
    use WithPagination;
    public $name;
    public $taskId;   

    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;


    /**
     * rules
     *
     * @return void
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:70',
        ];
    }

    /**
     * showNodalForm
     *
     * @return void
     */
    public function createShowModal()
    {
        $this->resetValidation();
        $this->resetInput();
        $this->modalFormVisible = true;
    }
    
    /**
     * updateShowModal
     *
     * @param  mixed $Task
     * @return void
     */
    public function editShowModal($id)
    {
        $this->taskId = $id;
        $this->modalFormVisible = true;
        $this->loadTask();
    }


    /**
     * Shows the delete confirmation modal.
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteShowModal($id)
    {
        $this->taskId = $id;
        $this->name = Task::find($id)->name;
        $this->modalConfirmDeleteVisible = true;
    }


    /**
     * The delete function.
     *
     * @return void
     */
    public function delete()
    {
        Task::destroy($this->taskId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();

        session()->flash('message', 'The Task has been deleted.');
    }
  
    /**
     * loadTask
     *
     * @return void
     */
    public function loadTask()
    {
        $task = Task::find($this->taskId);
        $this->name = $task->name;
    }

    /**
     * createTask
     *
     * @return void
     */
    public function createTask()
    {
        $this->validate();
        $task_latest_id = Task::latest()->first()->id;
        $task = Task::create([
            'name' => $this->name,
            'priority' => $task_latest_id + 1,
        ]);
        $this->modalFormVisible = false;
        $this->resetPage();
        session()->flash('message', 'The task has been created.');
    }
    
    /**
     * read
     *
     * @return void
     */
    public function getTasks()
    {
        return Task::orderBy('priority')->paginate(10);
    }


    public function modelData()
    {
        return [
            'name' => $this->name,
        ];
    }

    /**
     * resetInput
     *
     * @return void
     */
    public function resetInput()
    {
        $this->taskId = null;
        $this->name = null;
    }

    /**
     * resetValidation
     *
     * @return void
     */
    public function resetValidations()
    {
        $this->rules();
    }

    public function mount()
    {
        $this->resetInput();
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.tasks', [
            'tasks' => $this->getTasks(),
        ]);
    }

    public function updateOrder($list)
    {
        foreach ($list as $item) {
            $task = Task::find($item['value']);
            $task->priority = $item['order'];
            $task->save();
        }
    }
}
