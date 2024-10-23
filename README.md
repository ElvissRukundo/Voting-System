<h1>weDecide ONLINE VOTING SYSTEM</h1>

weDecide is a simple web-based voting system designed to allow users to cast their votes, view candidates, and see election results in real-time. The system also includes an admin panel for managing candidates, political parties, and overseeing the voting process (admin panel under development).

<h2>FEATURES</h2>

<h3>USER SIDE</h3>

User Registration: Users can register to participate in the voting process.
Login & Profile Management: Users can log in, view and edit their profiles
Candidates: View a list of candidates and political parties participating in the election.
Vote Casting: Submit votes for candidates, voting can only be done once per user.
Rankings: View current rankings and vote results, rankings update every 60 seconds

<h3>ADMIN PANEL</h3> (In Development)
The admin panel will allow administrators to:

Manage user accounts (edit but not delete or add since registratio will be done voluntarily by the voter).
Add and manage political parties and candidates.
Oversee the voting process and finalize results.

<h2>TECHNOLOGIES USED</h2>

**HTML**: For front-end layout and structure.

**CSS**: For styling and layout design.

**JavaScript**: For form validation and dynamic content updates.

**PHP**: For server-side scripting and database interaction.

**MySQL**: As the database to store users, votes, candidates, etc.

<h2>HOW TO USE</h2>

**Clone the repository:**

git clone https://github.com/ElvissRukundo/voting-system.git

**Import the database**:

Import the weDecideDB.sql file located in the db/ directory into your MySQL database.
Update the database credentials in db.php files in both the admin/ and include/ directories.
Run the app on a local server (e.g., XAMPP or WAMP) or upload it to your web hosting platform.
Access the user interface through index.php and the admin panel through admin/dashboard.php.

<h2>FUTURE IMPROVEMENTS</h2>
Completion of the admin panel.
Improved security measures, such as password hashing and CAPTCHA.
Enhanced styling and responsiveness for mobile devices.

<h2>LICENSE</h2>
This project is licensed under the MIT License.
