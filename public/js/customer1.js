var currentTab = 0;
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
    document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline-block";

    // Change button text on the final step
    document.getElementById("nextBtn").innerHTML = n === (x.length - 1) ?
        '<i class="fas fa-check me-2"></i>Submit' :
        'Next<i class="fas fa-arrow-right ms-2"></i>';

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
        } else if (n === -1) {
            // Previous button logic
            if (!marriedSelected && currentTab - 1 === spouseStepIndex) {
                currentTab -= 2; // Skip Spouse step when going backward
            } else {
                currentTab += n; // Default Previous
            }
        }
    }
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
    markPreviousStepsAsFinish(); // Mark previous steps as finished
}

function validateForm() {
    var x = document.getElementsByClassName("step");
    var y = x[currentTab].getElementsByTagName("input"); // Target input elements
    var selects = x[currentTab].getElementsByTagName("select"); // Target select elements
    var textareas = x[currentTab].getElementsByTagName("textarea"); // Target textarea elements
    var valid = true;

    // Validate each input in the current tab
    for (var i = 0; i < y.length; i++) {
        // Clear previous validation state
        y[i].classList.remove("is-invalid");

        // Check if it's a file input and required
        if (y[i].type === "file" && !y[i].files.length && y[i].hasAttribute('required')) {
            y[i].classList.add("is-invalid");
            valid = false;
        }
        // For radio buttons, check if any in the group is selected
        else if (y[i].type === "radio" && y[i].name) {
            var radioGroup = document.getElementsByName(y[i].name);
            var radioChecked = false;

            for (var j = 0; j < radioGroup.length; j++) {
                if (radioGroup[j].checked) {
                    radioChecked = true;
                    break;
                }
            }

            if (!radioChecked && y[i].hasAttribute('required')) {
                for (var j = 0; j < radioGroup.length; j++) {
                    radioGroup[j].classList.add("is-invalid");
                }
                valid = false;
            }
        }
        // For non-file, non-radio inputs, check if they are empty
        else if (y[i].type !== "file" && y[i].type !== "radio" && y[i].value.trim() === "" && y[i].hasAttribute('required')) {
            y[i].classList.add("is-invalid");
            valid = false;
        }
    }

    // Validate select elements
    for (var i = 0; i < selects.length; i++) {
        selects[i].classList.remove("is-invalid");
        if (selects[i].value === "" && selects[i].hasAttribute('required')) {
            selects[i].classList.add("is-invalid");
            valid = false;
        }
    }

    // Validate textarea elements
    for (var i = 0; i < textareas.length; i++) {
        textareas[i].classList.remove("is-invalid");
        if (textareas[i].value.trim() === "" && textareas[i].hasAttribute('required')) {
            textareas[i].classList.add("is-invalid");
            valid = false;
        }
    }

    return valid;
}

function fixStepIndicator(n) {
    var stepItems = document.querySelectorAll(".step-item");

    // Reset all steps
    stepItems.forEach(function(item) {
        item.classList.remove("active", "completed");
    });

    // Set active step
    stepItems[n].classList.add("active");
}

function markPreviousStepsAsFinish() {
    var stepItems = document.querySelectorAll(".step-item");

    // Mark completed steps
    for (var i = 0; i < currentTab; i++) {
        stepItems[i].classList.add("completed");
    }
}

document.getElementById('copyAddressButton').addEventListener('click', function() {
    // Copy Permanent Address to Mailing Address
    let permanentAddress = document.getElementById('permanent_address').value;
    document.getElementById('living_address').value = permanentAddress;

    // Copy Permanent City to Living City
    let permanentCity = document.getElementById('permanent_city').value;
    document.getElementById('living_city').value = permanentCity;
});

// NIC validation
document.addEventListener('DOMContentLoaded', function() {
    const regex = /^\d{0,15}[a-zA-Z]?$/;

    function validateInput(event) {
        const input = event.target;
        const value = input.value;

        if (!regex.test(value)) {
            input.value = value.slice(0, -1); // Remove last invalid character
        }
    }

    document.getElementById("spouse_nic").addEventListener("input", validateInput);
    document.getElementById("nic").addEventListener("input", validateInput);

    // Initialize date picker
    flatpickr('.datepicker', {
        dateFormat: 'Y-m-d',
        maxDate: 'today',
        disableMobile: true
    });
});


function toggleSpouseDetails() {

}
