<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceImage;
use App\Models\InvoiceImages;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoicesController extends Controller
{
    public function vendorDashboard()
    {
        $invoices = Invoices::where('vendor_id', Auth::guard('vendor')->id())->with('images')->get();
        return view('auth.vendor.dashboard', compact('invoices'));
    }

    public function managerDashboard()
    {
        $invoices = Invoices::with('vendor', 'images')->get();
        return view('auth.manager.dashboard', compact('invoices'));
    }

    public function accountantDashboard()
    {
        $invoices = Invoices::where('status', 'Approved')->with('vendor', 'images')->get();
        return view('auth.accountant.dashboard', compact('invoices'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'image' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $invoice = Invoices::create([
            'vendor_id' => Auth::guard('vendor')->id(),
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => 'Pending',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('invoices', 'public');
            InvoiceImages::create([
                'invoices_id' => $invoice->id,
                'image_type' => 'Invoice',
                'image_path' => $path,
                'uploaded_by' => Auth::guard('vendor')->id(),
            ]);
        }

        return redirect()->route('vendor.dashboard')->with('success', 'Invoice uploaded successfully.');
    }

    public function updateStatus(Request $request, Invoices $invoice)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $invoice->update(['status' => $request->status]);
        return redirect()->route('manager.dashboard')->with('success', 'Invoice status updated.');
    }

    public function markPaid(Request $request, Invoices $invoice)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $invoice->update(['status' => 'Paid']);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            InvoiceImages::create([
                'invoices_id' => $invoice->id,
                'image_type' => 'Payment_Proof',
                'image_path' => $path,
                'uploaded_by' => Auth::guard('accountant')->id(),
            ]);
        }

        return redirect()->route('accountant.dashboard')->with('success', 'Invoice marked as paid.');
    }

    public function reupload(Request $request, Invoices $invoice)
    {
        if ($invoice->status !== 'Rejected' || $invoice->vendor_id !== Auth::guard('vendor')->id()) {
            return redirect()->route('vendor.dashboard')->with('error', 'Cannot re-upload this invoice.');
        }

        $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $invoice->id,
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'image' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => 'Pending',
        ]);

        if ($request->hasFile('image')) {
            // Delete old invoice image
            $oldImage = $invoice->images->where('image_type', 'Invoice')->first();
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }

            // Store new image
            $path = $request->file('image')->store('invoices', 'public');
            InvoiceImages::create([
                'invoices_id' => $invoice->id,
                'image_type' => 'Invoice',
                'image_path' => $path,
                'uploaded_by' => Auth::guard('vendor')->id(),
            ]);
        }

        return redirect()->route('vendor.dashboard')->with('success', 'Invoice re-uploaded successfully.');
    }
}