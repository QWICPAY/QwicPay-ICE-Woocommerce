# QwicPay-ICE-Woocommerce


![QwicPay Logo](https://qwicpay.com/assets/QwicPayLogo.png)

This integration enables your WooCommerce store to offer **QwicPay Instant Checkout**, providing a faster, seamless payment experience directly from the cart page.

> ⚠️ **IMPORTANT:** This code is the intellectual property of QwicPay Pty Ltd (Registration No. K2024202050). Unauthorized use or distribution is strictly prohibited.

---

## 🚀 Features

- Adds a **QwicPay Checkout Faster** button to the cart page.
- Redirects customers to QwicPay’s secure instant checkout.
- Automatically passes cart contents, notes, promo codes, and currency.
- Fully configurable and easy to install.

---

## 📦 Installation

To enable the QwicPay button on your WooCommerce store:

1. **Access your WordPress Theme Editor:**
   - Go to your WordPress dashboard.
   - Navigate to: `Appearance > Theme File Editor`.

2. **Paste the Code:**
   - Locate and open the `functions.php` file in your active theme.
   - Paste the contents of [`functions.php`](functions.php) from this repository at the bottom of your theme’s existing `functions.php` file via the WordPress Theme Editor.
   - Update the configuration values as required (see below).

3. **Configure the Code:**
   - Replace the following within the `//START CONFIG` section:
     - `merchantid` – Your assigned merchant ID from QwicPay.
     - `stage` – Use `'test'` for testing or `'PROD'` for live production.
     - Choose your preferred QwicPay button design by URL.

---

## **Permalinks:** 

  WordPress permalinks must be set to a human-readable format.  
  Go to `Settings > Permalinks` and choose any option **other than "Plain"**.  
  The **"Day and name"** structure is a great default and works well with QwicPay.


## 🔗 Checkout Endpoints

The following endpoints are appended to your store's page URLs to handle specific actions during the QwicPay Instant Checkout process. These endpoints must be unique and properly configured in WooCommerce:

| Endpoint Purpose   | Endpoint Slug   |
|--------------------|-----------------|
| Pay                | `order-pay`     |
| Order Received     | `order-received`|

> ⚙️ **To set these endpoints in WooCommerce:**
>
> 1. Go to your WordPress Admin Dashboard.
> 2. Navigate to **WooCommerce → Settings → Advanced → Checkout Endpoints**.
> 3. Ensure the slugs `order-pay` and `order-received` are listed under the appropriate sections.


## ✅ Activation Instructions

After adding the code to your site:

1. Visit `https://integrate.qwicpay.com/link`
2. Enter your WooCommerce **store URL** (e.g., `demo.qwicpay.com`)
3. Approve the QwicPay integration request in your WooCommerce backend.

Once approved, your store is fully linked with QwicPay Instant Checkout.

---

## 🛠 Configuration Example

```php
$merchantid = 'YOUR_MERCHANT_ID';
$stage = 'test'; // Use 'PROD' for production
$button_url = 'https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText.svg';
```


---

## 🎨 Button Options

Choose from 4 button styles hosted by QwicPay:

**1. Round Blue**  
![Round Blue](https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText.svg)  
`https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText.svg`

**2. Square Blue**  
![Square Blue](https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText+(Squared).svg)  
`https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText+(Squared).svg`

**3. Round White**  
![Round White](https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText.svg)  
`https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText.svg`

**4. Square White**  
![Square White](https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText+(Squared).svg)  
`https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText+(Squared).svg`
---

## 📄 License & Legal

This code is the property of **QwicPay Pty Ltd**.  
Copying, modifying, or using this code without express permission is strictly prohibited.

---

## 💬 Support

If you have any questions or require assistance with integration:

📧 Email: `support@qwicpay.com`  
🌐 Website: `https://qwicpay.com`

---

## 🔗 Code Snippet

Paste this snippet into your `functions.php` file.  
👉 _The full QwicPay integration code is located at the top of this documentation._

---

Made with Care by [QwicPay](https://qwicpay.com)
