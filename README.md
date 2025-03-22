# Order Approval System

This is a **Laravel-based Order Approval System** that allows users to **create orders, approve them, and track order history** through a **2-step approval workflow**.

---

## Features
- **Create new orders** with multiple items.
- **Auto-generate unique, sequential order numbers**.
- **Calculate total order amount**.
- **Approval workflow**:  
  - Orders **above $1000 require approval**.  
  - Orders **below $1000 are auto-approved**.
- **Track order history**.

---

## ⚙️ Installation

**Clone the repository**  
```sh
git clone https://github.com/amerr000/order-approval.git
cd order-approval
```

**Install PHP dependencies**
```sh
composer install
```

**Set up environment file**
```sh
cp .env.example .env
php artisan key:generate
```

**Configure the database**
Update your .env file with your database settings.

**Run migrations**
```sh
php artisan migrate
```

**Start the server**
```sh
php artisan serve
```

**Running Tests**
To ensure the system is working correctly, you can run the test suite using the following command:
```sh
php artisan test

```

---

## API Endpoints and documentation

### URL: POST /api/orders
**description**: Creates an order with the given items
**Request Body**
```json
{
    "items": [
        { "item_name": "Laptop", "quantity": 1, "price": 1200 },
        { "item_name": "Mouse", "quantity": 2, "price": 25 }
    ]
}
```
**Response**
```json
{
    "order_number": "ORD00001",
    "total": 1250,
    "status": "pending",
    "updated_at": "2025-03-22T10:51:09.000000Z",
    "created_at": "2025-03-22T10:51:09.000000Z",
    "id": 1
}
```

### URL: POST /api/orders/{id}/approve
**Description**: Approves an order with the given ID.
**Response**
```json
{
    "message": "Order approved"
}
```

### URL: GET /api/orders/{id}/history
**description**: Fetches status changes for a given order
**Response**
```json
[
    {
        "id": 1,
        "order_id": 1,
        "status": "pending",
        "created_at": "2025-03-22T10:51:09.000000Z",
        "updated_at": "2025-03-22T10:51:09.000000Z"
    },
    {
        "id": 2,
        "order_id": 1,
        "status": "approved",
        "created_at": "2025-03-22T10:51:42.000000Z",
        "updated_at": "2025-03-22T10:51:42.000000Z"
    }
]
```