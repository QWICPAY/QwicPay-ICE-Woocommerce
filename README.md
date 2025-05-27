# QwicPay Checkout for WooCommerce

![QwicPay Logo](https://qwicpay.com/assets/QwicPayLogo.png)

This integration enables your WooCommerce store to offer **QwicPay Instant Checkout**, providing a faster, seamless payment experience directly from the cart page.

> ⚠️ **IMPORTANT:** You will need a QwicPay merchant account. You can sign up at [https://www.qwicpay.com](https://www.qwicpay.com).

---

## 🚀 Features

- Adds a **QwicPay Checkout Faster** button to the cart page.
- Redirects customers to QwicPay’s secure instant checkout.
- Automatically passes cart contents, notes, promo codes, and currency.
- Fully configurable and easy to install.
- Direct Merchant portal access from WordPress

---

## Installation

1. Download the ZIP and upload via **Plugins → Add New → Upload Plugin**.
2. Activate the plugin in **Plugins → Installed Plugins**.
3. Go to **QwicPay → Settings** to enter your Merchant ID and other options. **Save**
4. Activate the plugin and ensure status becomes `Active`

---

## Custom Hook: `qwicpay_cart_hook`

The QwicPay Checkout plugin defines a custom action hook named `qwicpay_cart_hook` to give theme or plugin developers more flexibility if the over where the QwicPay button is rendered on the WooCommerce cart page if the provided hooks don't match the correct position.

### Purpose

This hook is useful when the default WooCommerce hooks like `woocommerce_cart_totals_after_order_total` or `woocommerce_proceed_to_checkout` do not fit your theme layout or design requirements.

### How to Enable

1. Go to **WooCommerce → Settings → QwicPay Settings**.
2. Under **Hook Location**, select `QwicPay Custom hook`.

### How to Use

In your theme’s `cart.php` template or a custom plugin, place the following line where you want the button to appear:

```php
do_action('qwicpay_cart_hook');
```

## **Permalinks:**

WordPress permalinks must be set to a human-readable format.  
 Go to `Settings > Permalinks` and choose any option **other than "Plain"**.  
 The **"Day and name"** structure is a great default and works well with QwicPay.

## 🔗 Checkout Endpoints

The following endpoints are appended to your store's page URLs to handle specific actions during the QwicPay Instant Checkout process. These endpoints must be unique and properly configured in WooCommerce:

| Endpoint Purpose | Endpoint Slug    |
| ---------------- | ---------------- |
| Pay              | `order-pay`      |
| Order Received   | `order-received` |

> ⚙️ **To set these endpoints in WooCommerce:**
>
> 1. Go to your WordPress Admin Dashboard.
> 2. Navigate to **WooCommerce → Settings → Advanced → Checkout Endpoints**.
> 3. Ensure the slugs `order-pay` and `order-received` are listed under the appropriate sections.

## Usage

1. **Cart & Checkout**  
   From WooCommerce **Settings → QwicPay Settings**, choose your “Hook Location.” The plugin will render a full-width (100%) button at that hook on cart and checkout pages.

2. **Mini-cart Drawer**  
   The plugin automatically injects a half-width (50%) button under the “View cart”/“Checkout” buttons in the WooCommerce mini-cart drawer.

3. **Merchant Portal**  
   In the WordPress admin sidebar, open **QwicPay → Merchant Access Portal** to view for payment dashboard

---

---

## 🎨 Button Options

Choose from 4 button styles hosted by QwicPay:

**1. Round Blue**  
![Round Blue](https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText.svg)  
`https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText.svg`

**2. Square Blue**  
![Square Blue](<https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText+(Squared).svg>)  
`https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText+(Squared).svg`

**3. Round White**  
![Round White](https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText.svg)  
`https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText.svg`

**4. Square White**  
![Square White](<https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText+(Squared).svg>)  
`https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText+(Squared).svg`

---

## Plugin Options

| Option        | Description                                        | Default                                     |
| ------------- | -------------------------------------------------- | ------------------------------------------- |
| Hook Location | Where to display the button on cart/checkout pages | `woocommerce_cart_totals_after_order_total` |
| Merchant ID   | Your QwicPay merchant identifier                   | _(empty — must be set)_                     |
| Stage         | Test or Production environment                     | `test`                                      |
| Currency      | Checkout currency                                  | `ZAR`                                       |
| Button Style  | URL of the QwicPay button image                    | Blue round button SVG                       |

## 📄 License & Legal

This code is the property of **QwicPay Pty Ltd**.  
Copying, modifying, or using this code without express permission is strictly prohibited.

---

## 💬 Support

If you have any questions or require assistance with integration:

📧 Email: `support@qwicpay.com`  
🌐 Website: `https://qwicpay.com`

---

## Changelog

### 1.2.12

- Added full/half-width rendering via separate callbacks.
- Refactored shared renderer for button HTML.

### 1.1.12

- Introduced dedicated admin menu with QwicPay icon.
- Merchant Access Portal page with iframe.

### 1.1.8

- Initial top-level menu and submenus.

### 1.0.0

- Initial release: settings tab, button placement, uptime check.

---

## External Contributors

Thank you to the following community members for their contributions:

- **@Enrico1109** — WooCommerce inital settings menu

---

Made with Care by [QwicPay](https://qwicpay.com)
