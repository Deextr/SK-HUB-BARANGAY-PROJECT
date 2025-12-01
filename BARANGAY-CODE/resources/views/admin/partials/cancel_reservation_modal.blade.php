<!-- Cancel Reservation Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Cancel Reservation</h3>
                <button id="cancelModalClose" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="cancelReservationForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div class="text-sm space-y-2">
                    <p class="font-medium">Are you sure you want to cancel this reservation?</p>
                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                        <div><span class="font-medium">Reference:</span> <span id="cancel_ref">—</span></div>
                        <div><span class="font-medium">Resident:</span> <span id="cancel_resident">—</span></div>
                        <div><span class="font-medium">Date/Time:</span> <span id="cancel_datetime">—</span></div>
                    </div>
                </div>

                <!-- Cancellation Reason -->
                <div>
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Cancellation <span class="text-red-500">*</span></label>
                    <textarea id="cancellation_reason" name="cancellation_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Please provide a reason for cancellation" required></textarea>
                </div>

                <!-- Suspension Option -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="apply_suspension" name="apply_suspension" type="checkbox" value="1" class="h-4 w-4 text-yellow-500 focus:ring-yellow-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="apply_suspension" class="font-medium text-gray-700">Apply Suspension Warning</label>
                        <p class="text-gray-500">Check this box if the resident didn't show up or violated reservation policies. Three violations will result in a 7-day suspension.</p>
                    </div>
                </div>

                <!-- Warning for upcoming reservations -->
                <div id="upcomingWarning" class="hidden bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg">
                    <p class="text-sm font-medium">⚠️ This reservation is scheduled to start within 10 minutes!</p>
                    <p class="text-sm">Cancelling at this time may cause significant inconvenience to the resident.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="cancelModalDismiss" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 border border-gray-300">
                        Dismiss
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Cancel Reservation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
