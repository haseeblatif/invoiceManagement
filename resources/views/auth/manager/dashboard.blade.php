<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manager Dashboard</h2>
        <form action="{{ route('manager.logout') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h3>Invoices</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Vendor</th>
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
                            @if ($invoice->status == 'Pending')
                                <form action="{{ route('invoices.updateStatus', $invoice) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="{{ route('invoices.updateStatus', $invoice) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
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