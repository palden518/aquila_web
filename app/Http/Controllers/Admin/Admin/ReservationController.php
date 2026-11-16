<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = Reservation::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('reservation_date', $request->date('date')))
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reservations.index', [
            'reservations' => $reservations,
            'statuses' => Reservation::STATUSES,
        ]);
    }

    public function show(Reservation $reservation)
    {
        return view('admin.reservations.show', [
            'reservation' => $reservation,
            'statuses' => Reservation::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', Reservation::STATUSES)],
        ]);

        $reservation->update($data);

        return back()->with('status', 'Reservation status updated.');
    }
}
