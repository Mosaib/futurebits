<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Agent Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('agent.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light ms-3">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><strong>{{ Auth::guard('agent')->user()->name }}</strong></h4>
                    </div>
                    <div class="card-body">
                        <div class="row mt-4">

                            <div class="col-md-3">
                                <div class="card text-center border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Withdrawals</h5>
                                        <p class="display-6 text-secondary">
                                            ₹{{ number_format($withdrawals->sum('amount'), 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="card text-center border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">Approved Withdrawals</h5>
                                        <p class="display-6 text-primary">
                                            ₹{{ number_format($withdrawals->where('status', 'approved')->sum('amount'), 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card text-center border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">Pending Requests</h5>
                                        <p class="display-6 text-danger">
                                            {{ $withdrawals->where('status', 'pending')->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card text-center border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">Approved Requests</h5>
                                        <p class="display-6  text-success">
                                            {{ $withdrawals->where('status', 'approved')->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h4>Request a Withdrawal</h4>
                            <form method="POST" action="{{ route('agent.withdraw.request') }}" class="row g-3">
                                @csrf
                                <div class="col-md-6">
                                    <label class="form-label">Withdrawal Amount</label>
                                    <input type="number" name="amount" step="0.01" class="form-control" required>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-success" type="submit">Submit Request</button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-5">
                            <h4>Your Withdrawal History</h4>
                            <table class="table table-striped table-bordered mt-3">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($withdrawals as $withdrawal)
                                        <tr>
                                            <td>{{ $withdrawal->created_at->format('d M Y H:i') }}</td>
                                            <td>₹{{ number_format($withdrawal->amount, 2) }}</td>
                                            <td>
                                                @if($withdrawal->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($withdrawal->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($withdrawal->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($withdrawal->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No withdrawals yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
