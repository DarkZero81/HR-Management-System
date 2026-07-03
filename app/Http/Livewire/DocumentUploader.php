<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use Illuminate\Support\Str;

class DocumentUploader extends Component
{
    use WithFileUploads;

    public $file;
    public $document_type = 'identity';
    public $document_number;
    public $expiry_date;
    public $message = '';

    protected $rules = [
        'file' => 'required|file|max:10240',
        'document_type' => 'required|string',
        'document_number' => 'nullable|string',
        'expiry_date' => 'nullable|date',
    ];

    public function upload()
    {
        $this->validate();

        $user = Auth::user();
        $employee = $user?->employee;

        if (! $employee) {
            $this->message = 'لا يوجد ملف موظف مرتبط.';
            return;
        }

        $path = $this->file->store('documents');

        Document::create([
            'employee_id' => $employee->id,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'expiry_date' => $this->expiry_date,
            'file_path' => $path,
            'uuid' => Str::uuid(),
        ]);

        $this->reset(['file', 'document_number', 'expiry_date']);
        $this->message = 'تم رفع الوثيقة بنجاح.';
        $this->emit('documentUploaded');
    }

    public function render()
    {
        return view('livewire.document-uploader');
    }
}
