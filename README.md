# Laravel SPK SMART

Laravel SPK SMART is a website designed to provide a decision support system using the SMART (Simple Multi Attribute Rating Tachnique) method. This site enables users to analyze various decision alternatives based on defined criteria, assisting in determining the best choice in a systematic and transparent way. With a user-friendly interface, users can easily input data and obtain in-depth analysis results to support more accurate decision-making.

## Tech Stack

- **Laravel 11**
- **Laravel Breeze**
- **MySQL Database**
- **TailwindCSS**
- **daisyUI**
- **[maatwebsite/excel](https://laravel-excel.com/)**
- **[barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)**

## Features

- Main features available in this application:
  - Implementation SMART method

## Installation

Follow the steps below to clone and run the project in your local environment:

1. Clone repository:

    ```bash
    git clone https://github.com/Akbarwp/Laravel-SPK-SMART.git
    ```

2. Install dependencies use Composer and NPM:

    ```bash
    composer install
    npm install
    ```

3. Copy file `.env.example` to `.env`:

    ```bash
    cp .env.example .env
    ```

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Setup database in the `.env` file:

    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel_smart
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6. Run migration database:

    ```bash
    php artisan migrate
    ```

7. Run seeder database:

    ```bash
    php artisan db:seed
    ```

8. Run website:

    ```bash
    npm run dev
    php artisan serve
    ```

## Screenshot

![4  Halaman Hasil Akhir]()
![3  Halaman Penilaian Alternatif]()
![2  Halaman Kriteria]()
![1  Halaman Dashboard]()


- ### **Dashboard**

<img src="https://github.com/user-attachments/assets/09930340-980c-4c15-954a-62f84c9b1491" alt="Halaman Dashboard" width="" />
<br><br>

- ### **Criteria page**

<img src="https://github.com/user-attachments/assets/d0dfd257-56c8-4c27-888e-501b231cc84e" alt="Halaman Kriteria" width="" />
<br><br>

- ### **Penilaian page**

<img src="https://github.com/user-attachments/assets/dab9e7fc-2575-498c-acb6-e2e51214b429" alt="Halaman Penilaian" width="" />
<br><br>

- ### **Result page**

<img src="https://github.com/user-attachments/assets/6f737a0e-c4ea-48e5-86ea-628e587074b3" alt="Halaman Hasil Akhir" width="" />
<br><br>
