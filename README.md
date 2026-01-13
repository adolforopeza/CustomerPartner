# Aoropeza_CustomerPartner Module

This module enables the creation of unique Partner URLs that facilitate the automatic assignment of visitors to specific Customer Groups upon registration or login.

## Business Context

The core function of this module is to establish a 1:1 mapping between a specific URL and a Magento Customer Group.

**Example Scenario:**
-   **Partner A (e.g., eBay)**: You create a partner entry with URL key `ebay` linked to the "eBay Customers" group.
    -   URL: `http://yourstore.com/ebay`
    -   Result: Any user registering or logging in via this URL is assigned to the "eBay Customers" group.
-   **Partner B (e.g., Amazon)**: You create a partner entry with URL key `amazon` linked to the "Amazon Customers" group.
    -   URL: `http://yourstore.com/amazon`
    -   Result: Any user registering or logging in via this URL is assigned to the "Amazon Customers" group.

**Key Features:**
1.  **URL Registration**: Administrators can register specific URL keys (e.g., `ebay`, `amazon`).
2.  **Group Assignment**:
    -   **New Users**: If a visitor registers an account after visiting a partner URL, they are immediately assigned to the linked Customer Group.
    -   **Existing Users**: If an existing customer logs in via a partner URL, their account is updated to belong to the linked Customer Group.
3.  **Partner Specificity**: Each URL is unique to a specific partner and maps to a unique Customer Group context.

*Note: This module handles the assignment logic only. Any specific offers, pricing, or restrictions associated with the Customer Group must be configured separately using standard Magento features (e.g., Cart Price Rules, Catalog Price Rules).*

## Installation

### Composer
To install the module via Composer:

```bash
composer require aoropeza/customerpartner
bin/magento module:enable Aoropeza_CustomerPartner
bin/magento setup:upgrade
bin/magento setup:di:compile
```

## Usage

### Admin Configuration
1.  Go to the Magento Admin Panel.
2.  Navigate to the **Aoropeza Customer Partner** grid.
3.  **Create New Partner**:
    -   **Name**: The name of the partner (e.g., "eBay").
    -   **URL Key**: The unique URL segment (e.g., `ebay`).
    -   **Customer Group**: The specific group to assign (e.g., "eBay Customers").
    -   **Is Active**: Enable/Disable the link.

### Frontend Flow
1.  A user accesses the partner URL: `http://yourstore.com/partner/{url_key}`.
2.  The module sets a tracking cookie for that specific partner.
3.  **Registration**: When the user creates an account, the system reads the cookie and assigns the new account to the partner's Customer Group.
4.  **Login**: If a user logs in, the system reads the cookie and updates their existing account to the partner's Customer Group.

## API Reference

The module exposes standard REST endpoints for managing Customer Partner entities.

### Base URL
`/rest/V1/aoropeza-customerpartner/customer_partner`

### Endpoints

### 1. Create Customer Partner
**POST** `/rest/V1/aoropeza-customerpartner/customer_partner`

<details>
<summary>Request Payload</summary>

```json
{
  "customerPartner": {
    "name": "Summer VIP",
    "url_key": "summer-vip",
    "customer_group_id": 4,
    "is_active": 1,
    "description": "Exclusive access for summer partners"
  }
}
```
</details>

<details>
<summary>Response Example</summary>

```json
{
    "entity_id": 15,
    "name": "Summer VIP",
    "url_key": "summer-vip",
    "customer_group_id": 4,
    "is_active": 1,
    "description": "Exclusive access for summer partners",
    "created": "2026-01-12 10:00:00",
    "updated": "2026-01-12 10:00:00"
}
```
</details>

---

### 2. Update Customer Partner
**PUT** `/rest/V1/aoropeza-customerpartner/customer_partner/{id}`

<details>
<summary>Request Payload</summary>

```json
{
  "customerPartner": {
    "name": "Summer VIP Updated",
    "is_active": 0
  }
}
```
</details>

<details>
<summary>Response Example</summary>

```json
{
    "entity_id": 15,
    "name": "Summer VIP Updated",
    "url_key": "summer-vip",
    "customer_group_id": 4,
    "is_active": 0,
    "description": "Exclusive access for summer partners",
    "created": "2026-01-12 10:00:00",
    "updated": "2026-01-12 12:30:00"
}
```
</details>

---

### 3. Get Customer Partner Details
**GET** `/rest/V1/aoropeza-customerpartner/customer_partner/{id}`

<details>
<summary>Request Payload</summary>

*None*

</details>

<details>
<summary>Response Example</summary>

```json
{
    "entity_id": 15,
    "name": "Summer VIP Updated",
    "url_key": "summer-vip",
    "customer_group_id": 4,
    "is_active": 0,
    "description": "Exclusive access for summer partners",
    "created": "2026-01-12 10:00:00",
    "updated": "2026-01-12 12:30:00"
}
```
</details>

---

### 4. Search/List Customer Partners
**GET** `/rest/V1/aoropeza-customerpartner/customer_partner/search`

<details>
<summary>Request Payload</summary>

Parameters passed via query string (SearchCriteria).
Example: `?searchCriteria[filter_groups][0][filters][0][field]=is_active&searchCriteria[filter_groups][0][filters][0][value]=1`

</details>

<details>
<summary>Response Example</summary>

```json
{
    "items": [
        {
            "entity_id": 15,
            "name": "Summer VIP",
            "url_key": "summer-vip",
            "customer_group_id": 4,
            "is_active": 1,
            "description": "Exclusive access for summer partners",
            "created": "2026-01-12 10:00:00",
            "updated": "2026-01-12 10:00:00"
        }
    ],
    "search_criteria": {
        "filter_groups": [
            {
                "filters": [
                    {
                        "field": "is_active",
                        "value": "1",
                        "condition_type": "eq"
                    }
                ]
            }
        ]
    },
    "total_count": 1
}
```
</details>

---

### 5. Delete Customer Partner
**DELETE** `/rest/V1/aoropeza-customerpartner/customer_partner/{id}`

<details>
<summary>Request Payload</summary>

*None*

</details>

<details>
<summary>Response Example</summary>

```json
true
```
</details>
