# Bankroll Tracker Project README

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

## Installation and Setup
1. **Clone the Repository**:
   - If using Git, clone the project to your local machine:
     ```bash
     git clone <repository-url> /home/afonso/eu
     ```
   - Alternatively, download the files manually and place them in `/home/afonso/eu/`.

2. **Set Permissions**:
   - Ensure the directory is writable by the web server user (e.g., `www-data`):
     ```bash
     sudo chmod -R 775 /home/afonso/eu
     sudo chown -R www-data:www-data /home/afonso/eu
     ```

3. **Configure the Web Server**:
   - Point your web server to the `/home/afonso/eu/` directory.
   - For Apache, edit the configuration (e.g., `/etc/apache2/sites-available/000-default.conf`) to include:
     ```
     DocumentRoot /home/afonso/eu
     <Directory /home/afonso/eu>
         AllowOverride All
         Require all granted
     </Directory>
     ```
   - Restart the web server:
     ```bash
     sudo service apache2 restart
     ```

4. **Access the Application**:
   - Open a browser and navigate to `http://127.0.0.1/index.php` or your server's IP address.

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
- **Branch Problems**: If Git branches (`main` vs. `master`) cause issues, see the "Version Control" section.

## Version Control
- The project may have `main` and `master` branches with different histories.
- To align them, switch to `master` locally (`git checkout master`) and set it as the default branch on GitHub (Settings > Branches).
- If needed, overwrite `main` with `master`:
  ```bash
  git push origin master:main --force
  ```
  (Backup first: `cp -r /home/afonso/eu /home/afonso/eu-backup`).

## Contributing
- Fork the repository and create a new branch for your changes.
- Submit a pull request with a clear description of your updates.
- Ensure code follows the existing structure and passes testing.

## License
This project is open-source. Feel free to modify and distribute, but please include this README and credit the original author.

## Contact
For questions or support, contact the project maintainer at [your-email@example.com] (replace with your email).

---
*Last Updated: June 18, 2025, 08:17 PM WEST*