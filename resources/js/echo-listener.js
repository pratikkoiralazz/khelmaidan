import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY || 'khelmaidan_key',
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

const venueId = window.currentVenueId;

if (venueId) {
    window.Echo.channel(`venue.${venueId}`)
        .listen('.slot.locked', (event) => {
            const slotElement = document.querySelector(`[data-slot-id="${event.slot_id}"][data-date="${event.booking_date}"]`);
            if (slotElement) {
                slotElement.classList.add('bg-yellow-500', 'cursor-not-allowed');
                slotElement.setAttribute('disabled', 'true');
                slotElement.innerText = 'LOCKED';
            }
        })
        .listen('.booking.confirmed', (event) => {
            const slotElement = document.querySelector(`[data-slot-id="${event.slot_id}"][data-date="${event.booking_date}"]`);
            if (slotElement) {
                slotElement.classList.remove('bg-yellow-500');
                slotElement.classList.add('bg-red-600', 'cursor-not-allowed');
                slotElement.setAttribute('disabled', 'true');
                slotElement.innerText = 'BOOKED';
            }
        });
}