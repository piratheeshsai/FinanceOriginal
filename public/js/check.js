

document.addEventListener('DOMContentLoaded', function () {
    // Initialize Choices.js for dropdowns
    const loanTypeField = new Choices('#loan_type', { searchEnabled: false });
    let groupField = new Choices('#group_id', { shouldSort: false, searchEnabled: true });
    let customerField = initializeCustomerField();

    const customerFieldContainer = document.getElementById('customerFieldContainer');

    // Handle loan type change
    loanTypeField.passedElement.element.addEventListener('change', function () {
        const loanType = loanTypeField.getValue(true);

        if (loanType === 'individual') {
            switchToIndividualLoanType();
        } else if (loanType === 'group') {
            switchToGroupLoanType();
        } else {
            resetFields();
        }
    });

    // Handle group selection change
    groupField.passedElement.element.addEventListener('change', function () {
        const groupId = groupField.getValue(true);
        if (groupId) {
            fetchCustomersForGroup(groupId);
        } else {
            reinitializeCustomerField([]);
        }
    });

    function switchToIndividualLoanType() {
        groupField.disable();
        clearSelectedCustomer(); // Clear the selected customer
        customerFieldContainer.style.display = 'block';
        fetchCustomersForIndividual();
    }

    function switchToGroupLoanType() {
        groupField.enable();
        clearSelectedCustomer(); // Clear the selected customer
        customerFieldContainer.style.display = 'block';
    }

    function resetFields() {
        groupField.disable();
        clearSelectedCustomer(); // Clear the selected customer
        reinitializeCustomerField([]);
        customerFieldContainer.style.display = 'none';
    }

    async function fetchCustomersForIndividual() {
        try {
            const response = await fetch('/api/customers?group_id=null');
            const data = await response.json();
            reinitializeCustomerField(data, 'No customers available');
        } catch (error) {
            console.error('Error fetching individual customers:', error);
        }
    }

    async function fetchCustomersForGroup(groupId) {
        try {
            const response = await fetch(`/api/customers?group_id=${groupId}`);
            const data = await response.json();
            reinitializeCustomerField(data, 'No customers found');
        } catch (error) {
            console.error('Error fetching group customers:', error);
        }
    }

    function reinitializeCustomerField(data, emptyMessage = 'No customers available') {
        // Destroy the current Choices.js instance
        if (customerField) {
            customerField.destroy();
        }

        // Update the options in the original <select> element
        const selectElement = document.querySelector('#customer_id');
        selectElement.innerHTML = ''; // Clear existing options

        if (data && data.length > 0) {
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                const displayParts = [];
                if (item.full_name) displayParts.push(item.full_name);
                if (item.nic) displayParts.push(`(${item.nic})`); // Nic in parentheses

                option.textContent = displayParts.join(' ')
                    || item.group_code
                    || `Item ${item.id}`;
                selectElement.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = emptyMessage;
            option.disabled = true;
            selectElement.appendChild(option);
        }

        // Reinitialize Choices.js for the updated select element
        customerField = initializeCustomerField();
    }

    function initializeCustomerField() {
        return new Choices('#customer_id', { shouldSort: false, removeItemButton: true,placeholder: true,
            placeholderValue: 'Select Customer' });
    }

    function clearSelectedCustomer() {
        if (customerField) {
            customerField.clearStore(); // Clear all choices
            customerField.clearInput(); // Clear input field
            customerField.removeActiveItems(); // Clear selected values
        }
    }
});

$(document).ready(function() {
    $('#loan_start').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        toggleActive: false
    });
});
