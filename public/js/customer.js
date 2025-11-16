
var currentTab = 0; // Current tab is set to be the first tab (0)
var spouseStepIndex = 1;
var familyStepIndex = 2;
var guarantorSelected = false; // Guarantor checkbox state
var customerSelected = false; // Customer checkbox state
var marriedSelected = false; // Married checkbox state

// Show the current tab
showTab(currentTab);

function showTab(n) {
    var x = document.getElementsByClassName("step");
    x[n].style.display = "block";

    // Adjust visibility of the Prev button
    document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline";

    // Change button text on the final step
    document.getElementById("nextBtn").innerHTML = n === (x.length - 1) ? "Submit" : "Next";

    fixStepIndicator(n); // Update step indicators
}

function nextPrev(n) {
    var x = document.getElementsByClassName("step");

    // Get the checkbox states dynamically
    var marriedCheckbox = document.getElementById("marriedCheckbox");
    var guarantorCheckbox = document.getElementById("guarantor");
    var customerCheckbox = document.getElementById("customer");

    // Update states dynamically
    var guarantorSelected = guarantorCheckbox.checked;
    var customerSelected = customerCheckbox.checked;
    var marriedSelected = marriedCheckbox.checked;

    // Exit the function if the current step is invalid
    if (n === 1 && !validateForm()) return false;

    // Hide the current step
    x[currentTab].style.display = "none";


    if (customerSelected && guarantorSelected) {
        if (n === 1) {
            // Handle Next button
            if (!marriedSelected && currentTab + n === spouseStepIndex) {
                currentTab += 2; // Skip Spouse step when not married
            } else {
                currentTab += n; // Default behavior
            }
        }else if (n === -1) {
                // Previous button logic
                if (!marriedSelected && currentTab - 1 === spouseStepIndex) {
                    currentTab -= 2; // Skip Spouse step when going backward
                } else {
                    currentTab += n; // Default Previous
                }}}
    // If only Guarantor is selected, skip Spouse and Family Details steps
    else if (guarantorSelected) {
        if (n === 1) {
            if (currentTab + n === spouseStepIndex || currentTab + n === familyStepIndex) {
                currentTab += 3; // Skip Spouse and Family Details step when moving forward
            } else {
                currentTab += n; // Default behavior for other steps
            }
        } else if (n === -1) {
            if (currentTab - 1 === spouseStepIndex || currentTab - 1 === familyStepIndex) {
                currentTab -= 3; // Skip Spouse and Family Details step when moving backward
            } else {
                currentTab += n; // Default behavior for other steps
            }
        }
    }
    // Default behavior when neither Customer nor Guarantor is selected
    else {
        if (n === 1 && !marriedSelected && currentTab + n === spouseStepIndex) {
            currentTab += 2; // Skip Spouse Details if Married is not selected
        } else if (n === -1 && !marriedSelected && currentTab - 1 === spouseStepIndex) {
            currentTab -= 2; // Skip Spouse Details if Married is not selected
        } else {
            currentTab += n; // Default behavior for other steps
        }
    }

    // Prevent going out of bounds (checking if the currentTab is beyond the number of steps)
    if (currentTab >= x.length) {
        document.getElementById("signUpForm").submit();
        return false;
    }



    // Show the correct tab
    showTab(currentTab);
    markPreviousStepsAsFinish(); // Optional: Mark previous steps as finished
}



function validateForm() {
    var x = document.getElementsByClassName("step");
    var y = x[currentTab].getElementsByTagName("input"); // Only target input elements
    var valid = true;

    // Validate each input in the current tab
    for (var i = 0; i < y.length; i++) {
        // Check if it's a file input and required
        if (y[i].type === "file" && !y[i].files.length && y[i].hasAttribute('required')) {
            y[i].classList.add("invalid");
            valid = false;
        }
        // For non-file inputs, check if they are empty
        else if (y[i].type !== "file" && y[i].value.trim() === "" && y[i].hasAttribute('required')) {
            y[i].classList.add("invalid");
            valid = false;
        } else {
            y[i].classList.remove("invalid");
        }
    }

    return valid;
}






function fixStepIndicator(n) {
    var x = document.getElementsByClassName("stepIndicator");

    // Remove "active" and "finish" classes
    for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("active", "finish");
    }

    // Add "active" to the current step
    x[n].classList.add("active");
}

function markPreviousStepsAsFinish() {
    var x = document.getElementsByClassName("stepIndicator");

    // Mark all previous steps as "finish"
    for (var i = 0; i < currentTab; i++) {
        x[i].classList.add("finish");
    }
}

document.getElementById('copyAddressButton').addEventListener('click', function() {
    // Copy Permanent Address to Mailing Address
    let permanentAddress = document.getElementById('permanent_address').value;
    document.getElementById('living_address').value = permanentAddress;

    // Copy Permanent City to Living City
    let permanentCity = document.getElementById('permanent_city').value;
    document.getElementById('living_city').value = permanentCity;
})



$(document).ready(function() {
    const permanentCitySelect = new Choices('#permanent_city', {
        placeholderValue: 'Select Permanent city', // Placeholder text
        searchEnabled: true, // Enable search functionality
        itemSelectText: '', // Hide "Press to select" text
        removeItemButton: true, // Allow removal of selected items
        noResultsText: 'No cities found', // Custom no results message
    });


});

$(document).ready(function() {
    const permanentCitySelect = new Choices('#living_city', {
        placeholderValue: 'Select mailing city', // Placeholder text
        searchEnabled: true, // Enable search functionality
        itemSelectText: '', // Hide "Press to select" text
        removeItemButton: true, // Allow removal of selected items
        noResultsText: 'No cities found', // Custom no results message
    });


});

$(document).ready(function() {
    const permanentCitySelect = new Choices('#centre_id', {
        placeholderValue: 'Select center', // Placeholder text
        searchEnabled: true, // Enable search functionality
        itemSelectText: '', // Hide "Press to select" text
        removeItemButton: true, // Allow removal of selected items
        noResultsText: 'No cities found', // Custom no results message
    });


});


$(document).ready(function() {
    const permanentCitySelect = new Choices('#gender', {
        placeholderValue: 'Select center', // Placeholder text
        searchEnabled: true, // Enable search functionality
        itemSelectText: '', // Hide "Press to select" text
        removeItemButton: true, // Allow removal of selected items
        noResultsText: 'No cities found', // Custom no results message
    });


});


$(document).ready(function() {
    $('#date_of_birth').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        toggleActive: false
    });
});







