# 🛒 GoFood - E-Commerce Grocery & Food Platform

[![Laravel Version](https://img.shields.io/badge/Laravel-8.75-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-%5E7.3%20%7C%20%5E8.0-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.1.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![OpenAI](https://img.shields.io/badge/OpenAI-GPT--3.5-412991?style=for-the-badge&logo=openai&logoColor=white)](https://openai.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**GoFood** is a full-featured, modern e-commerce web application designed for fresh grocery and food delivery retail. Built with **Laravel 8**, **Bootstrap 5**, and integrated with intelligent AI customer assistance (**OpenAI**), real-time messaging (**Pusher / WebSockets**), multi-channel authentication (Google, Facebook, Phone OTP), and **ZaloPay** payment gateway.

---

## 📑 Table of Contents

- [Key Features](#-key-features)
  - [Customer & Storefront Experience](#1-customer--storefront-experience)
  - [AI Chatbot & Real-Time Communication](#2-ai-chatbot--real-time-communication)
  - [Authentication & User Management](#3-authentication--user-management)
  - [Admin Management Dashboard](#4-admin-management-dashboard)
- [Tech Stack & Architecture](#-tech-stack--architecture)
- [Database Schema](#-database-schema)
- [Third-Party Integrations](#-third-party-integrations)
- [Getting Started & Installation](#-getting-started--installation)
  - [Prerequisites](#prerequisites)
  - [Installation Steps](#step-by-step-installation)
  - [Environment Configuration](#environment-configuration)
  - [Default Credentials](#default-credentials)
- [Project Directory Structure](#-project-directory-structure)
- [Route Overview](#-route-overview)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🌟 Key Features

### 1. Customer & Storefront Experience
- **Interactive Homepage & Hero Showcase**: Dynamic banner sliders, category navigation, promotional campaigns, and trending products.
- **Product Catalog & Filtering**:
  - Organized by **Categories** and **Product Groups**.
  - Dynamic price display (regular vs. discounted pricing, savings badges).
  - Search engine supporting product keyword matching.
- **Product Details**: Rich media view, descriptions, stock availability, and related product recommendations.
- **Shopping Cart & Checkout**:
  - Database-backed Cart supporting both authenticated users and guest sessions (`session_id`).
  - AJAX cart quantity increment/decrement/removal.
  - Multi-method Checkout: **Cash on Delivery (COD)** and **ZaloPay** digital wallet payment gateway.
- **News & Blog System**: Informative food, recipes, and company blogs with full article views.
- **Contact & Support Desk**: Customer feedback and inquiry submission with automated email notifications.

### 2. AI Chatbot & Real-Time Communication
- **Smart AI Shopping Assistant (`/chatbot`)**:
  - Powered by **OpenAI GPT-3.5 Turbo**.
  - Integrated with live database context: queries real-time stock counts, categories, product pricing, and smart product discovery.
  - Falls back to OpenAI natural conversational intelligence for open-ended questions.
- **Real-Time Live Chat (`/chat`)**:
  - Real-time peer-to-peer messaging between users and support/admins.
  - Powered by **Pusher** / **Laravel WebSockets** with event broadcasting (`MessageSent`).

### 3. Authentication & User Management
- **Traditional Auth**: Registration, Login, Password Reset with email verification.
- **OAuth Social Login**: Single-sign-on using **Google OAuth** and **Facebook OAuth** (via Laravel Socialite).
- **Phone / OTP Login**: Passwordless phone number verification with 6-digit OTP codes via Email/SMS.
- **Role-Based Access Control (RBAC)**: Distinct permissions for `Admin` and `User` roles with route middleware protection (`admin`).

### 4. Admin Management Dashboard
- **Analytics & Reporting**:
  - Real-time summary cards: Total Orders, Products, Categories, Groups, and Blogs.
  - 7-Day interactive sales/orders chart.
  - Top 10 Best-Selling Products table with aggregate sales counts.
- **Product Management**: Full CRUD, image uploads, category/group association, stock tracking, and pricing.
- **Category & Group Management**: Category hierarchy with active/inactive status toggle.
- **Order Management**: Order listing, detail breakdown, total calculations, and status progression (`Chờ xác nhận`, `Đã xác nhận`, `Hủy`).
- **Blog & Content Management**: Create, edit, toggle visibility, and delete blog posts.

---

## 🛠 Tech Stack & Architecture

| Layer | Technologies Used |
| :--- | :--- |
| **Backend Framework** | [Laravel 8.75](https://laravel.com) (PHP 7.3+ / PHP 8.x) |
| **Database & ORM** | MySQL / MariaDB, Eloquent ORM |
| **Frontend UI** | Blade Templating Engine, [Bootstrap 5.1.3](https://getbootstrap.com/), Sass, jQuery |
| **Asset Bundling** | Laravel Mix 6, Webpack, Vue.js 2 (Template compiler support) |
| **Real-time & Broadcasting** | Pusher PHP Server, BeyondCode Laravel WebSockets |
| **AI Engine** | OpenAI PHP Client (`openai-php/client` - GPT-3.5 Turbo) |
| **Payment Gateway** | ZaloPay Merchant API v2 |
| **Telephony / SMS** | Twilio PHP SDK |
| **Social Authentication** | Laravel Socialite (Google, Facebook) |

---

## 🗄 Database Schema

The core database consists of the following key tables:

- `users`: User profiles, credentials, social IDs, and phone numbers.
- `role_master`: Definition of system roles (`Admin`, `User`, etc.).
- `user_roles_mapping`: Many-to-many relationship mapping users to roles.
- `category`: Product categories with status flags.
- `group`: Product sub-groups / collections.
- `product`: Product records (name, old price, new price, discount info, stock quantity, images, category/group IDs).
- `carts` & `cart_items`: User and session shopping cart storage.
- `theorder`: Order records (customer name, phone, address, payment method, order status, total).
- `orderdetail`: Line items for each order referencing product, quantity, and unit price.
- `blog`: Articles and posts with thumbnail, content, author, and status.
- `messages`: Real-time chat messages between sender and receiver.
- `otp_codes`: Temporary phone login OTP codes with expiration timestamps.

---

## 🔌 Third-Party Integrations

```
+-------------------------------------------------------------------------+
|                                GoFood App                               |
+-------------------------------------------------------------------------+
       |                  |                 |                 |
       v                  v                 v                 v
 [OpenAI API]       [ZaloPay API]     [Pusher / WS]      [Twilio / SMTP]
  Smart Chatbot      Checkout & QR      Live Messages     Phone OTP & Mails
```

1. **OpenAI API**: Context-aware store assistant for natural language product query resolution.
2. **ZaloPay Gateway**: HMAC SHA256 signed payment gateway integration with callback/webhook handling.
3. **Pusher / Laravel WebSockets**: Event broadcasting on private/public channels.
4. **Twilio & SMTP**: OTP verification and support ticket delivery.
5. **Google & Facebook Socialite**: Single-click OAuth authentication.

---

## 🚀 Getting Started & Installation

### Prerequisites

Ensure your system meets the following requirements:
- **PHP**: `>= 7.3` or `>= 8.0` with extensions: `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`, `cURL`
- **Composer**: `>= 2.0`
- **Node.js & NPM**: Node.js `>= 14.x` & NPM `>= 6.x`
- **Database**: MySQL `>= 5.7` or MariaDB `>= 10.3`
- **Web Server**: Apache / Nginx / Laravel Built-in Server

---

### Step-by-Step Installation

#### 1. Clone the repository
```bash
git clone https://github.com/your-username/PHP_LARAVEL.git
cd PHP_LARAVEL
```

#### 2. Install PHP Dependencies
```bash
composer install
```

#### 3. Install & Compile Frontend Assets
```bash
npm install
npm run dev
```

#### 4. Configure Environment Variables
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```
Generate an application encryption key:
```bash
php artisan key:generate
```

#### 5. Configure Database & Services in `.env`
Open `.env` in your text editor and update your database credentials and API keys:

```env
APP_NAME="GoFood"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gofood_db
DB_USERNAME=root
DB_PASSWORD=your_password

# Broadcasting (Pusher or Laravel WebSockets)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=ap1

# OpenAI API Key (For Chatbot)
OPENAI_API_KEY=sk-your-openai-api-key

# Mail Configuration (For OTP & Support)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Social Logins (Google & Facebook)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret

FACEBOOK_CLIENT_ID=your_facebook_client_id
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret

# Twilio (Optional: For SMS OTP)
TWILIO_SID=your_twilio_sid
TWILIO_AUTH_TOKEN=your_twilio_auth_token
TWILIO_PHONE_NUMBER=your_twilio_number
```

#### 6. Run Migrations and Seeders
Create the database in MySQL (`gofood_db`), then execute:
```bash
php artisan migrate --seed
```
*This will create all tables and generate the default Admin account and initial seed categories.*

#### 7. Create Storage Symbolic Link
```bash
php artisan storage:link
```

#### 8. Start the Development Server
```bash
php artisan serve
```
The application will be accessible at: `http://127.0.0.1:8000`

*(Optional) If using Laravel WebSockets for local real-time broadcasting:*
```bash
php artisan websockets:serve
```

---

### 🔑 Default Credentials

After seeding the database with `AdminSeeder`:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `12345678` |

Admin Dashboard URL: `http://127.0.0.1:8000/dashboard`

---

## 📂 Project Directory Structure

```text
PHP_LARAVEL/
├── app/
│   ├── Events/                 # Broadcast events (MessageSent, ChatMessageSent)
│   ├── Http/
│   │   ├── Controllers/        # Business logic & Route handlers
│   │   │   ├── AdminController.php         # Admin management & metrics
│   │   │   ├── ChatbotController.php       # AI OpenAI Assistant
│   │   │   ├── GoogleAuthController.php    # Google OAuth
│   │   │   ├── FacebookAuthController.php  # Facebook OAuth
│   │   │   ├── PhoneLoginController.php    # OTP Phone authentication
│   │   │   ├── SendMessageController.php   # Real-time 1-on-1 Chat
│   │   │   ├── ShoppingCartController.php  # Cart & ZaloPay Checkout
│   │   │   └── ...
│   │   └── Middleware/         # Custom middleware (AdminMiddleware, etc.)
│   └── Models/                 # Eloquent ORM Models (Product, Order, User, etc.)
├── config/                     # Configuration files (services, broadcasting, auth)
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Database migration files
│   └── seeders/                # Seeders (AdminSeeder, DatabaseSeeder)
├── public/                     # Public web assets (css, js, frontend images, uploads)
├── resources/
│   ├── js/                     # Vue & JavaScript source files
│   ├── sass/                   # SCSS stylesheets
│   └── views/                  # Blade Templates
│       ├── admins/             # Admin dashboard & management views
│       ├── auth/               # Login, registration, phone OTP views
│       ├── layouts/            # Master layout wrappers
│       ├── pages/              # Storefront views (Home, Cart, Checkout, Chat, Blog)
│       └── chatbot.blade.php   # Chatbot interactive modal/interface
├── routes/
│   ├── api.php                 # API endpoints
│   ├── channels.php            # Broadcasting channel authorization
│   └── web.php                 # Web application routes
└── webpack.mix.js              # Asset compilation configuration
```

---

## 🚦 Route Overview

### 🛍 Public & Storefront Routes
| Method | URI | Action / Description |
| :--- | :--- | :--- |
| `GET` | `/` or `/home` | Main storefront home page |
| `GET` | `/productcategory/{id?}` | View products by category / group |
| `GET` | `/productDetails/{id}` | View detailed product specifications |
| `GET` | `/search` | Search products by name keyword |
| `GET` | `/blog` & `/blogDetail/{id}` | Blog list and single article view |
| `GET` | `/home/about` & `/support` | About us and Support contact page |
| `POST`| `/support/send` | Submit customer support inquiry via email |

### 🛒 Cart & Checkout Routes
| Method | URI | Action / Description |
| :--- | :--- | :--- |
| `GET` | `/cart` | View cart contents |
| `GET` | `/cart/add/{id}` | Add item to cart |
| `GET` | `/cart/remove/{id}` | Remove specific item from cart |
| `GET` | `/cart/clear` | Clear all items from cart |
| `POST`| `/cart/update` | Update item quantities via AJAX |
| `GET` | `/checkout` | Checkout form |
| `POST`| `/order/process` | Place order (Cash on Delivery) |
| `POST`| `/payWithZaloPay` | Initiate ZaloPay online payment |
| `POST`| `/zalopay/callback`| ZaloPay webhook transaction callback |

### 💬 Real-Time Chat & AI Bot
| Method | URI | Action / Description |
| :--- | :--- | :--- |
| `GET` | `/chatbot` | Open AI Chatbot UI |
| `POST`| `/chatbot` | Query AI Chatbot (OpenAI + Live Database) |
| `GET` | `/chat` | Real-time chat interface |
| `POST`| `/chat/send` | Send message (Broadcast via Pusher) |
| `GET` | `/chat/messages` | Fetch chat message history |

### 🔐 Authentication Routes
| Method | URI | Action / Description |
| :--- | :--- | :--- |
| `GET`/`POST` | `/login`, `/register`, `/logout` | Standard authentication |
| `GET` | `/auth/google` & `/auth/google/call-back` | Google OAuth Login |
| `GET` | `/auth/facebook` & `/auth/facebook/call-back` | Facebook OAuth Login |
| `GET`/`POST` | `/phone/login`, `/phone/send-otp` | Phone number OTP request |
| `GET`/`POST` | `/phone/verify`, `/phone/verify-otp` | OTP code verification & login |

### 🛡 Admin Dashboard Routes (`/admin` Protected)
| Method | URI | Action / Description |
| :--- | :--- | :--- |
| `GET` | `/dashboard` | Admin analytics & dashboard charts |
| `GET`/`POST` | `/productlists`, `/createproduct`, `/addproduct` | Product CRUD |
| `GET`/`POST` | `/get-product/{id}`, `/update-product/{id}`, `/delete-product/{id}` | Product editing & removal |
| `GET`/`POST` | `/categorylists`, `/createCategory`, `/addCategory` | Category CRUD |
| `POST`| `/admin/toggle-status/{id}` | Toggle category active status |
| `GET`/`POST` | `/grouplists`, `/creategroup`, `/addGroup` | Product group CRUD |
| `GET`/`POST` | `/orderlists`, `/confirmOrder/{id}`, `/orders/{id}` | Order review & fulfillment |
| `GET`/`POST` | `/bloglists`, `/createblog`, `/blog/add`, `/blog/update/{id}` | Blog post management |

---

## 🤝 Contributing

Contributions are welcome! If you'd like to improve GoFood:

1. Fork the project repository.
2. Create your feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

---

## 📄 License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).
