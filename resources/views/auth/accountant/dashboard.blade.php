<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Accountant Dashboard</h2>
        <form action="{{ route('accountant.logout') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h3>Approved Invoices</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Vendor</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Invoice Image</th>
                    <th>Payment Proof</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->vendor->username }}</td>
                        <td>{{ $invoice->amount }}</td>
                        <td>{{ $invoice->due_date }}</td>
                        <td>{{ $invoice->status }}</td>
                        <td>
                            @if ($invoice->images->where('image_type', 'Invoice')->first())
                                <a href="{{ Storage::url($invoice->images->where('image_type', 'Invoice')->first()->image_path) }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>
                            @if ($invoice->images->where('image_type', 'Payment_Proof')->first())
                                <a href="{{ Storage::url($invoice->images->where('image_type', 'Payment_Proof')->first()->image_path) }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>
                            @if ($invoice->status == 'Approved')
                                <form action="{{ route('invoices.markPaid', $invoice) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <input type="file" name="payment_proof" accept=".jpg,.png,.pdf" required>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">Mark Paid</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>