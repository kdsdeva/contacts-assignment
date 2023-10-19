# Contacts Listing Dashboard

A simple contact listing dashboard built with Symfony 4.4. This dashboard allows you to create, view, update, and delete contacts.

## Requirements

Before you get started, ensure you have the following software installed on your system:

- PHP 7.1.3 or higher
- Symfony 4.4
- Composer (PHP package manager)

## Installation

Clone the repository to your local machine:

   git clone https://github.com/kdsdeva/contacts-assignment.git
   
## Install project dependencies using Composer:

    composer install
    
## Install yarn:

    yarn install

## Create the database schema and Configure .env:

Configure your Symfony environment. You'll need to set up your database connection in .env and .env.test file.

    php bin/console doctrine:schema:update --force

Configure your MAILER_DSN in .env for sending emails.

## Unit Testing

This project includes unit tests to ensure its reliability and maintainability. You can run the unit tests using the following command:
    
    php bin/phpunit

## Usage

- Register an account and Login, you will get redirected to the Contacts list Dashboard
- To create a new contact, use the "Create new contact" feature.
- To update an existing contact, use the "Update" feature.
- To delete a contact, use the "Delete" feature.
