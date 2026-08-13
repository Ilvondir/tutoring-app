# Tutoring App

Tutoring App is a web application for managing private tutoring created using the Laravel framework. In addition to Laravel, Vue was also used. These frameworks were integrated using the Inertia framework that allows for building SPA applications despite following the MVC architecture. The interface of the application is in Polish. The application is created on a SQLite database by default and can also run on MySQL.

The application allows logging in with two user roles: teacher and student. Roles and access control are handled with the Spatie Laravel Permission package together with Laravel policies.

Users with the teacher role can create homework assignments for selected students, edit them, and soft-delete them. A homework consists of a title, a description, and a set of exercises. Each exercise has an assignment text and an expected answer. Teachers can add, edit, and delete exercises, as well as change their order. They also have access to a panel that allows them to create, edit, and delete user accounts with the teacher or student role. Additionally, teachers can browse recorded learning sessions and remove them.

Users with the student role can open homework assigned to them and submit answers to exercises. The application compares the submitted answer with the expected one, ignoring letter case and surrounding whitespace. Every attempt is stored in the database, including incorrect ones, so that teachers can later review the history of answers. A correctly solved exercise is marked as completed. When every exercise in a homework is completed, the homework itself is marked as finished. Correct answers are celebrated with confetti and a sound effect, and completing an entire homework triggers a fireworks animation with applause.

Students can also measure their learning time with a timer available in the navigation bar. Starting the timer counts elapsed seconds in the browser. Stopping it saves a learning session on the server. The dashboard presents statistics for the logged-in user: teachers see the number of created homeworks and exercises, while students see finished homeworks, finished exercises, and the total recorded learning time.

Feedback after user actions is displayed with Vue Toastification. Lists of homeworks, users, and learning sessions are presented in searchable, sortable, and paginated tables. Authentication, profile management, password reset, two-factor authentication, and account deletion are provided by Laravel Jetstream and Laravel Fortify.

## Used Tools

### Backend
- PHP 8.4
- Laravel 12.28.0
- Inertia.js 2.0.6
- Laravel Jetstream 5.3.8
- Laravel Fortify 1.30.0
- Spatie Laravel Permission 6.21.0
- Sail 1.45.0
- IDE Helper 3.6.0

### Frontend
- Vue 3 3.5.21
- Inertia.js 2.1.4
- Tailwind CSS 3.4.17
- Boxicons 2.1.4
- Vue Toastification 2.0.0
- Vue3 Table Lite 1.4.3
- Vue Select 4.0.0
- Vue Confetti Explosion 1.0.2
- Fireworks.js 2.10.8
- Lodash 4.17.21

## Requirements

For running the application you need:

- [PHP](https://www.php.net/manual/en/install.windows.php)
- [composer](https://getcomposer.org)
- [Node.js](https://nodejs.org)

Or only:

- [Docker](https://www.docker.com)

## How to run

1. Clone this repository.
2. Copy `.env.example` to `.env`.
3. Run `composer install` and `npm install`.
4. Run `php artisan key:generate`.
5. Run `php artisan migrate --seed`.
6. Run `composer run dev`.
7. Log in to the selected account to discover various functionalities.

| Account  | Email                 | Password |
|:--------:|:---------------------:|:--------:|
| Teacher  | test@example.com      | password |
| Student  | student@example.com   | password |

You can also run this app on Docker containers using Laravel Sail.

## First Look

![firstlook](public/firstlook/firstlook.png?raw=true)
