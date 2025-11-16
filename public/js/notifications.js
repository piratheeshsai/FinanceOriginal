// Include Pusher and Laravel Echo via CDN
const Echo = new window.Echo({
    broadcaster: 'pusher',
    key: 'your-pusher-app-key',
    cluster: 'your-cluster',
    forceTLS: true,
});

// Listen for notifications on the 'notifications' channel
Echo.channel('notifications')
    .listen('NewLoanNotification', (data) => {
        console.log('Notification received:', data);

        // Update the notification bell
        const notificationCount = document.querySelector('#notification-count');
        notificationCount.innerText = parseInt(notificationCount.innerText || 0) + 1;
        notificationCount.classList.add('bg-danger');

        // Add the new loan to the dropdown
        const dropdown = document.querySelector('#notification-dropdown');
        const newItem = document.createElement('li');
        newItem.textContent = `Loan ID: ${data.loan.id} - Amount: ${data.loan.amount}`;
        dropdown.appendChild(newItem);
    });
