# OULAD Database Management System
- The OULAD Database Management System is a web-based database application built to store, manage, and interact with data from the Open University Learning Analytics Dataset (OULAD). The purpose of the project is to help users to analyze student engagement and academic performance data through a simple front-end interface connected to a PostgreSQL database.

## Tech Stack
- HTML/CSS/JS for front end
- AJAX for client-server communications
- PHP for backend
- PostgreSQL + pgAdmin for database and GUI
- MAMP & Apache for local development environment

## How the Application Works
Each interactive webpage follows the same general process:

1. A user enters information into a form on the front end.
2. JavaScript collects the form data.
3. An AJAX POST request sends the data to a PHP back-end file.
4. The PHP code connects to the PostgreSQL database.
5. SQL is executed to insert, delete, update, or retrieve records.
6. A response message is returned to the front end.
7. The webpage displays the result to the user.
