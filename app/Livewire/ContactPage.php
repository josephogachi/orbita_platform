<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Contact Us - Orbita Kenya')]
class ContactPage extends Component
{
    public $name, $email, $phone, $subject, $message;

    public function submit()
    {
        // 1. Validate the input
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'subject' => 'required|min:3',
            'message' => 'required|min:10',
        ]);

        // 2. Create the record in the database
        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        // 3. Reset form and flash success message
        $this->reset();
        session()->flash('success', 'Message received! We will get back to you shortly.');
    }

    public function render()
    {
        /** * We use 'layouts.blank' to remove the default app/guest headers 
         * and 'back to cart' navigation. Ensure resources/views/layouts/blank.blade.php exists.
         */
        return view('livewire.contact-page')->layout('layouts.blank');
    }
}