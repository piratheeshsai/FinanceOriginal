

document.addEventListener('DOMContentLoaded', function() {
    // Ensure Livewire is available
    if (window.Livewire) {
        Livewire.on('show-success-alert', (data) => {
            console.log('Success alert triggered:', data);
            showSuccessAlert(data);
        });
        Livewire.on('show-error-alert', (data) => {
            showErrorAlert(data);
        });
    }
});



function showSuccessAlert(data) {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: data.message || 'Success',
        timer: 3000, // Auto-close after 3 seconds
        showConfirmButton: false,
    });
}

function showErrorAlert(data) {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: data.message || 'Error occurred',
        timer: 3000, // Auto-close after 3 seconds
        showConfirmButton: false,
    });
}



function confirmAction(options) {
    return Swal.fire({
        title: options.title || 'Are you sure?',
        text: options.text || "You won't be able to revert this!",
        icon: options.icon || 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: options.confirmButtonText || 'Yes, proceed!',
        cancelButtonText: options.cancelButtonText || 'Cancel',
    });
}


