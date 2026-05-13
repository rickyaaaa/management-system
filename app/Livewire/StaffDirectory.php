<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffDirectory extends Component
{
    public $showModal = false;
    public $editMode = false;
    public $editUserId = null;
    public $confirmDeleteId = null;

    public $name = '';
    public $username = '';
    public $email = '';
    public $password = '';
    public $role_specialty = 'Modeling';
    public $search = '';

    protected $specialties = ['Modeling', 'Texturing', 'RIG', 'Animation', 'LRC'];

    public function getSpecialtiesProperty()
    {
        return $this->specialties;
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $user = User::findOrFail($id);
        $this->editUserId = $id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role_specialty = $user->role_specialty;
        $this->password = '';
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $this->editUserId,
            'email' => 'required|email|unique:users,email,' . $this->editUserId,
            'role_specialty' => 'required|in:Modeling,Texturing,RIG,Animation,LRC',
        ];

        if (!$this->editMode) {
            $rules['password'] = 'required|string|min:6';
        }

        $this->validate($rules);

        if ($this->editMode) {
            $user = User::findOrFail($this->editUserId);
            $data = [
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'role_specialty' => $this->role_specialty,
            ];
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);
            $this->dispatch('notify', message: 'Staff updated successfully.');
        } else {
            User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_level' => 2,
                'role_specialty' => $this->role_specialty,
            ]);
            $this->dispatch('notify', message: 'New specialist added.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteUser()
    {
        if ($this->confirmDeleteId) {
            User::findOrFail($this->confirmDeleteId)->delete();
            $this->confirmDeleteId = null;
            $this->dispatch('notify', message: 'Staff removed.');
        }
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'username', 'email', 'password', 'editUserId']);
        $this->role_specialty = 'Modeling';
    }

    public function render()
    {
        $query = User::where('role_level', 2);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('role_specialty', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.staff-directory', [
            'staff' => $query->latest()->get(),
        ]);
    }
}
