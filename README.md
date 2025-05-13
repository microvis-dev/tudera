# Tudera CRM

**Tudera CRM** is a powerful and efficient Customer Relationship Management (CRM) system designed to streamline business processes, enhance customer interactions, and drive productivity. Built with a robust backend in **PHP** and a dynamic frontend using **Vue**, Tudera CRM is tailored for modern business needs.

## Key Features

* **Customer Management**: Organize and manage customer data efficiently.
* **Sales Tracking**: Monitor and analyze sales performance with ease.
* **Task Automation**: Automate repetitive tasks to save time and improve accuracy.
* **Customizable Workflows**: Tailor workflows to match your business processes.
* **Real-time Analytics**: Gain insights into your business performance with detailed reports.
* **Secure Architecture**: Your data is safe with our robust security features.

## Technology Stack

* **Backend**: PHP
* **Frontend**: Vue.js
* **Database**: MySQL (via Docker)
* **Containerization**: Docker, Laravel Sail

## Prerequisites

* **Docker** and **Docker Compose** installed
* **Git** installed
* **Composer** installed
* **Node.js** and **npm** installed

## Setup & Installation & Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/microvis-dev/tudera.git
   cd tudera
   ```

2. **Copy environment file**

   ```bash
   cp .env.example .env
   ```

3. **Install PHP dependencies**

   ```bash
   composer install
   ```

4. **Install Node dependencies**

   ```bash
   npm install
   ```

5. **Start Docker containers**

   ```bash
   ./vendor/bin/sail up -d --build
   ```

   > **Note:** `--build` is only required on the first run.

6. **Run database migrations and seeders**

   ```bash
   ./vendor/bin/sail artisan migrate
   ./vendor/bin/sail artisan db:seed
   ```

7. **Run tests**

   ```bash
   ./vendor/bin/sail npm run test
   ```

8. **Access the application**
   Open your browser and navigate to:

   ```
   http://localhost:8000
   ```

## Additional Sail Commands

* **Stop containers**

  ```bash
  ./vendor/bin/sail down
  ```

* **Restart containers**

  ```bash
  ./vendor/bin/sail restart
  ```

* **Rebuild containers**

  ```bash
  ./vendor/bin/sail build
  ```

* **View running containers**

  ```bash
  docker ps
  ```

* **Access Adminer**
  Open your browser and navigate to:

  ```
  http://localhost:8080
  ```
