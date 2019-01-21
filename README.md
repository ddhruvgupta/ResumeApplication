# ResumeApplication
PHP-JQuery Application for managing a resume database

# Requirement Collection

index.php Will present a list of all profiles in the system with a link to a detailed view with view.php whether or not you are logged in. If you are not logged in, you will be given a link to login.php. If you are logged in you will see a link to add.php add a new resume and links to delete or edit any resumes that are owned by the logged in user. will need to have a section where the user can press a "+" button to add up to nine empty position entries. Each position entry includes a year (integer) and a description. Implement auto-complete functionality for name of educational institution.

create a Position table and connect it to the Profile table with a many-to-one relationship

rank column should be used to record the order in which the positions are to be displayed

login.php will present the user the login screen with an email address and password to get the user to log in. If there is an error, redirect the user back to the login page with a message. If the login is successful, redirect the user back to index.php after setting up the session. In this assignment, you will need to store the user's hashed password in the users table as described below.

logout.php will log the user out by clearing data in the session and redirecting back to index.php.

view.php Will show all of the positions in an un-numbered list.

edit.php Will support the addition of new position entries, the deletion of any or all of the existing entries, and the modification of any of the existing entries. After the "Save" is done, the data in the database should match whatever positions were on the screen and in the same order as the positions on the screen.

If the user goes to an add, edit, or delete script without being logged in, die with a message of "ACCESS DENIED".

### Screenshots
<img src="https://raw.githubusercontent.com/ddhruvgupta/ResumeApplication/master/screenshots/1.png" width="1000" />
