=== QwicPay Checkout ===
Contributors: qwicpay
Tags: qwicpay, instant checkout, woocommerce, payment, south africa
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.2.12
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This integration enables your WooCommerce store to offer QwicPay Instant Checkout and join our ecosystem.

== Description ==

QwicPay Checkout for WooCommerce adds an instant checkout option to your store, letting customers checkout directly from the cart page with their saved payment methods. It connects securely to QwicPay's systems and supports passing cart contents, promo codes, and currency.

= 🚀 Features =
* Adds a **QwicPay Checkout Faster** button to the cart page.
* Redirects customers to QwicPay’s secure instant checkout.
* Passes cart contents, notes, promo codes, and currency.
* Fully configurable and easy to install.
* Direct Merchant portal access from WordPress.

== Installation ==

1. Download the ZIP and upload via **Plugins → Add New → Upload Plugin**.
2. Activate the plugin in **Plugins → Installed Plugins**.
3. Go to **QwicPay → Settings** to enter your Merchant ID and other options.
4. Activate the plugin and ensure status becomes "Active".

= Permalinks =
WordPress permalinks must be set to a human-readable format.
Go to **Settings → Permalinks** and choose any option **other than "Plain"**.
The **"Day and name"** structure is a great default and works well with QwicPay.

= 🔗 Checkout Endpoints =
| Endpoint Purpose | Endpoint Slug |
| ---------------- | ------------- |
| Pay              | order-pay     |
| Order Received   | order-received|

**To set these endpoints:**
1. Go to WooCommerce → Settings → Advanced → Checkout Endpoints.
2. Ensure `order-pay` and `order-received` slugs are listed.

== Frequently Asked Questions ==

= Can I change the button style? =
Yes! Choose from 4 styles hosted by QwicPay.



== Changelog ==

= 1.2.12 =
* Added full/half-width rendering via separate callbacks.
* Refactored shared renderer for button HTML.

= 1.1.12 =
* Introduced dedicated admin menu with QwicPay icon.
* Merchant Access Portal page with iframe.

= 1.1.8 =
* Initial top-level menu and submenus.

= 1.0.0 =
* Initial release: settings tab, button placement, uptime check.

== Upgrade Notice ==

= 1.2.12 =
Latest version with width options and button renderer improvements.

== Arbitrary section ==

= Plugin Options =
| Option         | Description                                      | Default |
| -------------- | ------------------------------------------------ | ------- |
| Hook Location  | Where to display the button                      | woocommerce_cart_totals_after_order_total |
| Merchant ID    | Your QwicPay merchant ID                         | (empty — must be set) |
| Stage          | Test or Production                               | test    |
| Currency       | Checkout currency                                | ZAR     |
| Button Style   | URL of QwicPay button image                      | Blue round button SVG |

== License ==

This code is the property of **QwicPay Pty Ltd**.
Copying, modifying, or using without permission is prohibited.

== Support ==

For assistance:
* Email: support@qwicpay.com
* Website: https://qwicpay.com

== Contributors ==
Thanks to community members:
* @Enrico1109 — WooCommerce initial settings menu

Made with care by QwicPay (https://qwicpay.com)
