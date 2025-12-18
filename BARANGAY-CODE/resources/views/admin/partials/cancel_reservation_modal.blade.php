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
                <div class="space-y-1">
                    <label for="cancellation_reason_select" class="block text-xs font-semibold text-gray-600 mb-1.5">REASON FOR CANCELLATION <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="cancellation_reason_select" name="cancellation_reason_select" 
                            class="appearance-none w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all bg-white pr-10 cursor-pointer" 
                            required>
                            <option value="" disabled selected>Select a reason...</option>
                            <option value="Resident did not show up" class="text-gray-700 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-user-slash text-gray-500 mr-2"></i>
                                    <span>Resident did not show up</span>
                                </div>
                            </option>
                            <option value="Facility maintenance required" class="text-gray-700 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-tools text-gray-500 mr-2"></i>
                                    <span>Facility maintenance required</span>
                                </div>
                            </option>
                            <option value="Double booking conflict" class="text-gray-700 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-times text-gray-500 mr-2"></i>
                                    <span>Double booking conflict</span>
                                </div>
                            </option>
                            <option value="Emergency closure" class="text-gray-700 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                                    <span>Emergency closure</span>
                                </div>
                            </option>
                            <option value="Policy violation" class="text-gray-700 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-ban text-red-500 mr-2"></i>
                                    <span>Policy violation</span>
                                </div>
                            </option>
                            <option value="Weather conditions" class="text-gray-700 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-cloud-rain text-blue-500 mr-2"></i>
                                    <span>Weather conditions</span>
                                </div>
                            </option>
                            <option value="Others" class="text-gray-700 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <i class="fas fa-ellipsis-h text-gray-500 mr-2"></i>
                                    <span>Others</span>
                                </div>
                            </option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>

                
                <!-- Others Textarea (shown when "Others" is selected) -->
                <div id="others_reason_container" class="hidden space-y-1">
                    <label for="cancellation_reason_others" class="block text-xs font-semibold text-gray-600 mb-1.5">PLEASE SPECIFY <span class="text-red-500">*</span></label>
                     <textarea id="cancellation_reason_others" name="cancellation_reason_others" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Please provide a reason for cancellation"></textarea>
                </div>

                <!-- Hidden field to store the final cancellation reason -->
                <input type="hidden" id="cancellation_reason" name="cancellation_reason">

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
