<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{
    /**
     * Display the user's e-commerce dashboard (Order History).
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Fetch orders belonging to the logged-in user
        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('profile.index', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    /**
     * Download a specific invoice as a PDF.
     */
    public function downloadInvoice(Order $order)
    {
        // Security Check: Ensure the user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load items and products to match the Orbita template structure
        $order->load(['items.product']);
        
        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order,
        ]);

        return $pdf->download("Orbita_Invoice_{$order->order_number}.pdf");
    }

    /**
     * Display the user's account settings form (Security/Email).
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}