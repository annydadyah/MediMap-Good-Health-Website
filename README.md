# MediMap - A Health Facility Map Application for SDG 3: Good Health and Well-being

## Overview

**MediMap** is a web-based platform developed to help improve access to quality healthcare services by providing a transparent, user-driven review and rating system for hospitals and healthcare facilities. The website is designed to support **Sustainable Development Goal (SDG) 3: Good Health and Well-being**, which aims to ensure healthy lives and promote well-being for all.

The website integrates an interactive map using **Leaflet API** to allow users to easily find healthcare facilities near them. The platform also includes features for users to rate and review healthcare facilities, ensuring that everyone has access to reliable, transparent information when choosing a hospital or clinic.

---

## Features

- **Interactive Map:** Powered by Leaflet API, users can find the nearest healthcare facilities and navigate the map to explore them.
- **User Reviews and Ratings:** Patients can submit reviews and ratings based on their experiences, contributing to the overall transparency and quality of healthcare services.
- **Admin Features:** Admins can manage the list of hospitals by adding, editing, or removing data, ensuring that the information is accurate and up-to-date.
- **Search Functionality:** Users can search for hospitals based on their location or specific needs (e.g., hospital type, rating, etc.).
- **Radius Search:** Users can filter hospitals within a specific radius of their current location.

---

## Technologies Used

- **Frontend:**
  - **Tailwind CSS:** A utility-first CSS framework used to design the website’s responsive layout.
  - **Leaflet API:** A JavaScript library used for creating the interactive map feature.
  
- **Backend:**
  - **Laravel 11:** A PHP framework that powers the backend, handling user authentication, data management, and API integration.
  - **MySQL:** A relational database system used to store data such as hospital details, user reviews, and ratings.

---

## Installation

### Prerequisites

- PHP (Laravel requires PHP 8.x or higher)
- Composer (Dependency manager for PHP)
- MySQL
- Node.js (for frontend development)

### Steps to Run Locally

1. Clone the repository:
   ```
   git clone https://github.com/annydadyah/MediMap-Good-Health-Website.git
   cd MediMap-Good-Health-Website
    ```
2. Install PHP dependencies:
    ```
    composer install
    ```
3. Set up the environment file:
    - Copy .env.example to .env
    - Configure your database and environment settings.
4. Generate the application key:
    ```
    php artisan key:generate
    ```
5. Run migrations to set up the database:
    ```
   php artisan migrate
    ```
7. Serve the application locally:
    ```
    php artisan serve
    ```
8. Visit http://localhost:8000 in your browser.

---

## Deployment
The application is hosted on [infinity free](https://dash.infinityfree.com/) and can be accessed at [MediMap Website](http://uaspwl.great-site.net). The full source code is available on GitHub.

---

## Our Team

MediMap was developed with a spirit of collaboration and dedication. Our team consists of students from the [PLN Institute of Technology (Institut Teknologi PLN)](https://itpln.ac.id), who worked together to bring this project to life. The team members who contributed to this project are:
1. Annyda Dyah Kusuma – 202231029
2. T. Rajas Suhaba Arwana – 202231035
3. Ajeng Puspitaloka – 202231043
4. Renata Yasmine Selomita – 202231059

