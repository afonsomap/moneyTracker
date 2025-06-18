document.getElementById('bankrollForm').addEventListener('submit', function(e) {
    const moneyAdded = document.getElementById('money_added').value;
    const loss = document.getElementById('loss').value;
    const gain = document.getElementById('gain').value;
    const errorElement = document.createElement('div');
    errorElement.className = 'error';

    // Check if all amount fields are empty
    if (!moneyAdded && !loss && !gain) {
        e.preventDefault();
        errorElement.textContent = 'Please enter a money added, loss, or gain amount.';
        this.appendChild(errorElement);
        setTimeout(() => errorElement.remove(), 3000);
    } else {
        // Add loading state to button
        const button = this.querySelector('.submit-btn');
        button.textContent = 'Updating...';
        button.disabled = true;
        setTimeout(() => {
            button.textContent = 'Update Bankroll';
            button.disabled = false;
        }, 1000);
    }
});

// Handle delete button clicks
document.addEventListener('click', function(e) {
    if (e.target.closest('.delete-btn')) {
        e.preventDefault();
        const form = e.target.closest('.delete-form');
        const confirmDelete = confirm('Are you sure you want to delete this transaction?');
        if (confirmDelete) {
            const button = e.target.closest('.delete-btn');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            form.submit();
        }
    }
});