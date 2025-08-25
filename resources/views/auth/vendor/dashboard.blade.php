<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Vendor Dashboard</h2>
        <form action="{{ route('vendor.logout') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h3>Upload Invoice</h3>
        <form action="{{ route('invoices.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="invoice_number" class="form-label">Invoice Number</label>
                <input type="text" class="form-control" id="invoice_number" name="invoice_number" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Invoice Image</label>
                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.png,.pdf" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Invoice</button>
        </form>
        <h3 class="mt-5">Your Invoices</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->amount }}</td>
                        <td>{{ $invoice->due_date }}</td>
                        <td>{{ $invoice->status }}</td>
                        <td>
                            @if ($invoice->images->where('image_type', 'Invoice')->first())
                                <a href="{{ Storage::url($invoice->images->where('image_type', 'Invoice')->first()->image_path) }}" target="_blank">View</a>
                            @endif
                        </td>
                        <td>
                            @if ($invoice->status == 'Rejected')
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#reuploadModal{{ $invoice->id }}">Re-upload</button>
                                <!-- Modal for Re-upload -->
                                <div class="modal fade" id="reuploadModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="reuploadModalLabel{{ $invoice->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="reuploadModalLabel{{ $invoice->id }}">Re-upload Invoice</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('invoices.reupload', $invoice) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="invoice_number_{{ $invoice->id }}" class="form-label">Invoice Number</label>
                                                        <input type="text" class="form-control" id="invoice_number_{{ $invoice->id }}" name="invoice_number" value="{{ $invoice->invoice_number }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="amount_{{ $invoice->id }}" class="form-label">Amount</label>
                                                        <input type="number" step="0.01" class="form-control" id="amount_{{ $invoice->id }}" name="amount" value="{{ $invoice->amount }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="due_date_{{ $invoice->id }}" class="form-label">Due Date</label>
                                                        <input type="date" class="form-control" id="due_date_{{ $invoice->id }}" name="due_date" value="{{ $invoice->due_date }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="image_{{ $invoice->id }}" class="form-label">New Invoice Image</label>
                                                        <input type="file" class="form-control" id="image_{{ $invoice->id }}" name="image" accept=".jpg,.png,.pdf" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Re-upload Invoice</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>