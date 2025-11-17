@extends('layouts.resident_panel')

@section('title', '')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8 mb-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Terms & Conditions</h2>
        
        <div class="mb-6">
            <p class="text-gray-600 mb-4">Before proceeding with your reservation, please read and accept the following terms and conditions:</p>
        </div>
        
        <!-- Terms and Conditions Modal Content -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <div class="h-72 md:h-80 overflow-y-auto pr-2 mb-4 text-gray-700 text-sm md:text-base space-y-4" id="termsContent" tabindex="0" aria-label="Terms and conditions content">
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">1. Rules During Use</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Playing games is prohibited since this is a study hub for students unless it is for school purposes and validated by SK officials.</li>
                        <li>Users must maintain cleanliness, orderliness, and proper decorum at all times.</li>
                        <li>Strictly no alcohol, smoking, or gambling inside or near the SK Hub.</li>
                        <li>Playing loud music or causing disturbances is prohibited unless allowed during events.</li>
                        <li>Users are responsible for their own belongings. The SK is not liable for lost or stolen items.</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">2. Time of Use</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Users must strictly follow the approved schedule.</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">3. Facility Care</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Users must take care of SK property, furniture, and equipment.</li>
                        <li>Rearranged equipment must be returned to the original setup after use.</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">4. Cancellation Policy</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Multiple no-shows may lead to suspension of reservation privileges.</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">5. Safety and Security</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Users must follow all safety protocols of the SK and Barangay 22-C.</li>
                        <li>Any incident or accident must be reported immediately to the SK official on duty.</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">6. Agreement</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Non-compliance may result in cancellation and/or suspension of future use.</li>
                        <li>By confirming the reservation, the user agrees to follow all Terms and Conditions stated above.</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Agreement Form -->
        <form action="{{ route('resident.reservation.accept_terms') }}" method="POST" id="termsForm">
            @csrf
            <div class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" id="acceptTerms" name="accept_terms" class="mt-1 h-5 w-5 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500 focus:ring-2" required aria-describedby="terms-description">
                    <span class="ml-3 text-gray-700 font-medium">
                        I have read and agree to the Terms and Conditions.
                    </span>
                </label>
                <p id="terms-description" class="text-xs text-gray-500 mt-2 ml-8">You must accept the terms to proceed with your reservation.</p>
            </div>
            
            <div class="flex flex-col-reverse sm:flex-row justify-between sm:justify-end sm:space-x-4 space-y-4 space-y-reverse sm:space-y-0">
                <a href="{{ route('resident.dashboard') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200 font-medium text-center">
                    Cancel
                </a>
                <button type="submit" id="proceedBtn" class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Proceed
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('acceptTerms');
    const proceedBtn = document.getElementById('proceedBtn');
    const termsContent = document.getElementById('termsContent');
    const form = document.getElementById('termsForm');
    
    // Toggle button disabled state based on checkbox
    checkbox.addEventListener('change', function() {
        proceedBtn.disabled = !this.checked;
        if (this.checked) {
            proceedBtn.focus();
        }
    });
    
    // Add keyboard support
    checkbox.addEventListener('keydown', function(e) {
        // Space or Enter key
        if (e.keyCode === 32 || e.keyCode === 13) {
            e.preventDefault();
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        }
    });
    
    // Make sure the terms content is scrollable with keyboard
    termsContent.addEventListener('keydown', function(e) {
        // Down arrow
        if (e.keyCode === 40) {
            e.preventDefault();
            termsContent.scrollTop += 30;
        }
        // Up arrow
        else if (e.keyCode === 38) {
            e.preventDefault();
            termsContent.scrollTop -= 30;
        }
        // Page Down
        else if (e.keyCode === 34) {
            e.preventDefault();
            termsContent.scrollTop += termsContent.clientHeight;
        }
        // Page Up
        else if (e.keyCode === 33) {
            e.preventDefault();
            termsContent.scrollTop -= termsContent.clientHeight;
        }
    });
    
    // Prevent form submission if checkbox is not checked
    form.addEventListener('submit', function(e) {
        if (!checkbox.checked) {
            e.preventDefault();
            alert('Please read and accept the Terms and Conditions to proceed.');
            checkbox.focus();
        }
    });
});
</script>
@endsection
