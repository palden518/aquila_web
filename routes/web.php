<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* ---------------------------------------------------------------------------
 | Existing team routes — unchanged
 * ------------------------------------------------------------------------- */
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/test', function () {
    return view('test');
})->name('test');

Route::get('/menu', function () {
    return view('menu');
})->name('menu');

Route::get('/events', function () {
    return view('events');
})->name('events');


/* ---------------------------------------------------------------------------
 | Specials & packages
 * ------------------------------------------------------------------------- */
Route::get('/specials-and-packages', function () {
    return view('specials');
})->name('specials');


/* ---------------------------------------------------------------------------
 | Book a table — PREVIEW ONLY
 |
 | "Book a table" is a SECTION of the Contact page, not a page of its own.
 | This route exists so the reservation component can be demoed before the
 | Contact page is merged. Delete this route and booking-preview.blade.php
 | once <x-reservation-form /> is dropped into contact.blade.php.
 * ------------------------------------------------------------------------- */
Route::get('/book-a-table', function () {
    return view('booking-preview');
})->name('booking.preview');


/* ---------------------------------------------------------------------------
 | Form handlers
 |
 | These validate and flash a success message. They do NOT write to the
 | database yet — see the notes below before wiring them up.
 |
 |   catering_enquiries  — table exists, but its columns do not match this
 |                         form exactly: the table has `guest_count` (this
 |                         form sends `guests`) and has NO `event_type`
 |                         column at all. Someone needs to decide whether the
 |                         form drops event_type or the table gains a column.
 |
 |   reservations        — NO TABLE EXISTS. "Book a table" has nowhere to save
 |                         to. A migration is needed before this can persist.
 * ------------------------------------------------------------------------- */

Route::post('/book-a-table', function (Request $request) {

    // TODO (team): hardcoded to match the component. Move to config or a DB
    // lookup once real availability exists.
    $availableTimes = ['12:00 pm', '12:30 pm', '1:00 pm', '1:30 pm', '6:00 pm', '6:30 pm', '7:00 pm'];

    $validated = $request->validate([
        'first_name' => ['required', 'string', 'max:60'],
        'last_name'  => ['required', 'string', 'max:60'],
        'email'      => ['required', 'email', 'max:120'],
        'phone'      => ['required', 'string', 'max:20'],
        'date'       => ['required', 'date', 'after_or_equal:today'],
        'party_size' => ['required', 'integer', 'min:1', 'max:8'],
        'time'       => ['required', 'string', Rule::in($availableTimes)],
        'requests'   => ['nullable', 'string', 'max:500'],
    ], [
        'date.after_or_equal' => 'This date has already passed. Choose an upcoming date.',
        'time.required'       => 'Please choose a time.',
        'time.in'             => 'That time is not available. Please choose another.',
        'party_size.max'      => 'For 8 or more guests, please use the packages enquiry form.',
    ]);

    $message = sprintf(
        'Reservation confirmed for %s at %s, party of %d. A confirmation email has been sent to %s.',
        date('D j M', strtotime($validated['date'])),
        $validated['time'],
        $validated['party_size'],
        $validated['email']
    );

    return back()->with('reservation_success', $message);

})->name('reservations.store');


Route::post('/enquiry', function (Request $request) {

    $eventTypes = [
        'Office lunch package',
        'Corporate event package',
        'Catering package',
        'Catering — office',
        'Other',
    ];

    $request->validate([
        'name'       => ['required', 'string', 'max:80'],
        'email'      => ['required', 'email', 'max:120'],
        'phone'      => ['required', 'string', 'max:20'],
        'event_type' => ['required', 'string', Rule::in($eventTypes)],
        'event_date' => ['required', 'date', 'after_or_equal:today'],
        'guests'     => ['required', 'integer', 'min:1', 'max:200'],
        'message'    => ['nullable', 'string', 'max:1000'],
    ], [
        'event_date.after_or_equal' => 'This date has already passed. Choose an upcoming date.',
    ]);

    return back()->with(
        'enquiry_success',
        "Thanks — we've received your enquiry. Our team will reply within 1 business day."
    );

})->name('enquiries.store');
