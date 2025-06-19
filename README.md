# Bankroll Tracker Project 

## Overview
The Bankroll Tracker is a web-based application designed to help users manage their financial bankroll, particularly for tracking gains, losses, and money added over time. Built using PHP, SQLite, HTML, CSS, and JavaScript, this project provides a simple interface to log transactions, view history, and maintain an accurate bankroll balance. The application features a modern design with responsive layouts, real-time updates, and the ability to delete transactions. This README was last updated on June 18, 2025, at 08:17 PM WEST.

## Features
- **Transaction Logging**: Add money, record losses, and gains with optional descriptions.
- **Bankroll Management**: Automatically updates the bankroll based on transactions.
- **Transaction History**: Displays a table of all transactions with timestamps and new bankroll values.
- **Delete Functionality**: Remove transactions using a garbage can icon, with bankroll recalculation.
- **Visual Feedback**: Bankroll turns red when negative and green when positive or zero.
- **Form Validation**: Ensures at least one amount field (money added, loss, or gain) is filled.
- **Loading States**: Shows feedback during form submission and transaction deletion.
- **Responsive Design**: Adapts to desktop and mobile screens with larger, modern styling.

## Prerequisites
- Web server with PHP support (e.g., Apache or Nginx).
- SQLite3 PHP extension enabled.
- A modern web browser (Chrome, Firefox, Edge, etc.).
- Basic command-line access for setup (optional for Git usage).

## Usage
1. **Add a Transaction**:
   - Enter values for "Money Added," "Loss," or "Gain" (at least one is required).
   - Optionally, add a "Description.".
   - Click "Update Bankroll" to log the transaction and update the bankroll.

2. **View Transaction History**:
   - The table below the form displays all transactions with dates, amounts, descriptions, and new bankroll values.

3. **Delete a Transaction**:
   - Click the garbage can icon in the "Action" column of the transaction history.
   - Confirm the deletion prompt; the bankroll will be recalculated based on remaining transactions.

4. **Check Bankroll Status**:
   - The "Current Bankroll" header shows the balance, turning red if negative and green if zero or positive.

## File Structure
- `index.php`: Main PHP file handling database operations, form submission, and HTML rendering.
- `styles.css`: CSS file for modern, responsive styling with larger fonts and animations.
- `script.js`: JavaScript file for form validation and delete button interactivity.
- `bankroll.db`: SQLite database file storing bankroll and transaction data (created automatically).

## Development Notes
- The application uses prepared statements to prevent SQL injection.
- Bankroll recalculation occurs when deleting transactions to ensure accuracy.
- The design is optimized for readability with a gradient background and shadow effects.
- Font Awesome is used for the delete icon via CDN; consider hosting locally for offline use.

## Troubleshooting
- **500 Internal Server Error**: Check PHP error logs (e.g., `/var/log/apache2/error.log`) for syntax errors in `index.php`.
- **Database Issues**: Ensure `bankroll.db` is writable and the SQLite3 extension is enabled in PHP.
- **Styling Issues**: Verify `styles.css` and `script.js` are loaded correctly in `index.php`.

## Running

    sqlite3 bankroll.db
    php -S localhost:9000

- **Localhost link**: 

## License
This project is open-source. Feel free to modify and distribute, but please include this README and credit the original author.

## Contact
For questions or support, contact the project maintainer at [afonsomiguelap@gmail.com].

---
*Last Updated: June 18, 2025, 08:17 PM WEST*
